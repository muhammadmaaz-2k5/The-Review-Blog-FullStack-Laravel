<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Services\SeoService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $imageService;
    protected $seoService;

    public function __construct(ImageService $imageService, SeoService $seoService)
    {
        $this->imageService = $imageService;
        $this->seoService = $seoService;
    }

    /**
     * Display a public user profile
     */
    public function show($username)
    {
        $user = User::where('username', $username)
            ->orWhere('id', $username)
            ->firstOrFail();

        // Check if profile is public or user is viewing their own profile
        if (!$user->profile_public && (!Auth::check() || Auth::id() !== $user->id)) {
            abort(403, 'This profile is private.');
        }

        $user->loadCount([
            'articles' => function($query) {
                $query->where('status', 'published');
            },
            'followers',
            'following',
            'comments',
        ]);

        // Get user statistics
        $stats = [
            'articles' => $user->published_articles_count,
            'views' => $user->total_views,
            'likes' => $user->total_likes,
            'comments' => $user->comments_count,
            'followers' => $user->followers_count,
            'following' => $user->following_count,
        ];

        // Get recent articles
        $recentArticles = Article::where('author_id', $user->id)
            ->where('status', 'published')
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        // Get popular articles
        $popularArticles = Article::where('author_id', $user->id)
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Get badges
        $badges = $user->badges()->where('is_active', true)->get();

        // Check if current user follows this user
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->follows($user);
        }

        return view('profile.show', [
            'user' => $user,
            'stats' => $stats,
            'recentArticles' => $recentArticles,
            'popularArticles' => $popularArticles,
            'badges' => $badges,
            'isFollowing' => $isFollowing,
            'seo' => $this->seoService->forProfile($user),
        ]);
    }

    /**
     * Show the form for editing user profile
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'avatar' => 'nullable|url|max:500',
            'cover_image' => 'nullable|url|max:500',
            'profile_public' => 'nullable|boolean',
            'avatar_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'cover_image_file' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);

        // Handle avatar upload if it's a file
        if ($request->hasFile('avatar_file')) {
            // Delete old avatar if exists
            if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $user->avatar);
                $this->imageService->delete($oldPath);
            }
            $path = $this->imageService->convertToWebp($request->file('avatar_file'), 'avatars');
            if ($path) {
                $validated['avatar'] = $path;
            }
        }

        // Handle cover image upload if it's a file
        if ($request->hasFile('cover_image_file')) {
            // Delete old cover if exists
            if ($user->cover_image && str_starts_with($user->cover_image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $user->cover_image);
                $this->imageService->delete($oldPath);
            }
            $path = $this->imageService->convertToWebp($request->file('cover_image_file'), 'covers');
            if ($path) {
                $validated['cover_image'] = $path;
            }
        }

        $user->update($validated);

        // Record activity
        $user->recordActivity('profile_updated', 'Updated profile information');

        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show user's articles
     */
    public function articles($username, Request $request)
    {
        $user = User::where('username', $username)
            ->orWhere('id', $username)
            ->firstOrFail();

        if (!$user->profile_public && (!Auth::check() || Auth::id() !== $user->id)) {
            abort(403, 'This profile is private.');
        }

        $perPage = 15;
        $articles = Article::where('author_id', $user->id)
            ->where('status', 'published')
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return view('profile.articles', [
            'user' => $user,
            'articles' => $articles,
            'seo' => $this->seoService->forProfile($user),
        ]);
    }
}
