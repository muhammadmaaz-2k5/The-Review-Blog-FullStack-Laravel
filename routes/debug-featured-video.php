<?php

use Illuminate\Support\Facades\Route;
use App\Models\FeaturedVideo;

Route::get('/debug-featured-video', function () {
    try {
        // Try to get video count
        $totalVideos = FeaturedVideo::count();
        
        // Try to get a random active video
        $randomFeaturedVideo = FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('debug-featured-video', compact('randomFeaturedVideo', 'totalVideos'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('debug-featured-video', [
            'randomFeaturedVideo' => null,
            'totalVideos' => 'Database Error: ' . $e->getMessage()
        ], 500);
    }
});