<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SeriesController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of series
     */
    public function index(Request $request)
    {
        $query = Series::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // If author, only show their series
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin()) {
            $query->where('author_id', $user->id);
        }

        $series = $query->withCount('articles')
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.series.index', compact('series'));
    }

    /**
     * Show the form for creating a new series
     */
    public function create()
    {
        return view('admin.series.create');
    }

    /**
     * Store a newly created series
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:article_series,slug',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $path = $this->imageService->convertToWebp($request->file('featured_image'), 'series');
            if ($path) {
                $validated['featured_image'] = $path;
            }
        }

        // Set defaults
        $validated['author_id'] = Auth::id();
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Series::create($validated);

        return redirect()->route('admin.series.index')
            ->with('success', 'Series created successfully.');
    }

    /**
     * Display the specified series
     */
    public function show(Series $series, Request $request)
    {
        $series->load(['author', 'articles' => function($query) {
            $query->orderBy('series_order', 'asc');
        }]);
        
        // Get articles not in this series for the add articles dropdown
        $existingArticleIds = $series->articles->pluck('id')->toArray();
        $availableArticles = \App\Models\Article::whereNotIn('id', $existingArticleIds)
            ->orderBy('title', 'asc')
            ->get(['id', 'title', 'status']);
        
        return view('admin.series.show', compact('series', 'availableArticles'));
    }
    
    /**
     * Add an article to the series
     */
    public function addArticle(Request $request, Series $series)
    {
        // Check permission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin() && $series->author_id !== $user->id) {
            abort(403, 'You can only manage articles in your own series.');
        }
        
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'series_order' => 'nullable|integer|min:1',
        ]);
        
        $article = \App\Models\Article::findOrFail($validated['article_id']);
        
        // Check if article already in series
        if ($article->series_id == $series->id) {
            return back()->with('error', 'Article is already in this series.');
        }
        
        // Check if article is already in another series
        if ($article->series_id && $article->series_id != $series->id) {
            return back()->with('error', 'Article is already in another series. Please remove it from that series first.');
        }
        
        // Get the next order number if not provided
        $maxOrder = $series->articles()->max('series_order') ?? 0;
        $seriesOrder = $validated['series_order'] ?? ($maxOrder + 1);
        
        $article->update([
            'series_id' => $series->id,
            'series_order' => $seriesOrder,
        ]);
        
        return back()->with('success', 'Article added to series successfully.');
    }
    
    /**
     * Remove an article from the series
     */
    public function removeArticle(Request $request, Series $series, \App\Models\Article $article)
    {
        // Check permission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin() && $series->author_id !== $user->id) {
            abort(403, 'You can only manage articles in your own series.');
        }
        
        if ($article->series_id != $series->id) {
            return back()->with('error', 'Article is not in this series.');
        }
        
        $article->update([
            'series_id' => null,
            'series_order' => null,
        ]);
        
        return back()->with('success', 'Article removed from series successfully.');
    }
    
    /**
     * Update article order in series
     */
    public function updateArticleOrder(Request $request, Series $series)
    {
        // Check permission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin() && $series->author_id !== $user->id) {
            abort(403, 'You can only manage articles in your own series.');
        }
        
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'series_order' => 'required|integer|min:1',
        ]);
        
        $article = \App\Models\Article::findOrFail($validated['article_id']);
        
        if ($article->series_id != $series->id) {
            return back()->with('error', 'Article is not in this series.');
        }
        
        $article->update([
            'series_order' => $validated['series_order'],
        ]);
        
        return back()->with('success', 'Article order updated successfully.');
    }

    /**
     * Show the form for editing the specified series
     */
    public function edit(Series $series)
    {
        // Check permission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin() && $series->author_id !== $user->id) {
            abort(403, 'You can only edit your own series.');
        }

        return view('admin.series.edit', compact('series'));
    }

    /**
     * Update the specified series
     */
    public function update(Request $request, Series $series)
    {
        // Check permission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAuthor() && !$user->isAdmin() && $series->author_id !== $user->id) {
            abort(403, 'You can only edit your own series.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:article_series,slug,' . $series->id,
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($series->featured_image && Storage::disk('public')->exists($series->featured_image)) {
                Storage::disk('public')->delete($series->featured_image);
            }

            $path = $this->imageService->convertToWebp($request->file('featured_image'), 'series');
            if ($path) {
                $validated['featured_image'] = $path;
            }
        } else {
            unset($validated['featured_image']);
        }

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $series->update($validated);

        return redirect()->route('admin.series.index')
            ->with('success', 'Series updated successfully.');
    }

    /**
     * Remove the specified series
     */
    public function destroy(Series $series)
    {
        // Only admins can delete
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can delete series.');
        }

        $series->delete();

        return redirect()->route('admin.series.index')
            ->with('success', 'Series deleted successfully.');
    }
}
