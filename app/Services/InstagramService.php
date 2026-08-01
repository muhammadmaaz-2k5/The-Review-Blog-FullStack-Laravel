<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class InstagramService
{
    protected $pageId;
    protected $accessToken;
    protected $apiVersion;
    protected $profileUrl;

    public function __construct()
    {
        $this->pageId = config('services.instagram.page_id');
        $this->accessToken = config('services.instagram.access_token');
        $this->apiVersion = config('services.instagram.api_version', 'v18.0');
        $this->profileUrl = config('services.instagram.profile_url', 'https://www.instagram.com/nazaaracircle');
    }

    /**
     * Check if Instagram integration is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->pageId) && !empty($this->accessToken);
    }

    /**
     * Post article to Instagram
     */
    public function postArticle($article): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Instagram integration is not configured. Please set up Instagram credentials in admin settings.',
            ];
        }

        try {
            $imageUrl = $this->getImageUrl($article);
            
            if (!$imageUrl) {
                return [
                    'success' => false,
                    'message' => 'Article must have a featured image to post to Instagram.',
                ];
            }

            // Step 1: Create media container
            $caption = $this->buildCaption($article);
            
            $mediaResponse = Http::post(
                "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/media",
                [
                    'image_url' => $imageUrl,
                    'caption' => $caption,
                    'access_token' => $this->accessToken,
                ]
            );

            if (!$mediaResponse->successful()) {
                $error = $mediaResponse->json();
                Log::error('Failed to create Instagram media container', [
                    'article_id' => $article->id,
                    'error' => $error,
                ]);

                return [
                    'success' => false,
                    'message' => $error['error']['message'] ?? 'Failed to create Instagram post.',
                    'error' => $error,
                ];
            }

            $mediaId = $mediaResponse->json()['id'];

            // Step 2: Publish the media
            $publishResponse = Http::post(
                "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/media_publish",
                [
                    'creation_id' => $mediaId,
                    'access_token' => $this->accessToken,
                ]
            );

            if ($publishResponse->successful()) {
                $responseData = $publishResponse->json();
                
                Log::info('Article posted to Instagram successfully', [
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                    'instagram_post_id' => $responseData['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Article posted to Instagram successfully.',
                    'post_id' => $responseData['id'] ?? null,
                ];
            } else {
                $error = $publishResponse->json();
                
                Log::error('Failed to publish Instagram post', [
                    'article_id' => $article->id,
                    'error' => $error,
                ]);

                return [
                    'success' => false,
                    'message' => $error['error']['message'] ?? 'Failed to publish Instagram post.',
                    'error' => $error,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception while posting to Instagram', [
                'article_id' => $article->id,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while posting to Instagram: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build the caption for Instagram post
     */
    protected function buildCaption($article): string
    {
        $url = route('articles.show', $article->slug);
        $caption = "📝 {$article->title}\n\n";
        
        if ($article->excerpt) {
            $excerpt = substr(strip_tags($article->excerpt), 0, 200);
            $caption .= "{$excerpt}\n\n";
        }
        
        $caption .= "Read more: {$url}\n\n";
        
        // Add hashtags
        if ($article->category) {
            $caption .= "#" . str_replace(' ', '', $article->category->name) . " ";
        }
        
        if ($article->tags->isNotEmpty()) {
            $tags = $article->tags->take(10)->pluck('name')->map(fn($tag) => '#' . str_replace(' ', '', $tag))->implode(' ');
            $caption .= $tags;
        }
        
        return $caption;
    }

    /**
     * Get image URL for the article
     */
    protected function getImageUrl($article): ?string
    {
        if (!$article->featured_image) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($article->featured_image, FILTER_VALIDATE_URL)) {
            return $article->featured_image;
        }

        // Otherwise, make it a full URL
        return url($article->featured_image);
    }

    /**
     * Verify Instagram access token
     */
    public function verifyToken(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Instagram integration is not configured.',
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
                    'message' => 'Instagram token is valid.',
                    'page_info' => $response->json(),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid Instagram access token.',
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

    /**
     * Check if Instagram profile is configured for follower count
     */
    public function isProfileConfigured(): bool
    {
        return !empty($this->profileUrl);
    }

    /**
     * Get Instagram follower count
     * Note: This requires an Instagram Business Account connected to a Facebook Page
     * and the access token needs appropriate permissions
     * Cached for 1 hour to avoid too many API calls
     */
    public function getFollowerCount(bool $forceRefresh = false): ?string
    {
        if (!$this->isConfigured() || !$this->pageId) {
            return null;
        }

        // Clear cache if force refresh is requested
        if ($forceRefresh) {
            Cache::forget('instagram_follower_count');
        }

        // Cache for 1 hour (3600 seconds)
        return Cache::remember('instagram_follower_count', 3600, function () {
            try {
                // Get follower count from Instagram Business Account
                // The pageId should be the Instagram Business Account ID
                $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}";
                $params = [
                    'fields' => 'followers_count',
                    'access_token' => $this->accessToken,
                ];

                Log::info('Fetching Instagram follower count', [
                    'page_id' => $this->pageId,
                ]);

                // Disable SSL verification for local development
                $verifySSL = config('app.env') !== 'local';

                $response = Http::timeout(10)
                    ->withOptions([
                        'verify' => $verifySSL,
                    ])
                    ->get($url, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['followers_count'])) {
                        $count = (int) $data['followers_count'];
                        $formatted = $this->formatFollowerCount($count);
                        
                        Log::info('Successfully fetched Instagram follower count', [
                            'count' => $count,
                            'formatted' => $formatted,
                        ]);
                        
                        return $formatted;
                    } else {
                        Log::warning('Instagram API response missing follower count', [
                            'response' => $data,
                        ]);
                    }
                } else {
                    $errorData = $response->json();
                    $statusCode = $response->status();
                    
                    Log::error('Failed to fetch Instagram follower count', [
                        'status' => $statusCode,
                        'error' => $errorData,
                        'page_id' => $this->pageId,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception while fetching Instagram follower count', [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return null;
        });
    }

    /**
     * Format follower count (e.g., 1500 -> 1.5K, 1500000 -> 1.5M)
     */
    protected function formatFollowerCount(int $count): string
    {
        if ($count >= 1000000) {
            return number_format($count / 1000000, 1) . 'M';
        } elseif ($count >= 1000) {
            return number_format($count / 1000, 1) . 'K';
        }
        return (string) $count;
    }

    /**
     * Get status message for API configuration
     */
    public function getStatusMessage(): string
    {
        if (!$this->isConfigured()) {
            return 'Instagram not configured';
        }
        return 'Ready';
    }

    /**
     * Get Instagram profile URL
     */
    public function getProfileUrl(): string
    {
        return $this->profileUrl;
    }
}

