<?php

namespace App\Traits;

use App\Services\SitemapService;

trait ClearsSitemapCache
{
    /**
     * Boot the trait.
     */
    public static function bootClearsSitemapCache()
    {
        static::saved(function ($model) {
            if (app()->bound(SitemapService::class)) {
                app(SitemapService::class)->clearCache();
            }
        });

        static::deleted(function ($model) {
            if (app()->bound(SitemapService::class)) {
                app(SitemapService::class)->clearCache();
            }
        });
    }
}
