<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\FeaturedVideo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FeaturedVideoComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        try {
            // Try to get from cache first to reduce database load
            $randomFeaturedVideo = Cache::remember('random_featured_video', 300, function () {
                // Check if we can connect to the database
                try {
                    // Get active videos with better performance
                    $activeVideos = FeaturedVideo::where('is_active', true)
                        ->select(['id', 'title', 'youtube_url', 'is_active'])
                        ->limit(20) // Limit to prevent memory issues
                        ->get();
                    
                    // Return random video or null if none exist
                    return $activeVideos->isNotEmpty() ? $activeVideos->random() : null;
                } catch (\Exception $dbException) {
                    // Log the database error
                    Log::warning('FeaturedVideoComposer database connection failed: ' . $dbException->getMessage());
                    return null;
                }
            });
            
            $view->with('randomFeaturedVideo', $randomFeaturedVideo);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::warning('FeaturedVideoComposer failed to fetch video: ' . $e->getMessage());
            
            // Fallback to null to prevent errors
            $view->with('randomFeaturedVideo', null);
        }
    }
}