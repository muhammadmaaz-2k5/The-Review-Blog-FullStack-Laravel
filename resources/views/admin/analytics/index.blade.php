@extends('layouts.app')

@section('title', 'Analytics Dashboard - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Analytics Dashboard
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Track your website performance and user engagement
            </p>
        </div>
        <div class="flex gap-3">
            <!-- Date Range Filter -->
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border rounded-lg dark:!bg-bg-card dark:!border-border-primary dark:!text-white">
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border rounded-lg dark:!bg-bg-card dark:!border-border-primary dark:!text-white">
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Apply
                </button>
            </form>
            <a href="{{ route('admin.analytics.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'json']) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Export
            </a>
        </div>
    </div>

    <!-- Real-Time Stats -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Active Users (30min)
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ $realTime['active_users'] ?? 0 }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:!bg-green-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:!text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Total Views
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($engagement['total_views'] ?? 0) }}
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:!bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Unique Visitors
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($engagement['unique_visitors'] ?? 0) }}
                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:!bg-purple-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600 dark:!text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Engagement Metrics -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Avg. Time on Page
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ number_format(($engagement['avg_time_on_page'] ?? 0) / 60, 1) }}m
            </p>
        </div>
        
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Bounce Rate
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ number_format($engagement['bounce_rate'] ?? 0, 1) }}%
            </p>
        </div>
        
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Pages/Session
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ number_format($engagement['pages_per_session'] ?? 0, 1) }}
            </p>
        </div>
        
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Real-Time Views
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $realTime['page_views'] ?? 0 }}
            </p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Views Over Time Chart -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Views Over Time
            </h2>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>
        
        <!-- Device Types Chart -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Device Types
            </h2>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Pages & Popular Articles -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Pages -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Top Pages
            </h2>
            <div class="space-y-3">
                @forelse($topPages as $page)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                {{ Str::limit($page->page_path, 50) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ number_format($page->unique_views) }} unique views
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                {{ number_format($page->views) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        No page views yet
                    </p>
                @endforelse
            </div>
        </div>
        
        <!-- Popular Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Popular Articles
            </h2>
            <div class="space-y-3">
                @forelse($popularArticles as $article)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('articles.show', $article) }}" class="hover:text-accent transition-colors">
                                    {{ Str::limit($article->title, 50) }}
                                </a>
                            </p>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Views: {{ number_format($article->views_count ?? 0) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.articles.analytics', $article) }}" class="text-accent hover:text-accent-light text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Details →
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        No article views yet
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Wait for Chart.js to load and DOM to be ready
    function initCharts() {
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            // Retry after a short delay if Chart.js hasn't loaded yet
            setTimeout(initCharts, 100);
            return;
        }

        // Views Over Time Chart
        const viewsCtx = document.getElementById('viewsChart');
        let viewsChartInstance = null;
        
        if (viewsCtx) {
            const viewsData = @json($viewsOverTime ?? []);
            
            // Debug logging (only in development)
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('Views Over Time Data:', viewsData);
            }
            
            // Format data for chart - handle empty data
            let labels = [];
            let data = [];
            
            if (viewsData && Array.isArray(viewsData) && viewsData.length > 0) {
                // Process each data point
                viewsData.forEach(v => {
                    try {
                        // Handle different date formats - ensure we have the date property
                        let dateStr = v.date || v.viewed_at || '';
                        if (dateStr) {
                            // Parse date - handle YYYY-MM-DD format
                            // Add time component to avoid timezone issues
                            const date = new Date(dateStr + 'T00:00:00');
                            if (!isNaN(date.getTime())) {
                                labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
                                data.push(parseInt(v.views || v.count || 0) || 0);
                            } else {
                                // Try parsing without time component
                                const date2 = new Date(dateStr);
                                if (!isNaN(date2.getTime())) {
                                    labels.push(date2.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
                                    data.push(parseInt(v.views || v.count || 0) || 0);
                                }
                            }
                        }
                    } catch (e) {
                        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                            console.error('Error parsing date:', e, v);
                        }
                    }
                });
                
                // If no valid data was parsed, show placeholder
                if (labels.length === 0 || data.length === 0) {
                    labels = ['No Data'];
                    data = [0];
                }
            } else {
                // Show placeholder for empty data
                labels = ['No Data'];
                data = [0];
            }
            
            // Debug logging
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('Processed Labels:', labels);
                console.log('Processed Data:', data);
            }
            
            // Destroy existing chart if it exists
            if (viewsChartInstance) {
                viewsChartInstance.destroy();
            }
            
            viewsChartInstance = new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Page Views',
                        data: data,
                        borderColor: '#E50914',
                        backgroundColor: 'rgba(229, 9, 20, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#E50914',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 10,
                            left: 10,
                            right: 10
                        }
                    },
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 11
                            },
                            borderColor: '#E50914',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Views: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: {
                                    size: 11
                                },
                                color: '#666'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 10
                                },
                                color: '#666'
                            },
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
            
            // Handle window resize
            let viewsResizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(viewsResizeTimeout);
                viewsResizeTimeout = setTimeout(function() {
                    if (viewsChartInstance) {
                        viewsChartInstance.resize();
                    }
                }, 100);
            });
            
            // Handle scroll events to ensure chart stays rendered
            let viewsScrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(viewsScrollTimeout);
                viewsScrollTimeout = setTimeout(function() {
                    if (viewsChartInstance) {
                        viewsChartInstance.resize();
                    }
                }, 150);
            }, { passive: true });
        }

        // Device Types Chart
        const deviceCtx = document.getElementById('deviceChart');
        let deviceChartInstance = null;
        
        if (deviceCtx) {
            const deviceData = @json($deviceAnalytics['devices'] ?? []);
            let deviceLabels = Object.keys(deviceData);
            let deviceValues = Object.values(deviceData).map(v => parseInt(v) || 0);
            
            // If no data, show placeholder
            if (deviceLabels.length === 0 || deviceValues.every(v => v === 0)) {
                deviceLabels = ['No Data'];
                deviceValues = [1];
            }
            
            deviceChartInstance = new Chart(deviceCtx, {
                type: 'doughnut',
                data: {
                    labels: deviceLabels,
                    datasets: [{
                        label: 'Device Types',
                        data: deviceValues,
                        backgroundColor: [
                            '#E50914',
                            '#FF6B6B',
                            '#4ECDC4',
                            '#95E1D3',
                            '#FFD93D',
                            '#6BCB77'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1.5,
                    animation: {
                        animateRotate: true,
                        animateScale: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
            
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (deviceChartInstance) {
                        deviceChartInstance.resize();
                    }
                }, 100);
            });
            
            // Handle scroll events to ensure chart stays rendered
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if (deviceChartInstance) {
                        deviceChartInstance.resize();
                    }
                }, 150);
            }, { passive: true });
        }
    }

    // Start initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        // DOM is already ready, but wait a bit for Chart.js to load
        setTimeout(initCharts, 50);
    }
</script>
@endpush
@endsection

