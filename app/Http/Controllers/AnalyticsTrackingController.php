<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AnalyticsTrackingController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Track a page view (called via JavaScript/AJAX)
     */
    public function trackView(Request $request)
    {
        try {
            $validated = $request->validate([
                'page_path' => 'required|string|max:500',
                'page_title' => 'nullable|string|max:500',
                'viewable_id' => 'nullable|integer',
                'viewable_type' => 'nullable|string|max:255',
                'time_on_page' => 'nullable|integer',
                'screen_resolution' => 'nullable|string|max:50',
                'country' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
            ]);

            // Truncate long strings to fit database columns
            $userAgent = $request->userAgent();
            $referrer = $request->header('referer');
            
            $data = array_merge($validated, [
                'session_id' => session()->getId(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent ? mb_substr($userAgent, 0, 255) : null,
                'referrer' => $referrer ? mb_substr($referrer, 0, 255) : null,
                'page_path' => mb_substr($validated['page_path'], 0, 255),
                'page_title' => isset($validated['page_title']) ? mb_substr($validated['page_title'], 0, 255) : null,
            ]);

            $view = $this->analyticsService->trackView($data);

            return response()->json([
                'success' => true,
                'view_id' => $view->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Analytics tracking error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            // Return 200 with success:false instead of 500 to prevent console errors
            // Analytics failures shouldn't break the user experience
            return response()->json([
                'success' => false,
                'error' => 'Failed to track view',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 200);
        }
    }

    /**
     * Track time on page update
     */
    public function trackTimeOnPage(Request $request)
    {
        try {
            $validated = $request->validate([
                'view_id' => 'required|integer|exists:analytics_views,id',
                'time_on_page' => 'required|integer|min:0',
            ]);

            \App\Models\AnalyticsView::where('id', $validated['view_id'])
                ->where('session_id', session()->getId())
                ->update(['time_on_page' => $validated['time_on_page']]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Analytics time tracking error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            // Return 200 with success:false instead of 500
            return response()->json([
                'success' => false,
                'error' => 'Failed to track time on page',
            ], 200);
        }
    }

    /**
     * Track a custom event
     */
    public function trackEvent(Request $request)
    {
        try {
            $validated = $request->validate([
                'event_name' => 'required|string|max:255',
                'event_category' => 'nullable|string|max:255',
                'event_action' => 'nullable|string|max:255',
                'event_label' => 'nullable|string|max:255',
                'eventable_id' => 'nullable|integer',
                'eventable_type' => 'nullable|string|max:255',
                'value' => 'nullable|integer',
                'metadata' => 'nullable|array',
            ]);

            $data = array_merge($validated, [
                'session_id' => session()->getId(),
                'user_id' => Auth::id(),
                'page_path' => mb_substr($request->path(), 0, 500),
                'ip_address' => $request->ip(),
            ]);

            $event = $this->analyticsService->trackEvent($data);

            return response()->json([
                'success' => true,
                'event_id' => $event->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Analytics event tracking error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            // Return 200 with success:false instead of 500
            return response()->json([
                'success' => false,
                'error' => 'Failed to track event',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 200);
        }
    }
}
