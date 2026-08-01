<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ThreadsService
{
    protected $pageId;
    protected $accessToken;
    protected $apiVersion;

    public function __construct()
    {
        $this->pageId = config('services.threads.page_id');
        $this->accessToken = config('services.threads.access_token');
        $this->apiVersion = config('services.threads.api_version', 'v18.0');
    }

    /**
     * Check if Threads integration is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->pageId) && !empty($this->accessToken);
    }

    /**
     * Post article to Threads
     */
    public function postArticle($article): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Threads integration is not configured. Please set up Threads credentials in admin settings.',
            ];
        }

        try {
            $url = route('articles.show', $article->slug);
            $text = $this->buildText($article, $url);

            // Create Threads post
            $response = Http::post(
                "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/threads",
                [
                    'media_type' => 'TEXT',
                    'text' => $text,
                    'access_token' => $this->accessToken,
                ]
            );

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Article posted to Threads successfully', [
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                    'thread_id' => $responseData['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Article posted to Threads successfully.',
                    'thread_id' => $responseData['id'] ?? null,
                ];
            } else {
                $error = $response->json();
                
                Log::error('Failed to post article to Threads', [
                    'article_id' => $article->id,
                    'error' => $error,
                ]);

                return [
                    'success' => false,
                    'message' => $error['error']['message'] ?? 'Failed to post to Threads.',
                    'error' => $error,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception while posting to Threads', [
                'article_id' => $article->id,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while posting to Threads: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build the text for Threads post
     */
    protected function buildText($article, $url): string
    {
        $text = "📝 {$article->title}\n\n";
        
        if ($article->excerpt) {
            $excerpt = substr(strip_tags($article->excerpt), 0, 400);
            $text .= "{$excerpt}\n\n";
        }
        
        $text .= "Read more: {$url}\n\n";
        
        // Add hashtags
        if ($article->category) {
            $text .= "#" . str_replace(' ', '', $article->category->name) . " ";
        }
        
        if ($article->tags->isNotEmpty()) {
            $tags = $article->tags->take(5)->pluck('name')->map(fn($tag) => '#' . str_replace(' ', '', $tag))->implode(' ');
            $text .= $tags;
        }
        
        return $text;
    }

    /**
     * Verify Threads access token
     */
    public function verifyToken(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Threads integration is not configured.',
            ];
        }

        try {
            $response = Http::get(
                "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}",
                [
                    'fields' => 'id,name',
                    'access_token' => $this->accessToken,
                ]
            );

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Threads token is valid.',
                    'page_info' => $response->json(),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid Threads access token.',
                    'error' => $response->json(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error verifying token: ' . $e->getMessage(),
            ];
        }
    }
}

