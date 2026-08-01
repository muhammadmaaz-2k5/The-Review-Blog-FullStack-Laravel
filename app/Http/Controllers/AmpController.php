<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\SeoService;
use Illuminate\Http\Response;

class AmpController extends Controller
{
    protected $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Display AMP version of an article
     */
    public function article($slug): Response
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        $seo = $this->seoService->forArticle($article);

        return response()->view('amp.article', [
            'article' => $article,
            'seo' => $seo,
        ])->header('Content-Type', 'text/html; charset=utf-8');
    }
}
