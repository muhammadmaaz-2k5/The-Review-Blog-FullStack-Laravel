<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class FeaturedVideoFallbackComposer
{
    /**
     * Fallback composer that provides a default featured video without database queries.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        try {
            // Try to get from cache first (which might have been set by the main composer)
            $randomFeaturedVideo = cache()->get('random_featured_video');
            
            if (!$randomFeaturedVideo) {
                // If no cached video and database is down, provide a fallback
                // This prevents the entire site from breaking due to database issues
                Log::info('Using fallback featured video composer due to database issues');
            }
            
            $view->with('randomFeaturedVideo', $randomFeaturedVideo);
        } catch (\Exception $e) {
            // Ultimate fallback - just provide null
            Log::error('FeaturedVideoFallbackComposer failed: ' . $e->getMessage());
            $view->with('randomFeaturedVideo', null);
        }
    }
}