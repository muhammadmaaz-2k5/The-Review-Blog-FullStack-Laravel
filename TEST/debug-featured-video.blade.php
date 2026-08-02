<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Featured Video</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .debug-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; border-left: 4px solid; }
        .success { background-color: #d4edda; color: #155724; border-color: #28a745; }
        .error { background-color: #f8d7da; color: #721c24; border-color: #dc3545; }
        .warning { background-color: #fff3cd; color: #856404; border-color: #ffc107; }
        .info { background-color: #d1ecf1; color: #0c5460; border-color: #17a2b8; }
        .video-info { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; margin: 15px 0; border-radius: 8px; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 12px; margin: 10px 0; }
        .test-section { margin: 20px 0; padding: 20px; border: 2px dashed #007bff; border-radius: 10px; background: #f8f9ff; }
    </style>
</head>
<body>
    <div class="debug-container">
        <h1>🔍 Featured Video System Debug</h1>
        
        <div class="status {{ $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>🎯 Main Status:</strong> {{ $randomFeaturedVideo ? '✅ Video Found & Available' : '⚠️ No Video Available' }}
        </div>

        @if($randomFeaturedVideo)
            <div class="video-info">
                <h3>📋 Video Details:</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px; font-weight: bold;">Title:</td>
                        <td style="padding: 8px;">{{ $randomFeaturedVideo->title ?: 'No Title' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; font-weight: bold;">YouTube URL:</td>
                        <td style="padding: 8px;">
                            <a href="{{ $randomFeaturedVideo->youtube_url }}" target="_blank" style="color: #007bff;">
                                {{ $randomFeaturedVideo->youtube_url }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; font-weight: bold;">Is Active:</td>
                        <td style="padding: 8px;">
                            @if($randomFeaturedVideo->is_active)
                                <span style="color: #28a745;">✅ Active</span>
                            @else
                                <span style="color: #dc3545;">❌ Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; font-weight: bold;">Created:</td>
                        <td style="padding: 8px;">{{ $randomFeaturedVideo->created_at ? $randomFeaturedVideo->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="test-section">
                <h3>🎬 Video Preview Test:</h3>
                <p>This should show the actual featured video player:</p>
                <x-featured-video-player />
            </div>
        @else
            <div class="error">
                <h3>❌ No Featured Videos Available</h3>
                <p>Please check the following:</p>
                <ul>
                    <li>🗄️ Are there any active featured videos in the database?</li>
                    <li>🔗 Is the database connection working properly?</li>
                    <li>📊 Are there any errors in the Laravel logs?</li>
                    <li>🔄 Try clearing the cache: <code>php artisan featured-videos:clear-cache</code></li>
                </ul>
            </div>
        @endif

        <div class="info">
            <h3>🔧 System Information:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Current Time:</td>
                    <td style="padding: 8px;">{{ now()->format('Y-m-d H:i:s') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Cache Status:</td>
                    <td style="padding: 8px;">
                        @if(cache()->has('random_featured_video'))
                            <span style="color: #28a745;">✅ Cached</span>
                        @else
                            <span style="color: #dc3545;">❌ Not Cached</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Total Videos:</td>
                    <td style="padding: 8px;">{{ $totalVideos ?? 'Unknown' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">View Composer:</td>
                    <td style="padding: 8px;">{{ class_exists('\App\View\Composers\FeaturedVideoComposer') ? '✅ Loaded' : '❌ Not Found' }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>🚀 Quick Actions:</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <a href="{{ url('/') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">🏠 Go to Homepage</a>
                <a href="{{ route('admin.featured-videos.index') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">⚙️ Admin Panel</a>
                <a href="javascript:location.reload()" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">🔄 Refresh Page</a>
            </div>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
            <h4>💡 Debug Tips:</h4>
            <ul>
                <li>Check Laravel logs: <code>storage/logs/laravel.log</code></li>
                <li>Clear all cache: <code>php artisan cache:clear</code></li>
                <li>Check database connection in <code>.env</code> file</li>
                <li>Verify the ViewComposer is registered in AppServiceProvider</li>
                <li>Check if the component is included in app.blade.php</li>
            </ul>
        </div>
    </div>
</body>
</html>