<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ArticleService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $articleService;
    protected $seoService;

    public function __construct(ArticleService $articleService, SeoService $seoService)
    {
        $this->articleService = $articleService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = Category::where('is_active', true)
            ->withCount(['articles' => function($query) {
                $query->published();
            }])
            ->with(['articles' => function($query) {
                $query->published()
                    ->orderBy('published_at', 'desc')
                    ->limit(1)
                    ->select('id', 'category_id', 'published_at', 'views');
            }]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'sort_order');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'articles':
                $query->orderBy('articles_count', $sortOrder);
                break;
            case 'latest':
                $query->orderByRaw('(SELECT MAX(published_at) FROM articles WHERE category_id = categories.id AND status = "published") ' . $sortOrder);
                break;
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
        }

        $categories = $query->get();

        // Calculate statistics
        $totalCategories = Category::where('is_active', true)->count();
        $totalArticles = \App\Models\Article::published()->count();
        $totalViews = \App\Models\Article::published()->sum('views');

        // Get featured categories (categories with most articles)
        $featuredCategories = Category::where('is_active', true)
            ->withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('articles_count', 'desc')
            ->limit(6)
            ->get();

        return view('categories.index', [
            'categories' => $categories,
            'featuredCategories' => $featuredCategories,
            'totalCategories' => $totalCategories,
            'totalArticles' => $totalArticles,
            'totalViews' => $totalViews,
            'search' => $request->get('search'),
            'sort' => $sortBy,
            'order' => $sortOrder,
            'seo' => $this->seoService->forCategoriesIndex(),
        ]);
    }

    /**
     * Display articles in a specific category
     */
    public function show($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $perPage = $request->get('per_page', 15);
        
        // Build articles query with filtering and sorting
        $articlesQuery = \App\Models\Article::published()
            ->where('category_id', $category->id)
            ->with(['author', 'tags', 'category']);

        // Filter by date range
        if ($request->has('date_from')) {
            $articlesQuery->whereDate('published_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $articlesQuery->whereDate('published_at', '<=', $request->date_to);
        }

        // Filter by reading time
        if ($request->has('reading_time')) {
            $readingTime = $request->reading_time;
            if ($readingTime === 'short') {
                $articlesQuery->where('reading_time', '<=', 5);
            } elseif ($readingTime === 'medium') {
                $articlesQuery->whereBetween('reading_time', [6, 15]);
            } elseif ($readingTime === 'long') {
                $articlesQuery->where('reading_time', '>', 15);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $articlesQuery->orderBy('views', 'desc')->orderBy('published_at', 'desc');
                break;
            case 'oldest':
                $articlesQuery->orderBy('published_at', 'asc');
                break;
            case 'reading_time':
                $articlesQuery->orderBy('reading_time', 'asc');
                break;
            case 'title':
                $articlesQuery->orderBy('title', 'asc');
                break;
            default: // latest
                $articlesQuery->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
        }

        $articles = $articlesQuery->paginate($perPage);
        $articles->appends($request->query());

        // Get category statistics
        $categoryStats = [
            'total_articles' => $category->articles()->count(),
            'total_views' => $category->articles()->sum('views'),
            'avg_reading_time' => round($category->articles()->avg('reading_time') ?? 0),
            'latest_article' => $category->articles()->orderBy('published_at', 'desc')->first(),
            'most_viewed' => $category->articles()->orderBy('views', 'desc')->first(),
        ];

        // Get popular articles in this category
        $popularInCategory = $category->articles()
            ->orderBy('views', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Get related categories (categories with similar article counts)
        $relatedCategories = Category::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('articles_count', 'desc')
            ->limit(6)
            ->get();

        $featuredArticles = $this->articleService->getFeaturedArticles(5);
        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(10);

        return view('categories.show', [
            'category' => $category,
            'articles' => $articles,
            'categoryStats' => $categoryStats,
            'popularInCategory' => $popularInCategory,
            'relatedCategories' => $relatedCategories,
            'featuredArticles' => $featuredArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'filters' => [
                'sort' => $sortBy,
                'reading_time' => $request->get('reading_time'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'per_page' => $perPage,
            ],
            'seo' => $this->seoService->forCategory($category),
        ]);
    }

    /**
     * Load more articles for a category (AJAX)
     */
    public function loadMore($slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $page = $request->get('page', 2);
        $perPage = 15;
        
        // Build articles query with same filters as show method
        $articlesQuery = \App\Models\Article::published()
            ->where('category_id', $category->id)
            ->with(['author', 'tags', 'category']);

        // Apply filters from request
        if ($request->has('date_from')) {
            $articlesQuery->whereDate('published_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $articlesQuery->whereDate('published_at', '<=', $request->date_to);
        }
        if ($request->has('reading_time')) {
            $readingTime = $request->reading_time;
            if ($readingTime === 'short') {
                $articlesQuery->where('reading_time', '<=', 5);
            } elseif ($readingTime === 'medium') {
                $articlesQuery->whereBetween('reading_time', [6, 15]);
            } elseif ($readingTime === 'long') {
                $articlesQuery->where('reading_time', '>', 15);
            }
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $articlesQuery->orderBy('views', 'desc')->orderBy('published_at', 'desc');
                break;
            case 'oldest':
                $articlesQuery->orderBy('published_at', 'asc');
                break;
            case 'reading_time':
                $articlesQuery->orderBy('reading_time', 'asc');
                break;
            case 'title':
                $articlesQuery->orderBy('title', 'asc');
                break;
            default:
                $articlesQuery->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
        }

        $articles = $articlesQuery->paginate($perPage, ['*'], 'page', $page);
        
        // Debug logging
        \Log::info('Load More Articles:', [
            'category' => $category->name,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $articles->total(),
            'current_page' => $articles->currentPage(),
            'last_page' => $articles->lastPage(),
            'has_more' => $articles->hasMorePages(),
            'count' => $articles->count(),
            'filters' => $request->only(['sort', 'reading_time', 'date_from', 'date_to'])
        ]);
        
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
            'debug' => [
                'total' => $articles->total(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'count' => $articles->count()
            ]
        ]);
    }
}

