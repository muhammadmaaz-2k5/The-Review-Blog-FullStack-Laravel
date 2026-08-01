<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\FeaturedVideo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FeaturedVideoSafeComposer
{
    /**
     * Safe composer that handles database connection failures gracefully.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        try {
            // Try to get from cache first to reduce database load
            $randomFeaturedVideo = Cache::remember('random_featured_video', 300, function () {
                try {
                    // Get a random active video from the database
                    return FeaturedVideo::where('is_active', true)
                        ->inRandomOrder()
                        ->first();
                } catch (\Exception $dbException) {
                    // Log the database error
                    Log::warning('FeaturedVideoSafeComposer database connection failed: ' . $dbException->getMessage());
                    return null;
                }
            });
            
            // If no video found in database or cache, use fallback
            if (!$randomFeaturedVideo) {
                $randomFeaturedVideo = $this->createFallbackVideo();
            }
            
            $view->with('randomFeaturedVideo', $randomFeaturedVideo);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::warning('FeaturedVideoSafeComposer failed to fetch video, using fallback: ' . $e->getMessage());
            
            // Fallback to static video to prevent site from breaking
            $randomFeaturedVideo = $this->createFallbackVideo();
            $view->with('randomFeaturedVideo', $randomFeaturedVideo);
        }
    }
    
    /**
     * Create a fallback video object when database is unavailable
     *
     * @return object
     */
    private function createFallbackVideo()
    {
        $fallback = new \stdClass();
        $fallback->id = 0;
        $fallback->title = "Welcome to Nazaara Circle";
        $fallback->youtube_url = "";
        $fallback->is_active = true;
        $fallback->created_at = now();
        
        return $fallback;
    }
}
