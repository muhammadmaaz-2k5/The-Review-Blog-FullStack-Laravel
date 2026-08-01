<?php

namespace App\Services;

use App\Models\AnalyticsView;
use App\Models\AnalyticsEvent;
use App\Models\AnalyticsReferrer;
use App\Models\AnalyticsGeographic;
use App\Models\AnalyticsDevice;
use App\Models\AnalyticsSession;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Track a page view
     */
    public function trackView(array $data): AnalyticsView
    {
        // Helper function to truncate strings to 255 characters
        $truncate = function($value, $maxLength = 255) {
            if (is_null($value)) {
                return null;
            }
            return mb_strlen($value) > $maxLength ? mb_substr($value, 0, $maxLength) : $value;
        };

        return AnalyticsView::create([
            'session_id' => $truncate($data['session_id'] ?? session()->getId(), 255),
            'page_path' => $truncate($data['page_path'] ?? '', 255),
            'page_title' => $truncate($data['page_title'] ?? null, 255),
            'viewable_id' => $data['viewable_id'] ?? null,
            'viewable_type' => $truncate($data['viewable_type'] ?? null, 255),
            'user_id' => $data['user_id'] ?? auth()->id(),
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $truncate($data['user_agent'] ?? request()->userAgent(), 255),
            'referrer' => $truncate($data['referrer'] ?? request()->header('referer'), 255),
            'country' => $truncate($data['country'] ?? null, 100),
            'city' => $truncate($data['city'] ?? null, 100),
            'device_type' => $truncate($data['device_type'] ?? $this->detectDeviceType(), 255),
            'browser' => $truncate($data['browser'] ?? $this->detectBrowser(), 255),
            'os' => $truncate($data['os'] ?? $this->detectOS(), 255),
            'screen_resolution' => $truncate($data['screen_resolution'] ?? null, 50),
            'time_on_page' => $data['time_on_page'] ?? null,
            'is_bounce' => $data['is_bounce'] ?? false,
            'viewed_at' => now(),
        ]);
    }

    /**
     * Track a custom event
     */
    public function trackEvent(array $data): AnalyticsEvent
    {
        return AnalyticsEvent::create([
            'session_id' => $data['session_id'] ?? session()->getId(),
            'event_name' => $data['event_name'],
            'event_category' => $data['event_category'] ?? null,
            'event_action' => $data['event_action'] ?? null,
            'event_label' => $data['event_label'] ?? null,
            'eventable_id' => $data['eventable_id'] ?? null,
            'eventable_type' => $data['eventable_type'] ?? null,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'page_path' => $data['page_path'] ?? request()->path(),
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'metadata' => $data['metadata'] ?? null,
            'value' => $data['value'] ?? null,
            'occurred_at' => now(),
        ]);
    }

    /**
     * Get real-time stats
     */
    public function getRealTimeStats(int $minutes = 30): array
    {
        $since = now()->subMinutes($minutes);

        return [
            'active_users' => AnalyticsView::where('viewed_at', '>=', $since)
                ->distinct('session_id')
                ->count('session_id'),
            'page_views' => AnalyticsView::where('viewed_at', '>=', $since)->count(),
            'top_pages' => AnalyticsView::where('viewed_at', '>=', $since)
                ->select('page_path', DB::raw('count(*) as views'))
                ->groupBy('page_path')
                ->orderBy('views', 'desc')
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Get article performance metrics
     */
    public function getArticlePerformance(int $articleId, $startDate = null, $endDate = null): array
    {
        $query = AnalyticsView::where('viewable_type', Article::class)
            ->where('viewable_id', $articleId);

        if ($startDate) {
            $query->where('viewed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('viewed_at', '<=', $endDate);
        }

        $views = $query->get();
        $totalViews = $views->count();
        $uniqueViews = $views->distinct('session_id')->count('session_id');
        $avgTimeOnPage = $views->whereNotNull('time_on_page')->avg('time_on_page');
        $bounceRate = $views->where('is_bounce', true)->count() / max($totalViews, 1) * 100;

        return [
            'total_views' => $totalViews,
            'unique_views' => $uniqueViews,
            'avg_time_on_page' => round($avgTimeOnPage ?? 0),
            'bounce_rate' => round($bounceRate, 2),
            'views_by_date' => $this->getViewsByDate($views),
        ];
    }

    /**
     * Get traffic sources
     */
    public function getTrafficSources($startDate = null, $endDate = null, int $limit = 10): array
    {
        $query = AnalyticsView::select('referrer', DB::raw('count(*) as visits'))
            ->whereNotNull('referrer')
            ->groupBy('referrer')
            ->orderBy('visits', 'desc');

        if ($startDate) {
            $query->where('viewed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('viewed_at', '<=', $endDate);
        }

        return $query->limit($limit)->get()->map(function($item) {
            $domain = parse_url($item->referrer, PHP_URL_HOST) ?? 'Direct';
            return [
                'domain' => $domain,
                'url' => $item->referrer,
                'visits' => $item->visits,
            ];
        })->toArray();
    }

    /**
     * Get geographic analytics
     */
    public function getGeographicData($startDate = null, $endDate = null, int $limit = 20): array
    {
        $query = AnalyticsView::select('country', 'city', DB::raw('count(*) as visits'))
            ->whereNotNull('country')
            ->groupBy('country', 'city')
            ->orderBy('visits', 'desc');

        if ($startDate) {
            $query->where('viewed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('viewed_at', '<=', $endDate);
        }

        return $query->limit($limit)->get()->toArray();
    }

    /**
     * Get device/browser analytics
     */
    public function getDeviceAnalytics($startDate = null, $endDate = null): array
    {
        $query = AnalyticsView::query();

        if ($startDate) {
            $query->where('viewed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('viewed_at', '<=', $endDate);
        }

        $devices = $query->select('device_type', DB::raw('count(*) as visits'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->get()
            ->pluck('visits', 'device_type')
            ->toArray();

        $browsers = $query->select('browser', DB::raw('count(*) as visits'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get()
            ->pluck('visits', 'browser')
            ->toArray();

        $os = $query->select('os', DB::raw('count(*) as visits'))
            ->whereNotNull('os')
            ->groupBy('os')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get()
            ->pluck('visits', 'os')
            ->toArray();

        return [
            'devices' => $devices,
            'browsers' => $browsers,
            'os' => $os,
        ];
    }

    /**
     * Get popular content trends
     */
    public function getPopularContent($type = 'articles', $startDate = null, $endDate = null, int $limit = 10): array
    {
        $query = AnalyticsView::select('viewable_id', 'viewable_type', DB::raw('count(*) as views'))
            ->where('viewable_type', 'like', "%{$type}%")
            ->groupBy('viewable_id', 'viewable_type')
            ->orderBy('views', 'desc');

        if ($startDate) {
            $query->where('viewed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('viewed_at', '<=', $endDate);
        }

        return $query->limit($limit)->get()->toArray();
    }

    /**
     * Get user engagement metrics
     */
    public function getEngagementMetrics($startDate = null, $endDate = null): array
    {
        $query = AnalyticsView::query();

        if ($startDate) {
            $query->where('viewed_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('viewed_at', '<=', $endDate);
        }

        $totalViews = (clone $query)->count();
        $uniqueVisitors = (clone $query)->distinct('session_id')->count('session_id');
        $avgTimeOnPage = (clone $query)->whereNotNull('time_on_page')->avg('time_on_page');
        $bounceRate = (clone $query)->where('is_bounce', true)->count() / max($totalViews, 1) * 100;
        $pagesPerSession = $totalViews / max($uniqueVisitors, 1);

        return [
            'total_views' => $totalViews,
            'unique_visitors' => $uniqueVisitors,
            'avg_time_on_page' => round($avgTimeOnPage ?? 0),
            'bounce_rate' => round($bounceRate, 2),
            'pages_per_session' => round($pagesPerSession, 2),
        ];
    }

    /**
     * Helper: Detect device type from user agent
     */
    protected function detectDeviceType(): string
    {
        $userAgent = request()->userAgent() ?? '';
        
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Helper: Detect browser from user agent
     */
    protected function detectBrowser(): ?string
    {
        $userAgent = request()->userAgent() ?? '';
        
        if (preg_match('/chrome/i', $userAgent) && !preg_match('/edg|opr/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/edg/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/opr/i', $userAgent)) {
            return 'Opera';
        }
        
        return null;
    }

    /**
     * Helper: Detect OS from user agent
     */
    protected function detectOS(): ?string
    {
        $userAgent = request()->userAgent() ?? '';
        
        if (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        }
        
        return null;
    }

    /**
     * Helper: Get views grouped by date
     */
    protected function getViewsByDate($views): array
    {
        return $views->groupBy(function($view) {
            return Carbon::parse($view->viewed_at)->format('Y-m-d');
        })->map(function($group) {
            return $group->count();
        })->toArray();
    }
}

