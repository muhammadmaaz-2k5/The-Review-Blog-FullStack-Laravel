<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookmarkController extends Controller
{
    // Middleware is applied in routes/web.php, no need to define it here

    /**
     * Display all bookmarks for the authenticated user
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $query = Bookmark::where('user_id', $user->id)
            ->with(['article' => function($query) {
                $query->with(['category', 'author', 'tags']);
            }])
            ->orderBy('created_at', 'desc');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('article', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $bookmarks = $query->paginate(15);

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark for an article
     */
    public function toggle(Request $request, Article $article)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to bookmark articles.',
                ], 401);
            }

            $bookmark = Bookmark::where('user_id', $user->id)
                ->where('article_id', $article->id)
                ->first();

            if ($bookmark) {
                // Remove bookmark
                $bookmark->delete();
                
                $isBookmarked = false;
                $message = 'Article removed from bookmarks.';
            } else {
                // Add bookmark
                Bookmark::create([
                    'user_id' => $user->id,
                    'article_id' => $article->id,
                    'notes' => $request->notes ?? null,
                ]);
                
                // Record activity (if method exists)
                if (method_exists($user, 'recordActivity')) {
                    try {
                        $user->recordActivity('article_bookmarked', "Bookmarked: {$article->title}", $article);
                    } catch (\Exception $e) {
                        // Activity recording failed, but bookmark was created - continue
                        Log::warning('Failed to record bookmark activity: ' . $e->getMessage());
                    }
                }
                
                $isBookmarked = true;
                $message = 'Article bookmarked successfully!';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'bookmarked' => $isBookmarked,
                    'bookmarks_count' => $article->bookmarks()->count(),
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Bookmark toggle error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'article_id' => $article->id ?? null,
                'article_slug' => $article->slug ?? null,
                'exception' => $e
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to bookmark article. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to bookmark article. Please try again.');
        }
    }

    /**
     * Store a new bookmark
     */
    public function store(Request $request, Article $article)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if already bookmarked
        $existingBookmark = Bookmark::where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->first();

        if ($existingBookmark) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article is already bookmarked.',
                    'bookmarked' => true,
                ], 409);
            }
            return redirect()->back()->with('error', 'Article is already bookmarked.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $bookmark = Bookmark::create([
            'user_id' => $user->id,
            'article_id' => $article->id,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Record activity
        $user->recordActivity('article_bookmarked', "Bookmarked: {$article->title}", $article);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article bookmarked successfully!',
                'bookmark' => $bookmark,
                'bookmarked' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Article bookmarked successfully!');
    }

    /**
     * Update bookmark notes
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        // Ensure user owns the bookmark
        if ($bookmark->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $bookmark->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bookmark updated successfully.',
                'bookmark' => $bookmark->fresh(),
            ]);
        }

        return redirect()->back()->with('success', 'Bookmark updated successfully.');
    }

    /**
     * Remove a bookmark
     */
    public function destroy(Bookmark $bookmark)
    {
        // Ensure user owns the bookmark
        if ($bookmark->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $articleTitle = $bookmark->article->title;
        $bookmark->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bookmark removed successfully.',
                'bookmarked' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Bookmark removed successfully.');
    }

    /**
     * Remove bookmark by article
     */
    public function removeByArticle(Article $article)
    {
        $bookmark = Bookmark::where('user_id', Auth::id())
            ->where('article_id', $article->id)
            ->first();

        if (!$bookmark) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article is not bookmarked.',
                ], 404);
            }
            return redirect()->back()->with('error', 'Article is not bookmarked.');
        }

        $bookmark->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bookmark removed successfully.',
                'bookmarked' => false,
                'bookmarks_count' => $article->bookmarks()->count(),
            ]);
        }

        return redirect()->back()->with('success', 'Bookmark removed successfully.');
    }
}
