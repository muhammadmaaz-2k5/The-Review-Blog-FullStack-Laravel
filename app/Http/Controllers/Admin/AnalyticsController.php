<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Models\Article;
use App\Models\AnalyticsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        // Date range filter (default: last 30 days)
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        // Real-time stats (last 30 minutes)
        $realTime = $this->analyticsService->getRealTimeStats(30);

        // Overall engagement metrics
        $engagement = $this->analyticsService->getEngagementMetrics($startDateCarbon, $endDateCarbon);

        // Traffic sources
        $trafficSources = $this->analyticsService->getTrafficSources($startDateCarbon, $endDateCarbon, 10);

        // Geographic data
        $geographic = $this->analyticsService->getGeographicData($startDateCarbon, $endDateCarbon, 20);

        // Device analytics
        $deviceAnalytics = $this->analyticsService->getDeviceAnalytics($startDateCarbon, $endDateCarbon);

        // Popular articles - Get article IDs with view counts first, then load full articles
        $popularArticleIds = DB::table('articles')
            ->select('articles.id', DB::raw('COUNT(analytics_views.id) as views_count'))
            ->leftJoin('analytics_views', function($join) use ($startDateCarbon, $endDateCarbon) {
                $join->on('analytics_views.viewable_id', '=', 'articles.id')
                     ->where('analytics_views.viewable_type', '=', Article::class)
                     ->whereBetween('analytics_views.viewed_at', [$startDateCarbon, $endDateCarbon]);
            })
            ->where('articles.status', 'published')
            ->whereNull('articles.deleted_at')
            ->groupBy('articles.id')
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->pluck('id')
            ->toArray();

        // Load full article models with relationships
        $popularArticles = Article::with(['category', 'author'])
            ->whereIn('id', $popularArticleIds)
            ->get()
            ->map(function($article) use ($startDateCarbon, $endDateCarbon) {
                // Add views_count to each article
                $article->views_count = AnalyticsView::where('viewable_id', $article->id)
                    ->where('viewable_type', Article::class)
                    ->whereBetween('viewed_at', [$startDateCarbon, $endDateCarbon])
                    ->count();
                return $article;
            })
            ->sortByDesc('views_count')
            ->values();

        // Views over time (for chart)
        $viewsOverTime = AnalyticsView::whereBetween('viewed_at', [$startDateCarbon, $endDateCarbon])
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('COUNT(*) as views'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'views' => (int) $item->views
                ];
            });

        // Top pages
        $topPages = AnalyticsView::whereBetween('viewed_at', [$startDateCarbon, $endDateCarbon])
            ->select('page_path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT session_id) as unique_views'))
            ->groupBy('page_path')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        // User growth (if tracking users)
        $userGrowth = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDateCarbon, $endDateCarbon])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.analytics.index', compact(
            'realTime',
            'engagement',
            'trafficSources',
            'geographic',
            'deviceAnalytics',
            'popularArticles',
            'viewsOverTime',
            'topPages',
            'userGrowth',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get real-time analytics (AJAX endpoint)
     */
    public function realTime(Request $request)
    {
        $minutes = $request->get('minutes', 30);
        $stats = $this->analyticsService->getRealTimeStats($minutes);
        
        return response()->json($stats);
    }

    /**
     * Get article performance
     */
    public function articlePerformance(Request $request, Article $article)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;
        
        $performance = $this->analyticsService->getArticlePerformance($article->id, $startDate, $endDate);
        
        return view('admin.analytics.article-performance', compact('article', 'performance', 'startDate', 'endDate'));
    }

    /**
     * Export analytics report
     */
    public function export(Request $request)
    {
        // This will be implemented with PDF/Excel export
        // For now, return JSON
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->get('format', 'json'); // json, pdf, excel

        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        $data = [
            'engagement' => $this->analyticsService->getEngagementMetrics($startDateCarbon, $endDateCarbon),
            'traffic_sources' => $this->analyticsService->getTrafficSources($startDateCarbon, $endDateCarbon),
            'geographic' => $this->analyticsService->getGeographicData($startDateCarbon, $endDateCarbon),
            'devices' => $this->analyticsService->getDeviceAnalytics($startDateCarbon, $endDateCarbon),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        // PDF/Excel export will be added later
        return response()->json(['message' => 'Export format not yet implemented']);
    }
}
