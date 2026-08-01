<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FeaturedVideoController extends Controller
{
    /**
     * Display a listing of featured videos.
     */
    public function index()
    {
        $videos = FeaturedVideo::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.featured-videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new featured video.
     */
    public function create()
    {
        return view('admin.featured-videos.create');
    }

    /**
     * Store a newly created featured video in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'youtube_url' => 'required|url',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        FeaturedVideo::create($validated);

        // Clear the cache when a new video is added
        Cache::forget('random_featured_video');

        return redirect()->route('admin.featured-videos.index')
            ->with('success', 'Video added successfully.');
    }

    /**
     * Show the form for editing the specified featured video.
     */
    public function edit(FeaturedVideo $featuredVideo)
    {
        return view('admin.featured-videos.edit', compact('featuredVideo'));
    }

    /**
     * Update the specified featured video in storage.
     */
    public function update(Request $request, FeaturedVideo $featuredVideo)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'youtube_url' => 'required|url',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $featuredVideo->update($validated);

        // Clear the cache when a video is updated
        Cache::forget('random_featured_video');

        return redirect()->route('admin.featured-videos.index')
            ->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified featured video from storage.
     */
    public function destroy(FeaturedVideo $featuredVideo)
    {
        $featuredVideo->delete();

        // Clear the cache when a video is deleted
        Cache::forget('random_featured_video');

        return redirect()->route('admin.featured-videos.index')
            ->with('success', 'Video deleted successfully.');
    }

    /**
     * Toggle the active status of a video.
     */
    public function toggleStatus(FeaturedVideo $featuredVideo)
    {
        $featuredVideo->update([
            'is_active' => !$featuredVideo->is_active
        ]);

        // Clear the cache when a video status is toggled
        Cache::forget('random_featured_video');

        return back()->with('success', 'Status updated successfully.');
    }
}
