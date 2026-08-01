<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\User;
use App\Models\NewsletterSubscription;
use App\Models\ContactMessage;
use App\Models\AuthorRequest;
use App\Models\AnalyticsView;
use App\Models\Career;

use App\Services\AnalyticsService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalArticles = Article::count();
            $totalCategories = Category::count();
            $totalTags = Tag::count();
            $totalComments = Comment::count();
            $totalUsers = User::count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Core stats failed - ' . $e->getMessage());
            $totalArticles = 0;
            $totalCategories = 0;
            $totalTags = 0;
            $totalComments = 0;
            $totalUsers = 0;
        }

        try {
            $totalCareers = Career::count();
            $activeCareers = Career::where('is_active', true)->count();
            $featuredCareers = Career::where('is_featured', true)->count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Career stats failed - ' . $e->getMessage());
            $totalCareers = 0;
            $activeCareers = 0;
            $featuredCareers = 0;
        }

        try {
            $publishedArticles = Article::where('status', 'published')->count();
            $draftArticles = Article::where('status', 'draft')->count();
            $scheduledArticles = Article::where('status', 'scheduled')->count();
            $totalViews = Article::sum('views') ?? 0;
            $featuredArticles = Article::where('is_featured', true)->count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Article stats failed - ' . $e->getMessage());
            $publishedArticles = 0;
            $draftArticles = 0;
            $scheduledArticles = 0;
            $totalViews = 0;
            $featuredArticles = 0;
        }

        try {
            $recentArticles = Article::with(['category', 'author'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Recent articles failed - ' . $e->getMessage());
            $recentArticles = collect();
        }

        try {
            $articlesByCategory = Category::withCount('articles')
                ->having('articles_count', '>', 0)
                ->orderBy('articles_count', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Categories by count failed - ' . $e->getMessage());
            $articlesByCategory = collect();
        }

        try {
            $recentComments = Comment::with(['article', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Recent comments failed - ' . $e->getMessage());
            $recentComments = collect();
        }

        try {
            $topViewedArticles = Article::where('views', '>', 0)
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Top articles failed - ' . $e->getMessage());
            $topViewedArticles = collect();
        }

        try {
            $thisMonthArticles = Article::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $thisWeekArticles = Article::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count();
            $thisMonthComments = Comment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Time stats failed - ' . $e->getMessage());
            $thisMonthArticles = 0;
            $thisWeekArticles = 0;
            $thisMonthComments = 0;
        }

        try {
            $totalSubscriptions = NewsletterSubscription::where('is_active', true)->count();
            $newSubscriptionsThisMonth = NewsletterSubscription::where('is_active', true)
                ->whereMonth('subscribed_at', now()->month)
                ->whereYear('subscribed_at', now()->year)
                ->count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Newsletter stats failed - ' . $e->getMessage());
            $totalSubscriptions = 0;
            $newSubscriptionsThisMonth = 0;
        }

        try {
            $unreadMessages = ContactMessage::where('status', 'unread')->count();
            $totalMessages = ContactMessage::count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Contact stats failed - ' . $e->getMessage());
            $unreadMessages = 0;
            $totalMessages = 0;
        }

        try {
            $totalAuthors = User::where(function ($query) {
                $query->where('is_author', true)
                      ->orWhere('role', 'author')
                      ->orWhere('role', 'admin');
            })->count();
            $pendingAuthorRequests = AuthorRequest::where('status', 'pending')->count();
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Author stats failed - ' . $e->getMessage());
            $totalAuthors = 0;
            $pendingAuthorRequests = 0;
        }



        try {
            $analyticsService = app(AnalyticsService::class);
            $yesterday = Carbon::now()->subDay();
            $today = Carbon::now();

            $quickAnalytics = [
                'today_views' => AnalyticsView::whereBetween('viewed_at', [$yesterday, $today])->count(),
                'today_unique' => AnalyticsView::whereBetween('viewed_at', [$yesterday, $today])
                    ->distinct('session_id')->count('session_id'),
                'realtime' => $analyticsService->getRealTimeStats(30),
            ];
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: Analytics failed - ' . $e->getMessage());
            $quickAnalytics = [
                'today_views' => 0,
                'today_unique' => 0,
                'realtime' => ['active_users' => 0, 'page_views' => 0],
            ];
        }

        try {
            $rendered = view('admin.dashboard', compact(
                'totalArticles',
                'totalCategories',
                'totalTags',
                'totalComments',
                'totalUsers',
                'publishedArticles',
                'draftArticles',
                'scheduledArticles',
                'totalViews',
                'featuredArticles',
                'recentArticles',
                'articlesByCategory',
                'recentComments',
                'topViewedArticles',
                'thisMonthArticles',
                'thisWeekArticles',
                'thisMonthComments',
                'totalSubscriptions',
                'newSubscriptionsThisMonth',
                'unreadMessages',
                'totalMessages',
                'totalAuthors',
                'pendingAuthorRequests',
                'quickAnalytics',
                'totalCareers',
                'activeCareers',
                'featuredCareers'
            ))->render();
            return response($rendered);
        } catch (\Exception $e) {
            Log::error('Admin Dashboard: View rendering failed - ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response('Dashboard error: ' . $e->getMessage(), 500);
        }
    }
}
