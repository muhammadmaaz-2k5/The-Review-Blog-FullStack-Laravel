/**
 * Analytics Tracking Script
 * Tracks page views, time on page, and custom events
 */

(function() {
    'use strict';

    // Don't track on admin or author pages
    if (window.location.pathname.startsWith('/admin') || window.location.pathname.startsWith('/author')) {
        return;
    }

    let currentViewId = null;
    let pageStartTime = Date.now();
    let timeOnPageInterval = null;
    let isTracking = false;

    // Configuration
    const config = {
        trackRoute: '/analytics/track/view',
        timeRoute: '/analytics/track/time',
        eventRoute: '/analytics/track/event',
        updateInterval: 30000, // Update time on page every 30 seconds
        minTimeToTrack: 5000, // Minimum 5 seconds to track a view
    };

    /**
     * Get current page information
     */
    function getPageInfo() {
        const viewableType = document.querySelector('[data-viewable-type]')?.dataset.viewableType || null;
        const viewableId = document.querySelector('[data-viewable-id]')?.dataset.viewableId || null;

        return {
            page_path: window.location.pathname + window.location.search,
            page_title: document.title,
            viewable_id: viewableId ? parseInt(viewableId) : null,
            viewable_type: viewableType,
            screen_resolution: `${window.screen.width}x${window.screen.height}`,
        };
    }

    /**
     * Get user's location via IP (this would be done server-side in production)
     */
    async function getLocation() {
        try {
            // In production, you'd use a proper geolocation API
            // For now, return null - server will detect from IP
            return { country: null, city: null };
        } catch (error) {
            return { country: null, city: null };
        }
    }

    /**
     * Track Core Web Vitals
     */
    function trackCoreWebVitals() {
        // Track Largest Contentful Paint (LCP)
        if ('PerformanceObserver' in window) {
            try {
                const lcpObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    const lastEntry = entries[entries.length - 1];
                    
                    if (window.gtag) {
                        window.gtag('event', 'web_vitals', {
                            event_category: 'Web Vitals',
                            event_label: 'LCP',
                            value: Math.round(lastEntry.renderTime || lastEntry.loadTime),
                            non_interaction: true,
                        });
                    }
                    
                    // Track to custom analytics
                    trackEvent('web_vital', {
                        category: 'Web Vitals',
                        action: 'LCP',
                        value: Math.round(lastEntry.renderTime || lastEntry.loadTime)
                    });
                });
                lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
            } catch (e) {
                // PerformanceObserver not supported
            }

            // Track First Input Delay (FID)
            try {
                const fidObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    entries.forEach((entry) => {
                        if (window.gtag) {
                            window.gtag('event', 'web_vitals', {
                                event_category: 'Web Vitals',
                                event_label: 'FID',
                                value: Math.round(entry.processingStart - entry.startTime),
                                non_interaction: true,
                            });
                        }
                        
                        trackEvent('web_vital', {
                            category: 'Web Vitals',
                            action: 'FID',
                            value: Math.round(entry.processingStart - entry.startTime)
                        });
                    });
                });
                fidObserver.observe({ entryTypes: ['first-input'] });
            } catch (e) {
                // PerformanceObserver not supported
            }

            // Track Cumulative Layout Shift (CLS)
            let clsValue = 0;
            try {
                const clsObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    entries.forEach((entry) => {
                        if (!entry.hadRecentInput) {
                            clsValue += entry.value;
                        }
                    });
                });
                clsObserver.observe({ entryTypes: ['layout-shift'] });
                
                // Report CLS when page is hidden
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden' && clsValue > 0) {
                        if (window.gtag) {
                            window.gtag('event', 'web_vitals', {
                                event_category: 'Web Vitals',
                                event_label: 'CLS',
                                value: Math.round(clsValue * 1000) / 1000,
                                non_interaction: true,
                            });
                        }
                        trackEvent('web_vital', {
                            category: 'Web Vitals',
                            action: 'CLS',
                            value: Math.round(clsValue * 1000) / 1000
                        });
                    }
                });
            } catch (e) {
                // PerformanceObserver not supported
            }
        }
    }

    /**
     * Track a page view
     */
    async function trackView() {
        if (isTracking) return;
        
        // Double check we're not on admin pages
        if (window.location.pathname.startsWith('/admin') || window.location.pathname.startsWith('/author')) {
            return;
        }

        try {
            const pageInfo = getPageInfo();
            const location = await getLocation();

            const response = await fetch(config.trackRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    ...pageInfo,
                    ...location,
                }),
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.view_id) {
                    currentViewId = data.view_id;
                    isTracking = true;

                    // Start tracking time on page
                    startTimeTracking();

                    // Track page visibility changes
                    trackVisibility();
                    
                    // Track Core Web Vitals
                    trackCoreWebVitals();
                }
                // If success is false, silently fail - analytics errors shouldn't break the page
            } else {
                // Non-200 response - log only in development
                if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                    const errorData = await response.json().catch(() => ({}));
                    console.warn('Analytics tracking failed:', errorData.error || 'Unknown error');
                }
            }
        } catch (error) {
            // Silently fail - don't log errors to console in production
            // Analytics failures should never break the user experience
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.warn('Analytics tracking error:', error.message);
            }
        }
    }

    /**
     * Start tracking time on page
     */
    function startTimeTracking() {
        if (timeOnPageInterval) return;

        timeOnPageInterval = setInterval(() => {
            updateTimeOnPage();
        }, config.updateInterval);

        // Update time on page before page unload
        window.addEventListener('beforeunload', () => {
            updateTimeOnPage(true); // Force update
        });
    }

    /**
     * Update time on page
     */
    async function updateTimeOnPage(force = false) {
        if (!currentViewId) return;
        
        // Don't track on admin pages
        if (window.location.pathname.startsWith('/admin') || window.location.pathname.startsWith('/author')) {
            return;
        }

        const timeOnPage = Math.floor((Date.now() - pageStartTime) / 1000);

        // Only track if minimum time has passed
        if (!force && timeOnPage < config.minTimeToTrack / 1000) {
            return;
        }

        try {
            const response = await fetch(config.timeRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    view_id: currentViewId,
                    time_on_page: timeOnPage,
                }),
            });
            
            // Check if response was successful
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                // Only log in development
                if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                    console.warn('Time tracking failed:', errorData.error || 'Unknown error');
                }
            }
        } catch (error) {
            // Silently fail - analytics errors shouldn't break the page
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.warn('Time tracking error:', error.message);
            }
        }
    }

    /**
     * Track page visibility changes
     */
    function trackVisibility() {
        // Don't track on admin pages
        if (window.location.pathname.startsWith('/admin') || window.location.pathname.startsWith('/author')) {
            return;
        }
        
        let hiddenTime = 0;
        let visibilityStartTime = Date.now();

        try {
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    // Page became hidden
                    hiddenTime = Date.now();
                } else {
                    // Page became visible again
                    if (hiddenTime > 0) {
                        const hiddenDuration = Date.now() - hiddenTime;
                        // Adjust page start time to account for hidden duration
                        pageStartTime += hiddenDuration;
                        hiddenTime = 0;
                    }
                }
            });
        } catch (error) {
            // Silently fail
        }
    }

    /**
     * Track a custom event
     */
    window.trackEvent = function(eventName, eventData = {}) {
        const defaults = {
            event_name: eventName,
            event_category: eventData.category || 'engagement',
            event_action: eventData.action || eventName,
            event_label: eventData.label || null,
            eventable_id: eventData.eventable_id || null,
            eventable_type: eventData.eventable_type || null,
            value: eventData.value || null,
            metadata: eventData.metadata || null,
        };

        fetch(config.eventRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(defaults),
        }).then(response => {
            if (!response.ok) {
                // Only log in development
                if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                    response.json().then(data => {
                        console.warn('Event tracking failed:', data.error || 'Unknown error');
                    }).catch(() => {});
                }
            }
        }).catch(error => {
            // Silently fail - analytics errors shouldn't break the page
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.warn('Event tracking error:', error.message);
            }
        });
    };

    /**
     * Initialize tracking
     */
    function init() {
        // Don't track on admin pages
        if (window.location.pathname.startsWith('/admin') || window.location.pathname.startsWith('/author')) {
            return;
        }
        
        try {
            // Wait for page to be fully loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    setTimeout(trackView, 1000); // Wait 1 second after DOM ready
                });
            } else {
                setTimeout(trackView, 1000);
            }
        } catch (error) {
            // Silently fail
        }
    }

    // Initialize only if not on admin/author pages
    if (!window.location.pathname.startsWith('/admin') && !window.location.pathname.startsWith('/author')) {
        init();
    }

    // Track navigation for SPA-like behavior
    if (window.history && window.history.pushState) {
        const originalPushState = window.history.pushState;
        window.history.pushState = function() {
            originalPushState.apply(window.history, arguments);
            // Reset tracking for new page
            currentViewId = null;
            pageStartTime = Date.now();
            isTracking = false;
            if (timeOnPageInterval) {
                clearInterval(timeOnPageInterval);
                timeOnPageInterval = null;
            }
            setTimeout(trackView, 500);
        };

        window.addEventListener('popstate', () => {
            currentViewId = null;
            pageStartTime = Date.now();
            isTracking = false;
            if (timeOnPageInterval) {
                clearInterval(timeOnPageInterval);
                timeOnPageInterval = null;
            }
            setTimeout(trackView, 500);
        });
    }
})();

