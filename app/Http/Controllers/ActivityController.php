<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Get user activity feed
     */
    public function index(Request $request)
    {
        $user = $request->user ?? Auth::user();

        if (!$user) {
            abort(404);
        }

        // Check if profile is public
        if (!$user->profile_public && (!Auth::check() || Auth::id() !== $user->id)) {
            abort(403, 'This profile is private.');
        }

        $perPage = 20;
        $activities = UserActivity::where('user_id', $user->id)
            ->with(['subject', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'activities' => $activities,
            ]);
        }

        return view('profile.activity', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }

    /**
     * Get activity feed for followed users (timeline)
     */
    public function timeline(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Get activities from users the current user follows
        $followingIds = $user->following()->pluck('users.id')->toArray();
        $followingIds[] = $user->id; // Include own activities

        $perPage = 20;
        $activities = UserActivity::whereIn('user_id', $followingIds)
            ->with(['subject', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'activities' => $activities,
            ]);
        }

        return view('profile.timeline', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }
}
