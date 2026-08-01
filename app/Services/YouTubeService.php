<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class YouTubeService
{
    protected $apiKey;
    protected $channelId;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
        $this->channelId = config('services.youtube.channel_id', 'UCOiiIYdcKBeMFDCa42iylmA');
    }

    /**
     * Check if YouTube integration is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get API key status message
     */
    public function getStatusMessage(): string
    {
        if ($this->isConfigured()) {
            return 'YouTube API is configured.';
        }
        
        return 'YouTube API key is not configured. Please add YOUTUBE_API_KEY to your .env file. Get your API key from: https://console.cloud.google.com/apis/credentials?project=elite-campus-481706-b7';
    }

    /**
     * Get channel subscriber count
     * Cached for 1 hour to avoid too many API calls
     */
    public function getSubscriberCount(bool $forceRefresh = false): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        // Clear cache if force refresh is requested
        if ($forceRefresh) {
            Cache::forget('youtube_subscriber_count');
        }

        // Cache for 1 hour (3600 seconds)
        return Cache::remember('youtube_subscriber_count', 3600, function () {
            try {
                $url = 'https://www.googleapis.com/youtube/v3/channels';
                $params = [
                    'part' => 'statistics',
                    'id' => $this->channelId,
                    'key' => $this->apiKey,
                ];

                Log::info('Fetching YouTube subscriber count', [
                    'channel_id' => $this->channelId,
                    'api_key_prefix' => substr($this->apiKey, 0, 10) . '...',
                ]);

                // Disable SSL verification for local development (not recommended for production)
                $verifySSL = config('app.env') !== 'local';
                
                $response = Http::timeout(10)
                    ->withOptions([
                        'verify' => $verifySSL,
                    ])
                    ->get($url, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['items']) && count($data['items']) > 0) {
                        if (isset($data['items'][0]['statistics']['subscriberCount'])) {
                            $count = (int) $data['items'][0]['statistics']['subscriberCount'];
                            $formatted = $this->formatSubscriberCount($count);
                            
                            Log::info('Successfully fetched YouTube subscriber count', [
                                'count' => $count,
                                'formatted' => $formatted,
                            ]);
                            
                            return $formatted;
                        } else {
                            Log::warning('YouTube API response missing subscriber count in statistics', [
                                'statistics' => $data['items'][0]['statistics'] ?? 'not set',
                            ]);
                        }
                    } else {
                        Log::warning('YouTube API returned no items', [
                            'response' => $data,
                        ]);
                    }
                } else {
                    $errorData = $response->json();
                    $statusCode = $response->status();
                    
                    Log::error('Failed to fetch YouTube subscriber count', [
                        'status' => $statusCode,
                        'error' => $errorData,
                        'channel_id' => $this->channelId,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception while fetching YouTube subscriber count', [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return null;
        });
    }

    /**
     * Format subscriber count (e.g., 2000000 -> "2.0M")
     */
    protected function formatSubscriberCount(int $count): string
    {
        if ($count >= 1000000) {
            return number_format($count / 1000000, 1) . 'M';
        } elseif ($count >= 1000) {
            return number_format($count / 1000, 1) . 'K';
        }
        
        return number_format($count);
    }

    /**
     * Get channel statistics
     */
    public function getChannelStats(): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => 'statistics,snippet',
                'id' => $this->channelId,
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['items'][0])) {
                    return [
                        'subscriber_count' => $this->formatSubscriberCount((int) $data['items'][0]['statistics']['subscriberCount']),
                        'video_count' => number_format((int) $data['items'][0]['statistics']['videoCount']),
                        'view_count' => number_format((int) $data['items'][0]['statistics']['viewCount']),
                        'title' => $data['items'][0]['snippet']['title'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception while fetching YouTube channel stats', [
                'exception' => $e->getMessage(),
            ]);
        }

        return null;
    }
}

