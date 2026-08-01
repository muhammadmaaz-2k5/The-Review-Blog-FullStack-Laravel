<?php

use Illuminate\Support\Facades\Route;
use App\Models\FeaturedVideo;

Route::get('/test-featured-video-render', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video-render', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video-render', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});