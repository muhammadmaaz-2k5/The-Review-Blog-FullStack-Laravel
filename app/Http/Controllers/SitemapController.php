<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;

class SitemapController extends Controller
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Generate sitemap index (main /sitemap.xml)
     */
    public function sitemapIndex()
    {
        $sitemaps = $this->sitemapService->getSitemapIndex();
        
        return response()->view('sitemap.index-file', [
            'sitemaps' => $sitemaps,
        ])->header('Content-Type', 'application/xml');
    }





    /**
     * Generate sitemap by type
     */
    public function byType(string $type)
    {
        $urls = $this->sitemapService->getSitemapByType($type);
        
        if (empty($urls)) {
            abort(404);
        }

        $hasImages = $type === 'articles';
        $hasVideos = $type === 'articles';

        return response()->view('sitemap.index', [
            'urls' => $urls,
            'hasImages' => $hasImages,
            'hasVideos' => $hasVideos,
        ])->header('Content-Type', 'application/xml');
    }

    /**
     * Generate home page sitemap (includes featured and trending content)
     */
    public function home()
    {
        return $this->byType('home');
    }

    /**
     * Generate pages sitemap (static pages like home, about, contact, etc.)
     */
    public function pages()
    {
        return $this->byType('pages');
    }

    /**
     * Generate articles sitemap
     */
    public function articles()
    {
        return $this->byType('articles');
    }

    /**
     * Generate games sitemap
     */
    public function games()
    {
        return $this->byType('games');
    }

    /**
     * Generate applications sitemap
     */
    public function applications()
    {
        return $this->byType('applications');
    }

    /**
     * Generate tools sitemap
     */
    public function tools()
    {
        return $this->byType('tools');
    }

    /**
     * Generate categories sitemap
     */
    public function categories()
    {
        return $this->byType('categories');
    }

    /**
     * Generate tags sitemap
     */
    public function tags()
    {
        return $this->byType('tags');
    }

    /**
     * Generate series sitemap
     */
    public function series()
    {
        return $this->byType('series');
    }

    /**
     * Generate profiles sitemap
     */
    public function profiles()
    {
        return $this->byType('profiles');
    }
}

