<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ArticleService;
use App\Services\FacebookService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ArticleController extends Controller
{
    protected $articleService;
    protected $imageService;

    public function __construct(
        ArticleService $articleService, 
        ImageService $imageService
    ) {
        $this->articleService = $articleService;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of articles
     */
    public function index(Request $request)
    {
        $query = Article::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // If author, only show their articles
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin()) {
            $query->where('author_id', $user->id);
        }

        $articles = $query->with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $series = \App\Models\Series::where('is_active', true)->orderBy('title')->get();
        return view('admin.articles.create', compact('categories', 'tags', 'series'));
    }

    protected function handleFeaturedImage(Request $request, $slug = null, $currentImage = null)
    {
        $newPath = null;

        // 1. Handle file upload (highest priority)
        if ($request->hasFile('featured_image_file')) {
            $path = $this->imageService->convertToWebp($request->file('featured_image_file'), 'articles/featured', $slug);
            if ($path) {
                $newPath = '/storage/' . $path;
            }
        }

        // 2. Handle URL (e.g. from scraper)
        if (!$newPath && $request->filled('featured_image') && filter_var($request->featured_image, FILTER_VALIDATE_URL)) {
            try {
                $client = new Client(['timeout' => 10, 'verify' => false]);
                $response = $client->get($request->featured_image);
                $contents = $response->getBody()->getContents();
                
                $extension = 'jpg'; // Default
                $contentType = $response->getHeaderLine('Content-Type');
                if (strpos($contentType, 'image/png') !== false) $extension = 'png';
                if (strpos($contentType, 'image/webp') !== false) $extension = 'webp';
                if (strpos($contentType, 'image/jpeg') !== false) $extension = 'jpg';
                
                $filename = ($slug ?: uniqid()) . '.' . $extension;
                $tempPath = storage_path('app/temp/' . $filename);
                if (!file_exists(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0755, true);
                }
                file_put_contents($tempPath, $contents);
                
                // Use image service to convert to webp and store
                $file = new \Illuminate\Http\UploadedFile($tempPath, $filename, $contentType, null, true);
                $path = $this->imageService->convertToWebp($file, 'articles/featured', $slug);
                
                // Clean up temp file
                @unlink($tempPath);
                
                if ($path) {
                    $newPath = '/storage/' . $path;
                }
            } catch (\Exception $e) {
                Log::error('Featured Image Download Error: ' . $e->getMessage());
            }
        }

        // If we have a new path, delete the old local image if it exists
        if ($newPath && $currentImage && str_starts_with($currentImage, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $currentImage);
            $this->imageService->delete($oldPath);
            return $newPath;
        }

        // If no new image was uploaded/downloaded, keep existing value (if it's already a path)
        return $newPath ?: $request->featured_image;
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'excerpt' => 'nullable|string|max:500',
            'quick_answer' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'featured_image_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:10240',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_title' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'series_id' => 'nullable|exists:article_series,id',
            'series_order' => 'nullable|integer|min:1',
            'author_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:published,draft,scheduled',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'post_to_facebook' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'short_video_id' => 'nullable|string|max:255',
        ]);

        // Set defaults
        $validated['status'] = $validated['status'] ?? 'published';
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['allow_comments'] = $validated['allow_comments'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        
        // If author (not admin), force author_id to be themselves
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin()) {
            $validated['author_id'] = $user->id;
        } else {
            $validated['author_id'] = $validated['author_id'] ?? $user->id;
        }

        // Handle published_at
        if ($validated['status'] === 'scheduled' && $validated['published_at']) {
            $validated['published_at'] = Carbon::parse($validated['published_at']);
        } elseif ($validated['status'] === 'published' && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        // Handle featured image
        $validated['featured_image'] = $this->handleFeaturedImage($request, $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['title']));
        unset($validated['featured_image_file']);

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $article = Article::create($validated);
        
        // Attach tags
        if (!empty($tags)) {
            $article->tags()->sync($tags);
        }

        // Create initial revision
        if ($article->exists) {
            $article->createRevision(Auth::id(), 'Initial version');
        }

        // Handle scheduled publishing
        if ($validated['status'] === 'scheduled' && isset($validated['published_at'])) {
            $publishDate = Carbon::parse($validated['published_at']);
            if ($publishDate->isFuture()) {
                \App\Jobs\PublishScheduledArticle::dispatch($article)->delay($publishDate);
            }
        }

        // Post to social media platforms if article is published and user opted in
        if ($validated['status'] === 'published') {
            if (config('services.facebook.enabled', false) && ($request->has('post_to_facebook') && $request->post_to_facebook)) {
                \App\Jobs\PostToFacebookJob::dispatch($article);
            }
            if (config('services.twitter.enabled', false) && ($request->has('post_to_twitter') && $request->post_to_twitter)) {
                \App\Jobs\PostToTwitterJob::dispatch($article);
            }
            if (config('services.instagram.enabled', false) && ($request->has('post_to_instagram') && $request->post_to_instagram)) {
                \App\Jobs\PostToInstagramJob::dispatch($article);
            }
            if (config('services.threads.enabled', false) && ($request->has('post_to_threads') && $request->post_to_threads)) {
                \App\Jobs\PostToThreadsJob::dispatch($article);
            }
        }

        // Clear cache
        $this->articleService->clearCache();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Article saved successfully.',
                'article_id' => $article->id,
            ]);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Auto-save draft
     */
    public function autoSave(Request $request, Article $article = null)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'quick_answer' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'featured_image_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:10240',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image_file')) {
            $path = $this->imageService->convertToWebp($request->file('featured_image_file'), 'articles/featured');
            if ($path) {
                $validated['featured_image'] = '/storage/' . $path;
            }
        }
        unset($validated['featured_image_file']);

        // If article exists, update it; otherwise create new draft
        if ($article) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            // Check permission
            if ($user->isAuthor() && !$user->isAdmin() && $article->author_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $article->update(array_merge($validated, [
                'status' => 'draft', // Always save as draft when auto-saving
            ]));
        } else {
            $article = Article::create(array_merge($validated, [
                'author_id' => Auth::id(),
                'status' => 'draft',
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Draft saved successfully.',
            'article_id' => $article->id,
        ]);
    }

    /**
     * Display the specified article
     */
    public function show(Article $article)
    {
        $article->load(['category', 'author', 'tags', 'comments']);
        
        // Generate URLs
        $articleUrl = route('articles.show', $article->slug);
        
        return view('admin.articles.show', compact('article', 'articleUrl'));
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(Article $article)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Check permission
        if ($user->isAuthor() && !$user->isAdmin() && $article->author_id !== $user->id) {
            abort(403, 'You can only edit your own articles.');
        }

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $series = \App\Models\Series::where('is_active', true)->orderBy('title')->get();
        $article->load('tags', 'series');
        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'series'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Article $article)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Check permission
        if ($user->isAuthor() && !$user->isAdmin() && $article->author_id !== $user->id) {
            abort(403, 'You can only edit your own articles.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'excerpt' => 'nullable|string|max:500',
            'quick_answer' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'featured_image_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:10240',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_title' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'series_id' => 'nullable|exists:article_series,id',
            'series_order' => 'nullable|integer|min:1',
            'author_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:published,draft,scheduled',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'post_to_facebook' => 'nullable|boolean',
            'post_to_twitter' => 'nullable|boolean',
            'post_to_instagram' => 'nullable|boolean',
            'post_to_threads' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'short_video_id' => 'nullable|string|max:255',
        ]);

        // If author (not admin), prevent changing author_id and is_featured
        if ($user->isAuthor() && !$user->isAdmin()) {
            $validated['author_id'] = $user->id;
            $validated['is_featured'] = false; // Authors can't feature their own articles
        }

        // Handle published_at
        if ($validated['status'] === 'scheduled' && $validated['published_at']) {
            $validated['published_at'] = Carbon::parse($validated['published_at']);
        } elseif ($validated['status'] === 'published' && !$validated['published_at']) {
            $validated['published_at'] = $validated['published_at'] ?? now();
        }

        // Check for significant changes before updating
        $changedFields = ['title', 'content', 'excerpt'];
        $hasChanges = false;
        foreach ($changedFields as $field) {
            if (isset($validated[$field]) && $article->$field !== $validated[$field]) {
                $hasChanges = true;
                break;
            }
        }

        // Create revision before updating if there are changes
        if ($article->exists && $hasChanges) {
            $article->createRevision($user->id);
        }

        // Handle featured image
        $validated['featured_image'] = $this->handleFeaturedImage($request, $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['title']), $article->featured_image);
        unset($validated['featured_image_file']);

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $article->update($validated);

        // Sync tags
        $article->tags()->sync($tags);

        // Handle scheduled publishing
        if ($validated['status'] === 'scheduled' && isset($validated['published_at'])) {
            $publishDate = Carbon::parse($validated['published_at']);
            if ($publishDate->isFuture()) {
                \App\Jobs\PublishScheduledArticle::dispatch($article)->delay($publishDate);
            }
        }

        // Post to social media platforms if article status changed to published and user opted in
        if ($validated['status'] === 'published' && $article->wasChanged('status')) {
            if (config('services.facebook.enabled', false) && ($request->has('post_to_facebook') && $request->post_to_facebook)) {
                \App\Jobs\PostToFacebookJob::dispatch($article->fresh());
            }
            if (config('services.twitter.enabled', false) && ($request->has('post_to_twitter') && $request->post_to_twitter)) {
                \App\Jobs\PostToTwitterJob::dispatch($article->fresh());
            }
            if (config('services.instagram.enabled', false) && ($request->has('post_to_instagram') && $request->post_to_instagram)) {
                \App\Jobs\PostToInstagramJob::dispatch($article->fresh());
            }
            if (config('services.threads.enabled', false) && ($request->has('post_to_threads') && $request->post_to_threads)) {
                \App\Jobs\PostToThreadsJob::dispatch($article->fresh());
            }
        }

        // Clear cache
        $this->articleService->clearCache();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Article saved successfully.',
            ]);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified article
     */
    public function destroy(Article $article)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Only admins can delete
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can delete articles.');
        }

        // Delete images from storage if they are local
    foreach (['featured_image', 'og_image', 'twitter_image'] as $field) {
        if ($article->$field && str_starts_with($article->$field, '/storage/')) {
            $path = str_replace('/storage/', '', $article->$field);
            $this->imageService->delete($path);
        }
    }

    $article->delete();

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    /**
     * Post article to Facebook
     */
    public function postToFacebook($id, FacebookService $facebookService)
    {
        try {
            // Find article (including soft-deleted ones for admin)
            $article = Article::withTrashed()->findOrFail($id);
            
            // Check if article is published
            if ($article->status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only published articles can be posted to Facebook.',
                ], 400);
            }

            // Check if Facebook is enabled
            if (!config('services.facebook.enabled', false)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facebook integration is not enabled. Please enable it in settings.',
                ], 400);
            }

            // Post to Facebook
            $result = $facebookService->postArticle($article);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Article posted to Facebook successfully!',
                    'post_id' => $result['post_id'] ?? null,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to post to Facebook.',
                ], 500);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error posting to Facebook', [
                'article_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle image upload from TinyMCE
     */
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        
        // Convert to WebP
        $path = $this->imageService->convertToWebp($file, 'articles/content');

        if (!$path) {
            return response()->json(['error' => 'Failed to process image'], 500);
        }

        return response()->json([
            'location' => '/storage/' . $path
        ]);
    }

    /**
     * Scrape article content from a URL
     */
    public function scrape(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->url;
        
        try {
            $client = new Client([
                'timeout' => 30,
                'verify' => false, // Bypass SSL for local development or tricky sites
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]);

            $response = $client->get($url);
            $html = (string) $response->getBody();
            
            $crawler = new Crawler($html);
            
            // Site-specific selectors (ThereViewGeek)
            $title = '';
            $content = '';
            $image = '';
            
            if (strpos($url, 'thereviewgeek.com') !== false) {
                $title = $crawler->filter('h1.entry-title')->count() ? $crawler->filter('h1.entry-title')->text() : '';
                
                // Content extraction
                $contentCrawler = $crawler->filter('.entry-content');
                if ($contentCrawler->count()) {
                    // Remove unwanted elements
                    $contentCrawler->filter('.sharedaddy, .wpcnt, .jp-relatedposts, .sd-block, .shared-counts-wrap')->each(function (Crawler $node) {
                        $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                    });
                    
                    $content = $contentCrawler->html();
                }
                
                // Featured image
                $image = $crawler->filter('meta[property="og:image"]')->count() ? $crawler->filter('meta[property="og:image"]')->attr('content') : '';
            } else {
                // Fallback for other sites
                $title = $crawler->filter('h1')->count() ? $crawler->filter('h1')->first()->text() : '';
                $contentCrawler = $crawler->filter('article')->count() ? $crawler->filter('article')->first() : $crawler->filter('main')->first();
                if ($contentCrawler->count()) {
                    $content = $contentCrawler->html();
                }
                $image = $crawler->filter('meta[property="og:image"]')->count() ? $crawler->filter('meta[property="og:image"]')->attr('content') : '';
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => trim($title),
                    'content' => $content,
                    'featured_image' => $image,
                    'slug' => \Illuminate\Support\Str::slug($title)
                ]
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Scraper Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'error' => 'Failed to scrape URL. Check server logs for details. Message: ' . $e->getMessage()
            ], 500);
        }
    }
}
