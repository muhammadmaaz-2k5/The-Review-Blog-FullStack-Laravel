<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Ad extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'placement',
        'type',
        'ad_code',
        'is_active',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static array $placementOptions = [
        'home_after_editors_picks' => 'Home - After Editor\'s Picks',
        'home_after_reviews' => 'Home - After Latest Reviews',
        'home_after_games' => 'Home - After Latest Games',
        'home_after_apps' => 'Home - After Latest Apps',
        'home_after_tools' => 'Home - After Latest Tools',
        'home_after_dramas' => 'Home - After Korean Dramas',
        'home_download_timer' => 'Home - Download Timer',
        'article_banner_top' => 'Article - Top Banner (728x90)',
        'article_in_content' => 'Article - In Content (AdSense)',
        'article_native' => 'Article - Native Banner',
        'article_sidebar_smartlink' => 'Article - Sidebar Smartlink',
        'article_before_comments' => 'Article - Before Comments (AdSense)',
        'article_before_comments_banner' => 'Article - Before Comments Banner (300x250)',
        'articles_top_banner' => 'Articles List - Top Banner (728x90)',
        'articles_native' => 'Articles List - Native Banner',
        'articles_sidebar' => 'Articles List - Sidebar (300x250)',
        'articles_sidebar_2' => 'Articles List - Sidebar 2 (160x300)',
        'articles_smartlink' => 'Articles List - Smartlink Button',
        'detail_popunder' => 'Game/App/Tool Detail - Popunder',
        'detail_native' => 'Game/App/Tool Detail - Native Banner',
        'detail_smartlink' => 'Game/App/Tool Detail - Smartlink Button',
        'detail_sidebar_promo' => 'Game/App/Tool Detail - Sidebar Promo',
        'detail_sidebar_adsense' => 'Game/App/Tool Detail - Sidebar AdSense',
        'page_social_bar' => 'All Pages - Social Bar (Footer)',
        'global_popunder' => 'All Pages - Popunder (Head)',
    ];

    public static array $typeOptions = [
        'adsterra_popunder' => 'Adsterra - Popunder',
        'adsterra_social_bar' => 'Adsterra - Social Bar',
        'adsterra_native_banner' => 'Adsterra - Native Banner',
        'adsterra_banner' => 'Adsterra - Banner (atOptions)',
        'adsterra_smartlink' => 'Adsterra - Smartlink',
        'adsense' => 'Google AdSense',
        'custom' => 'Custom HTML',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('ads.all.active');
            Cache::forget('ads.placements');
        });

        static::deleted(function () {
            Cache::forget('ads.all.active');
            Cache::forget('ads.placements');
        });
    }

    public static function getForPlacement(string $placement): ?self
    {
        try {
            $ads = self::getCachedActiveAds();
            return $ads->where('placement', $placement)->sortBy('sort_order')->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function getCachedActiveAds()
    {
        return Cache::remember('ads.all.active', now()->addHour(), function () {
            try {
                return self::where('is_active', true)->orderBy('sort_order')->get();
            } catch (\Throwable $e) {
                return collect();
            }
        });
    }

    public static function hasActiveAd(string $placement): bool
    {
        return self::getForPlacement($placement) !== null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }
}
