<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FacebookService
{
    protected $pageId;
    protected $accessToken;
    protected $apiVersion;
    protected $profileId;

    public function __construct()
    {
        $this->pageId = config('services.facebook.page_id');
        $this->accessToken = config('services.facebook.page_access_token');
        $this->apiVersion = config('services.facebook.api_version', 'v18.0');
        $this->profileId = config('services.facebook.profile_id');
    }

    /**
     * Check if Facebook integration is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->pageId) && !empty($this->accessToken);
    }

    /**
     * Post article to Facebook page
     */
    public function postArticle($article): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Facebook integration is not configured. Please set up Facebook credentials in admin settings.',
            ];
        }

        try {
            $url = route('articles.show', $article->slug);
            $message = $this->buildMessage($article);

            // Build the post data
            // Note: We don't include 'picture', 'name', 'thumbnail', or 'description' parameters
            // Facebook will automatically fetch these from the page's Open Graph meta tags
            $postData = [
                'message' => $message,
                'link' => $url,
            ];

            // Post to Facebook page
            $response = Http::post(
                "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/feed",
                array_merge($postData, [
                    'access_token' => $this->accessToken,
                ])
            );

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Article posted to Facebook successfully', [
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                    'facebook_post_id' => $responseData['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Article posted to Facebook successfully.',
                    'post_id' => $responseData['id'] ?? null,
                ];
            } else {
                $error = $response->json();
                $errorMessage = $error['error']['message'] ?? 'Failed to post to Facebook.';
                $errorCode = $error['error']['code'] ?? null;
                
                // Provide more helpful error messages for common issues
                if ($errorCode == 200) {
                    if (str_contains($errorMessage, 'pages_read_engagement') || str_contains($errorMessage, 'Object does not exist')) {
                        $errorMessage = 'Missing "pages_read_engagement" permission or cannot access page. ' .
                            'This permission may require Facebook App Review. ' .
                            'SOLUTION: Go to https://developers.facebook.com/tools/explorer/, select your PAGE (not profile), ' .
                            'and generate a Page Access Token with "pages_manage_posts" permission. ' .
                            'Even if pages_read_engagement requires review, pages_manage_posts alone should work for posting.';
                    } else {
                        $errorMessage = 'Facebook access token is missing required permissions. ' .
                            'Please ensure your Page Access Token has "pages_manage_posts" permission at minimum. ' .
                            'Get a new token at https://developers.facebook.com/tools/explorer/';
                    }
                } elseif (str_contains($errorMessage, 'permission') || str_contains($errorMessage, 'Permission')) {
                    $errorMessage = 'Facebook access token permissions issue: ' . $errorMessage . 
                        ' Please ensure you are using a PAGE ACCESS TOKEN (not user token) with "pages_manage_posts" permission. ' .
                        'Get a new token at https://developers.facebook.com/tools/explorer/';
                } elseif (str_contains($errorMessage, 'Object does not exist') || str_contains($errorMessage, 'cannot be loaded')) {
                    $errorMessage = 'Cannot access Facebook Page. ' .
                        'Please verify: 1) Your Page ID is correct, 2) Your token is a Page Access Token (not User Token), ' .
                        '3) The token has "pages_manage_posts" permission. ' .
                        'Get a new token at https://developers.facebook.com/tools/explorer/';
                }
                
                Log::error('Failed to post article to Facebook', [
                    'article_id' => $article->id,
                    'error_code' => $errorCode,
                    'error' => $error,
                ]);

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $error,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception while posting to Facebook', [
                'article_id' => $article->id,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while posting to Facebook: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build the message for Facebook post
     */
    protected function buildMessage($article): string
    {
        $message = "📝 {$article->title}\n\n";
        
        if ($article->excerpt) {
            $message .= $article->excerpt . "\n\n";
        }
        
        $message .= "Read more: " . route('articles.show', $article->slug);
        
        // Add category if available
        if ($article->category) {
            $message .= "\n\n#{$article->category->name}";
        }
        
        // Add tags if available
        if ($article->tags->isNotEmpty()) {
            $tags = $article->tags->take(3)->pluck('name')->map(fn($tag) => "#{$tag}")->implode(' ');
            if ($tags) {
                $message .= " {$tags}";
            }
        }
        
        return $message;
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
     * Verify Facebook access token and check permissions
     */
    public function verifyToken(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Facebook integration is not configured.',
            ];
        }

        try {
            // First, try to get debug token info to see what permissions are granted
            // This doesn't require special permissions
            $debugResponse = Http::get(
                "https://graph.facebook.com/{$this->apiVersion}/debug_token",
                [
                    'input_token' => $this->accessToken,
                    'access_token' => $this->accessToken,
                ]
            );
            
            $debugInfo = null;
            $scopes = [];
            $tokenType = null;
            $isPageToken = false;
            
            if ($debugResponse->successful()) {
                $debugData = $debugResponse->json();
                if (isset($debugData['data'])) {
                    $debugInfo = $debugData['data'];
                    $scopes = $debugData['data']['scopes'] ?? [];
                    $tokenType = $debugData['data']['type'] ?? 'unknown';
                    $isPageToken = ($tokenType === 'PAGE' || isset($debugData['data']['profile_id']));
                }
            }
            
            // Try to get page info (this requires pages_read_engagement)
            $pageInfo = null;
            $pageInfoError = null;
            $response = Http::get(
                "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}",
                [
                    'access_token' => $this->accessToken,
                    'fields' => 'id,name',
                ]
            );

            if ($response->successful()) {
                $pageInfo = $response->json();
            } else {
                $error = $response->json();
                $pageInfoError = $error['error']['message'] ?? 'Unknown error';
            }
            
            // Check if we have the required permissions
            // CRITICAL: pages_manage_posts is REQUIRED for posting
            // pages_read_engagement is nice to have but not strictly required
            $hasPagesReadEngagement = in_array('pages_read_engagement', $scopes) || 
                                     in_array('pages_show_list', $scopes) ||
                                     ($pageInfo !== null); // If we can read page info, we have the permission
            $hasPagesManagePosts = in_array('pages_manage_posts', $scopes);
            
            // For posting, we MUST have pages_manage_posts
            // pages_read_engagement is optional (may require app review)
            $hasRequiredPermissions = $hasPagesManagePosts; // Only require pages_manage_posts for posting
            
            // Build message based on what we found
            if (!$isPageToken && $tokenType !== 'PAGE') {
                $message = '❌ ERROR: This appears to be a User Access Token, not a Page Access Token. You MUST generate a Page Access Token.';
            } elseif (empty($scopes) && $pageInfo === null) {
                $message = '⚠️ WARNING: Could not determine token permissions. The token may not have pages_manage_posts permission. Please verify your token is a valid Page Access Token with pages_manage_posts permission.';
            } elseif (!$hasPagesManagePosts) {
                $message = '❌ ERROR: Token is MISSING the critical "pages_manage_posts" permission. Current scopes: ' . implode(', ', $scopes ?: ['none']) . '. You MUST add pages_manage_posts permission to post articles.';
            } elseif (!$hasPagesReadEngagement) {
                $message = '✅ Token has "pages_manage_posts" (can post), but missing "pages_read_engagement" (may require app review). Posting should work, but some features may be limited.';
            } else {
                $message = '✅ Facebook token is valid and has all required permissions.';
            }
            
            return [
                'success' => $hasPagesManagePosts && $isPageToken, // Only require pages_manage_posts
                'message' => $message,
                'page_info' => $pageInfo,
                'page_info_error' => $pageInfoError,
                'scopes' => $scopes,
                'token_type' => $tokenType,
                'is_page_token' => $isPageToken,
                'debug_info' => $debugInfo,
                'has_required_permissions' => $hasRequiredPermissions,
                'has_pages_read_engagement' => $hasPagesReadEngagement,
                'has_pages_manage_posts' => $hasPagesManagePosts,
                'instructions' => !$hasPagesManagePosts ? [
                    '🚨 CRITICAL: Your token is missing "pages_manage_posts" permission!',
                    '',
                    'You MUST get a Page Access Token with pages_manage_posts:',
                    '',
                    'Step 1: Go to https://developers.facebook.com/tools/explorer/',
                    'Step 2: Select your Facebook App from the dropdown',
                    'Step 3: Click "Generate Access Token" button',
                    'Step 4: In the permissions popup, search and select:',
                    '  ✅ pages_manage_posts (REQUIRED - select this!)',
                    '  ⚠️  pages_read_engagement (optional - may require app review)',
                    'Step 5: Click "Generate Access Token"',
                    'Step 6: ⚠️ CRITICAL - Click the dropdown next to "User or Page"',
                    'Step 7: Select your PAGE (not your profile)',
                    'Step 8: Copy the NEW Page Access Token (it will be different from user token)',
                    'Step 9: Paste it in your .env file: FACEBOOK_PAGE_ACCESS_TOKEN=your_token_here',
                    'Step 10: Save and test again',
                    '',
                    'Note: pages_manage_posts is REQUIRED for posting. pages_read_engagement is optional.',
                ] : (!$hasPagesReadEngagement ? [
                    'ℹ️  Your token has pages_manage_posts (can post), but missing pages_read_engagement.',
                    'Posting should work fine. pages_read_engagement is optional and may require app review.',
                ] : null),
            ];
        } catch (\Exception $e) {
            Log::error('Error verifying Facebook token', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Error verifying token: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check if Facebook profile is configured for follower count
     */
    public function isProfileConfigured(): bool
    {
        return !empty($this->profileId);
    }

    /**
     * Get Facebook follower count
     * Note: This requires a Page (not a personal profile) and appropriate permissions
     * For personal profiles, follower count is not available via Graph API
     * Cached for 1 hour to avoid too many API calls
     */
    public function getFollowerCount(bool $forceRefresh = false): ?string
    {
        if (!$this->isProfileConfigured()) {
            return null;
        }

        // Clear cache if force refresh is requested
        if ($forceRefresh) {
            Cache::forget('facebook_follower_count');
        }

        // Cache for 1 hour (3600 seconds)
        return Cache::remember('facebook_follower_count', 3600, function () {
            try {
                // Try to get follower count from page insights
                // Note: This requires the page to be a Facebook Page (not personal profile)
                // and the access token needs 'read_insights' permission
                $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->profileId}";
                $params = [
                    'fields' => 'fan_count,followers_count',
                ];

                // If we have an access token, use it
                if ($this->accessToken) {
                    $params['access_token'] = $this->accessToken;
                }

                Log::info('Fetching Facebook follower count', [
                    'profile_id' => $this->profileId,
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
                    
                    // Try followers_count first, then fan_count
                    $count = null;
                    if (isset($data['followers_count'])) {
                        $count = (int) $data['followers_count'];
                    } elseif (isset($data['fan_count'])) {
                        $count = (int) $data['fan_count'];
                    }

                    if ($count !== null) {
                        $formatted = $this->formatFollowerCount($count);
                        
                        Log::info('Successfully fetched Facebook follower count', [
                            'count' => $count,
                            'formatted' => $formatted,
                        ]);
                        
                        return $formatted;
                    } else {
                        Log::warning('Facebook API response missing follower count', [
                            'response' => $data,
                        ]);
                    }
                } else {
                    $errorData = $response->json();
                    $statusCode = $response->status();
                    
                    Log::error('Failed to fetch Facebook follower count', [
                        'status' => $statusCode,
                        'error' => $errorData,
                        'profile_id' => $this->profileId,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception while fetching Facebook follower count', [
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
        if (!$this->isProfileConfigured()) {
            return 'Facebook profile ID not configured';
        }
        return 'Ready';
    }
}

