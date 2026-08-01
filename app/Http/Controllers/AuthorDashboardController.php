<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\ArticleLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorDashboardController extends Controller
{
    /**
     * Display the author dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Author's articles statistics
        $totalArticles = Article::where('author_id', $user->id)->count();
        $publishedArticles = Article::where('author_id', $user->id)
            ->where('status', 'published')
            ->count();
        $draftArticles = Article::where('author_id', $user->id)
            ->where('status', 'draft')
            ->count();
        $scheduledArticles = Article::where('author_id', $user->id)
            ->where('status', 'scheduled')
            ->count();
        
        // Total views on author's articles
        $totalViews = Article::where('author_id', $user->id)->sum('views') ?? 0;
        
        // Featured articles count
        $featuredArticles = Article::where('author_id', $user->id)
            ->where('is_featured', true)
            ->count();
        
        // Recent articles
        $recentArticles = Article::where('author_id', $user->id)
            ->with(['category', 'tags'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Articles with most views
        $topViewedArticles = Article::where('author_id', $user->id)
            ->where('views', '>', 0)
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();
        
        // Recent comments on author's articles
        $recentComments = Comment::whereHas('article', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['article', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Total comments on author's articles
        $totalComments = Comment::whereHas('article', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->count();
        
        // Total likes on author's articles
        $totalLikes = ArticleLike::whereHas('article', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->count();
        
        // Articles added this month
        $thisMonthArticles = Article::where('author_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Articles added this week
        $thisWeekArticles = Article::where('author_id', $user->id)
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();
        
        // Comments this month
        $thisMonthComments = Comment::whereHas('article', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('author.dashboard', compact(
            'totalArticles',
            'publishedArticles',
            'draftArticles',
            'scheduledArticles',
            'totalViews',
            'featuredArticles',
            'recentArticles',
            'topViewedArticles',
            'recentComments',
            'totalComments',
            'totalLikes',
            'thisMonthArticles',
            'thisWeekArticles',
            'thisMonthComments'
        ));
    }
}

