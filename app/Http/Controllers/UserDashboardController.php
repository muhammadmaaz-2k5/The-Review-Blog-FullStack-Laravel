<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\ReadingHistory;
use App\Models\ArticleLike;
use App\Models\AuthorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // User statistics
        $totalBookmarks = Bookmark::where('user_id', $user->id)->count();
        $totalComments = Comment::where('user_id', $user->id)->count();
        $totalLikes = ArticleLike::where('user_id', $user->id)->count();
        $totalReadingHistory = ReadingHistory::where('user_id', $user->id)->count();
        
        // Recent bookmarks
        $recentBookmarks = Bookmark::where('user_id', $user->id)
            ->with('article.category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent comments
        $recentComments = Comment::where('user_id', $user->id)
            ->with(['article', 'article.category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Reading history
        $recentReadingHistory = ReadingHistory::where('user_id', $user->id)
            ->with('article.category')
            ->orderBy('last_read_at', 'desc')
            ->limit(5)
            ->get();
        
        // Liked articles
        $likedArticles = ArticleLike::where('user_id', $user->id)
            ->with('article.category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Bookmarks this month
        $bookmarksThisMonth = Bookmark::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Comments this month
        $commentsThisMonth = Comment::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Check for existing author request
        $authorRequest = AuthorRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('user.dashboard', compact(
            'totalBookmarks',
            'totalComments',
            'totalLikes',
            'totalReadingHistory',
            'recentBookmarks',
            'recentComments',
            'recentReadingHistory',
            'likedArticles',
            'bookmarksThisMonth',
            'commentsThisMonth',
            'authorRequest'
        ));
    }

    /**
     * Submit an author request
     */
    public function requestAuthor(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is already an author
        if ($user->isAuthor()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You are already an author!');
        }
        
        // Check if there's a pending request
        $pendingRequest = AuthorRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if ($pendingRequest) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You already have a pending author request. Please wait for admin approval.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);
        
        // Create the author request
        AuthorRequest::create([
            'user_id' => $user->id,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);
        
        return redirect()->route('user.dashboard')
            ->with('success', 'Your author request has been submitted successfully! We will review it and get back to you soon.');
    }
}

