<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.',
            ], 400);
        }

        Auth::user()->follow($user);

        // Record activity
        Auth::user()->recordActivity('user_followed', "Started following {$user->name}", $user);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "You are now following {$user->name}.",
                'following' => true,
                'followers_count' => $user->fresh()->followers_count,
            ]);
        }

        return redirect()->back()->with('success', "You are now following {$user->name}.");
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        Auth::user()->unfollow($user);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "You unfollowed {$user->name}.",
                'following' => false,
                'followers_count' => $user->fresh()->followers_count,
            ]);
        }

        return redirect()->back()->with('success', "You unfollowed {$user->name}.");
    }

    /**
     * Toggle follow status
     */
    public function toggle(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.',
            ], 400);
        }

        $following = Auth::user()->follows($user);

        if ($following) {
            Auth::user()->unfollow($user);
            $message = "You unfollowed {$user->name}.";
            $action = 'unfollowed';
        } else {
            Auth::user()->follow($user);
            Auth::user()->recordActivity('user_followed', "Started following {$user->name}", $user);
            $message = "You are now following {$user->name}.";
            $action = 'followed';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'following' => !$following,
            'followers_count' => $user->fresh()->followers_count,
            'action' => $action,
        ]);
    }

    /**
     * Get followers list
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->withCount([
                'articles' => function($query) {
                    $query->where('status', 'published');
                },
                'followers',
            ])
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(20);

        return view('profile.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    /**
     * Get following list
     */
    public function following(User $user)
    {
        $following = $user->following()
            ->withCount([
                'articles' => function($query) {
                    $query->where('status', 'published');
                },
                'followers',
            ])
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(20);

        return view('profile.following', [
            'user' => $user,
            'following' => $following,
        ]);
    }
}
