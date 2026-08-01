<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\SeoAuditService;
use Illuminate\Http\Request;

class SeoAuditController extends Controller
{
    protected $auditService;

    public function __construct(SeoAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Show SEO audit for an article
     */
    public function show(Article $article)
    {
        $audit = $this->auditService->auditArticle($article);
        
        // Get additional analysis
        $keywordDensity = [];
        if (!empty($article->meta_keywords)) {
            $keywords = explode(',', $article->meta_keywords);
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if (!empty($keyword)) {
                    $keywordDensity[$keyword] = $this->auditService->calculateKeywordDensity(
                        $article->content,
                        $keyword
                    );
                }
            }
        }
        
        $extractedKeywords = $this->auditService->extractKeywords($article->content ?? '', 10);
        $readability = $this->auditService->checkReadability($article->content ?? '');
        
        return view('admin.articles.seo-audit', compact('article', 'audit', 'keywordDensity', 'extractedKeywords', 'readability'));
    }

    /**
     * Get SEO audit data as JSON
     */
    public function getAudit(Article $article)
    {
        $audit = $this->auditService->auditArticle($article);
        
        return response()->json($audit);
    }
}

