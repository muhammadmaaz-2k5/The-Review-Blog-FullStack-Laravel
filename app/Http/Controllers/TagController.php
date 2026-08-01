<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\ArticleService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $articleService;
    protected $seoService;

    public function __construct(ArticleService $articleService, SeoService $seoService)
    {
        $this->articleService = $articleService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of tags
     */
    public function index(Request $request)
    {
        $query = Tag::withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'articles');
        $sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'articles':
                $query->orderBy('articles_count', $sortOrder);
                break;
            case 'latest':
                $query->orderByRaw('(SELECT MAX(articles.published_at) FROM article_tag INNER JOIN articles ON article_tag.article_id = articles.id WHERE article_tag.tag_id = tags.id AND articles.status = "published") ' . $sortOrder);
                break;
            default:
                $query->orderBy('articles_count', 'desc')->orderBy('name', 'asc');
        }

        $tags = $query->get();

        // Load latest article for each tag (optimized to avoid N+1)
        $tagIds = $tags->pluck('id');
        $latestArticles = \App\Models\Article::published()
            ->select('articles.id', 'articles.published_at', 'articles.created_at', 'article_tag.tag_id')
            ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
            ->whereIn('article_tag.tag_id', $tagIds)
            ->orderBy('article_tag.tag_id')
            ->orderBy('articles.published_at', 'desc')
            ->get()
            ->groupBy('tag_id')
            ->map(function($articles) {
                return $articles->first();
            });

        // Attach latest article to each tag
        foreach ($tags as $tag) {
            $tag->latest_article = $latestArticles->get($tag->id);
        }

        // Calculate statistics
        $totalTags = Tag::withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0)
            ->count();
        $totalArticles = \App\Models\Article::published()->count();
        $totalViews = \App\Models\Article::published()->sum('views');

        // Get featured tags (tags with most articles)
        $featuredTags = Tag::withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('articles_count', 'desc')
            ->limit(6)
            ->get();

        return view('tags.index', [
            'tags' => $tags,
            'featuredTags' => $featuredTags,
            'totalTags' => $totalTags,
            'totalArticles' => $totalArticles,
            'totalViews' => $totalViews,
            'search' => $request->get('search'),
            'sort' => $sortBy,
            'order' => $sortOrder,
            'seo' => $this->seoService->forTagsIndex(),
        ]);
    }

    /**
     * Display articles with a specific tag
     */
    public function show($slug, Request $request)
    {
        $tag = Tag::where('slug', $slug)
            ->firstOrFail();

        $perPage = 15;
        $articles = $this->articleService->getArticlesByTag($tag, $perPage);
        $articles->appends($request->query());

        $featuredArticles = $this->articleService->getFeaturedArticles(5);
        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(10);

        return view('tags.show', [
            'tag' => $tag,
            'articles' => $articles,
            'featuredArticles' => $featuredArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'seo' => $this->seoService->forTag($tag),
        ]);
    }

    /**
     * Load more articles for a tag (AJAX)
     */
    public function loadMore($slug, Request $request)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $page = $request->get('page', 2);
        $perPage = 15;
        
        $articles = $this->articleService->getArticlesByTag($tag, $perPage, $page);
        
        if ($articles->isEmpty()) {
            return response()->json([
                'success' => false,
                'html' => '',
                'hasMore' => false,
            ]);
        }
        
        $html = view('articles._load_more_cards', ['articles' => $articles])->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'hasMore' => $articles->hasMorePages(),
            'nextPage' => $articles->currentPage() + 1,
        ]);
    }
}

