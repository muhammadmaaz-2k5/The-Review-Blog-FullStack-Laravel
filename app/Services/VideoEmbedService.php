<?php

namespace App\Services;

class VideoEmbedService
{
    /**
     * Extract video ID from various platform URLs
     */
    public function extractVideoId(string $url): ?array
    {
        // Check for direct embed URL (Doodstream, Mixdrop, etc.)
        if ($this->isDirectEmbedUrl($url)) {
            return [
                'platform' => 'custom',
                'id' => $url,
                'embed_url' => $url,
            ];
        }

        $platforms = [
            'youtube' => '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            'vimeo' => '%vimeo\.com/(?:video/)?([0-9]+)%i',
            'dailymotion' => '%dailymotion\.com/(?:video/)?([a-zA-Z0-9]+)%i',
            'facebook' => '%facebook\.com/.*/videos/([0-9]+)%i',
            'instagram' => '%instagram\.com/(?:p|reel)/([a-zA-Z0-9_]+)%i',
            'tiktok' => '%tiktok\.com/@[^/]+/video/([0-9]+)%i',
            'twitter' => '%twitter\.com/.*/status/([0-9]+)%i',
            'loom' => '%loom\.com/share/([a-zA-Z0-9]+)%i',
            'doodstream' => '%dood\.(?:stream|watch|pro)/(?:d|e)/([a-zA-Z0-9]+)%i',
            'mixdrop' => '%mixdrop\.co/(?:e|f)/([a-zA-Z0-9]+)%i',
            'voe' => '%voe\.sx/e/([a-zA-Z0-9]+)%i',
            'filemoon' => '%filemoon\.sx/e/([a-zA-Z0-9]+)%i',
        ];

        foreach ($platforms as $platform => $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return [
                    'platform' => $platform,
                    'id' => $matches[1],
                ];
            }
        }

        return null;
    }

    /**
     * Check if URL is a direct embed URL (for custom iframes)
     */
    private function isDirectEmbedUrl(string $url): bool
    {
        // Common video hosting domains that provide direct embed URLs
        $embedDomains = [
            'dood.stream', 'dood.watch', 'dood.pro',
            'mixdrop.co', 'mixdrop.to',
            'voe.sx', 'voe-unblock.com',
            'filemoon.sx', 'filemoon.to',
            'streamtape.com', 'stape.fun',
            'upstream.to', 'upstream.nz',
        ];

        foreach ($embedDomains as $domain) {
            if (strpos($url, $domain) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate embed HTML for a video URL
     */
    public function generateEmbed(string $url, array $options = []): string
    {
        $videoData = $this->extractVideoId($url);
        
        if (!$videoData) {
            return '';
        }

        $width = $options['width'] ?? '100%';
        $height = $options['height'] ?? 'auto';
        // Default to 16:9 (horizontal) for all platforms except TikTok/Instagram which have their own handling
        $aspectRatio = $options['aspectRatio'] ?? '16by9'; // 16by9, 4by3, 21by9, 1by1
        $autoplay = $options['autoplay'] ?? false;
        $loop = $options['loop'] ?? false;
        $muted = $options['muted'] ?? false;

        $embedUrl = $this->getEmbedUrl($videoData['platform'], $videoData['id'], $autoplay, $muted);

        if (!$embedUrl) {
            return '';
        }

        return view('components.video-embed', [
            'platform' => $videoData['platform'],
            'embedUrl' => $embedUrl,
            'videoId' => $videoData['id'],
            'width' => $width,
            'aspectRatio' => $aspectRatio,
            'autoplay' => $autoplay,
            'loop' => $loop,
            'muted' => $muted,
        ])->render();
    }

    /**
     * Get embed URL based on platform
     */
    private function getEmbedUrl(string $platform, string $id, bool $autoplay = false, bool $muted = false): ?string
    {
        $params = [];
        
        if ($autoplay) {
            $params[] = 'autoplay=1';
        }
        
        if ($muted) {
            $params[] = 'mute=1';
        }

        $queryString = !empty($params) ? '?' . implode('&', $params) : '';

        return match($platform) {
            'youtube' => "https://www.youtube.com/embed/{$id}{$queryString}",
            'vimeo' => "https://player.vimeo.com/video/{$id}{$queryString}",
            'dailymotion' => "https://www.dailymotion.com/embed/video/{$id}{$queryString}",
            'facebook' => "https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/facebook/videos/{$id}/{$queryString}",
            'instagram' => "https://www.instagram.com/p/{$id}/embed",
            'tiktok' => null, // TikTok requires iframe with specific structure
            'twitter' => "https://platform.twitter.com/embed/Tweet.html?id={$id}",
            'loom' => "https://www.loom.com/embed/{$id}{$queryString}",
            'doodstream' => "https://dood.stream/e/{$id}",
            'mixdrop' => "https://mixdrop.co/e/{$id}",
            'voe' => "https://voe.sx/e/{$id}",
            'filemoon' => "https://filemoon.sx/e/{$id}",
            'custom' => $id, // For custom embed URLs, use as-is
            default => null,
        };
    }

    /**
     * Process content and auto-embed video URLs
     */
    public function processContent(string $content): string
    {
        // Pattern to match standalone video URLs (not inside href attributes)
        $patterns = [
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:youtube\.com/watch\?v=[^&\s]+|youtu\.be/[^&\s]+|youtube\.com/shorts/[^&\s]+|vimeo\.com/[0-9]+|dailymotion\.com/video/[^&\s]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:facebook\.com/.*/videos/[0-9]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:instagram\.com/(?:p|reel)/[^&\s]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:tiktok\.com/@[^/]+/video/[0-9]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:twitter\.com/.*/status/[0-9]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:loom\.com/share/[^&\s]+))#i',
            // Custom embed URLs (Doodstream, Mixdrop, etc.) - must be on their own line
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:dood\.(?:stream|watch|pro)/(?:d|e)/[a-zA-Z0-9]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:mixdrop\.co/(?:e|f)/[a-zA-Z0-9]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:voe\.sx/e/[a-zA-Z0-9]+))#i',
            '#(?<!href=["\'])(?<!src=["\'])(https?://(?:www\.)?(?:filemoon\.sx/e/[a-zA-Z0-9]+))#i',
        ];

        foreach ($patterns as $pattern) {
            $content = preg_replace_callback($pattern, function ($matches) {
                $url = $matches[1];
                $embed = $this->generateEmbed($url);
                
                if ($embed) {
                    return $embed;
                }
                
                return $url;
            }, $content);
        }

        return $content;
    }
}
