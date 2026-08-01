<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwitterService
{
    protected $bearerToken;
    protected $apiVersion;

    public function __construct()
    {
        $this->bearerToken = config('services.twitter.bearer_token');
        $this->apiVersion = config('services.twitter.api_version', '2');
    }

    /**
     * Check if Twitter integration is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->bearerToken);
    }

    /**
     * Post article to Twitter/X
     */
    public function postArticle($article): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Twitter integration is not configured. Please set up Twitter credentials in admin settings.',
            ];
        }

        try {
            $url = route('articles.show', $article->slug);
            $message = $this->buildMessage($article, $url);

            // Twitter API v2 endpoint
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Content-Type' => 'application/json',
            ])->post(
                "https://api.twitter.com/{$this->apiVersion}/tweets",
                [
                    'text' => $message,
                ]
            );

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Article posted to Twitter successfully', [
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                    'tweet_id' => $responseData['data']['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Article posted to Twitter successfully.',
                    'tweet_id' => $responseData['data']['id'] ?? null,
                ];
            } else {
                $error = $response->json();
                
                Log::error('Failed to post article to Twitter', [
                    'article_id' => $article->id,
                    'error' => $error,
                ]);

                return [
                    'success' => false,
                    'message' => $error['detail'] ?? $error['title'] ?? 'Failed to post to Twitter.',
                    'error' => $error,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception while posting to Twitter', [
                'article_id' => $article->id,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while posting to Twitter: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build the message for Twitter post (max 280 characters)
     */
    protected function buildMessage($article, $url): string
    {
        $title = $article->title;
        $urlLength = 23; // Twitter shortens URLs to 23 characters
        
        // Calculate available space for title and text
        $maxTitleLength = 280 - $urlLength - 10; // Reserve space for URL and spacing
        
        if (strlen($title) > $maxTitleLength) {
            $title = substr($title, 0, $maxTitleLength - 3) . '...';
        }
        
        $message = "📝 {$title}\n\n{$url}";
        
        // Add hashtags if space allows
        $hashtags = [];
        if ($article->category) {
            $hashtags[] = '#' . str_replace(' ', '', $article->category->name);
        }
        if ($article->tags->isNotEmpty()) {
            $tags = $article->tags->take(2)->pluck('name')->map(fn($tag) => '#' . str_replace(' ', '', $tag))->toArray();
            $hashtags = array_merge($hashtags, $tags);
        }
        
        if (!empty($hashtags)) {
            $hashtagString = ' ' . implode(' ', $hashtags);
            if (strlen($message . $hashtagString) <= 280) {
                $message .= $hashtagString;
            }
        }
        
        return $message;
    }

    /**
     * Verify Twitter access token
     */
    public function verifyToken(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Twitter integration is not configured.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->bearerToken,
            ])->get("https://api.twitter.com/{$this->apiVersion}/users/me");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Twitter token is valid.',
                    'user_info' => $response->json(),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid Twitter bearer token.',
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

