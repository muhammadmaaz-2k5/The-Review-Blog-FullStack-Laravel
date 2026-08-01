<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Services\ArticleService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    protected $articleService;
    protected $seoService;

    public function __construct(ArticleService $articleService, SeoService $seoService)
    {
        $this->articleService = $articleService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of series
     */
    public function index(Request $request)
    {
        $query = Series::where('is_active', true)
            ->whereNotIn('slug', ['photo-gallery', 'celebrity-biography'])
            ->withCount(['articles' => function($query) {
                $query->published();
            }])
            ->with(['articles' => function($query) {
                $query->published()
                    ->orderBy('published_at', 'desc')
                    ->limit(1)
                    ->select('id', 'series_id', 'published_at', 'views');
            }, 'author']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'sort_order');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortOrder);
                break;
            case 'articles':
                $query->orderBy('articles_count', $sortOrder);
                break;
            case 'latest':
                $query->orderByRaw('(SELECT MAX(published_at) FROM articles WHERE series_id = article_series.id AND status = "published") ' . ($sortOrder === 'desc' ? 'DESC' : 'ASC'));
                break;
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('title', 'asc');
        }

        $series = $query->get();

        // Get featured series (series with most articles)
        $featuredSeries = Series::where('is_active', true)
            ->whereNotIn('slug', ['photo-gallery', 'celebrity-biography'])
            ->withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('articles_count', 'desc')
            ->limit(6)
            ->get();

        return view('series.index', [
            'series' => $series,
            'featuredSeries' => $featuredSeries,
            'search' => $request->get('search'),
            'sort' => $sortBy,
            'order' => $sortOrder,
            'seo' => $this->seoService->forSeriesIndex(),
        ]);
    }

    /**
     * Display articles in a specific series
     */
    public function show($slug, Request $request)
    {
        $series = Series::where('slug', $slug)
            ->where('is_active', true)
            ->with(['articles' => function($query) {
                $query->published()
                    ->with(['author', 'category', 'tags'])
                    ->orderBy('series_order', 'asc');
            }, 'author'])
            ->firstOrFail();

        // Get series statistics
        $seriesStats = [
            'total_articles' => $series->articles()->count(),
            'total_views' => $series->articles()->sum('views'),
            'avg_reading_time' => round($series->articles()->avg('reading_time') ?? 0),
            'latest_article' => $series->articles()->orderBy('published_at', 'desc')->first(),
            'most_viewed' => $series->articles()->orderBy('views', 'desc')->first(),
            'total_reading_time' => $series->articles()->sum('reading_time'),
        ];

        // Get popular articles in this series
        $popularInSeries = $series->articles()
            ->orderBy('views', 'desc')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Get related series (other active series)
        $relatedSeries = Series::where('is_active', true)
            ->where('id', '!=', $series->id)
            ->whereNotIn('slug', ['photo-gallery', 'celebrity-biography'])
            ->withCount(['articles' => function($query) {
                $query->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('articles_count', 'desc')
            ->limit(6)
            ->get();

        // Get featured articles and categories for sidebar
        $featuredArticles = $this->articleService->getFeaturedArticles(5);
        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(10);

        return view('series.show', [
            'series' => $series,
            'seriesStats' => $seriesStats,
            'popularInSeries' => $popularInSeries,
            'relatedSeries' => $relatedSeries,
            'featuredArticles' => $featuredArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'seo' => $this->seoService->forSeries($series),
        ]);
    }
}
