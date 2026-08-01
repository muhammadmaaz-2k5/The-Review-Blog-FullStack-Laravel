<?php

use Illuminate\Support\Facades\Route;
use App\Models\FeaturedVideo;

Route::get('/test-direct-component', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-direct-component', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-direct-component', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});