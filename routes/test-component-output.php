<?php

use Illuminate\Support\Facades\Route;
use App\Models\FeaturedVideo;

Route::get('/test-component-output', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-component-output', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-component-output', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});