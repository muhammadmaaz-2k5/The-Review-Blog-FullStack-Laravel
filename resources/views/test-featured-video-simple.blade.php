<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Featured Video Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; border-left: 4px solid; }
        .success { background-color: #d4edda; color: #155724; border-color: #28a745; }
        .error { background-color: #f8d7da; color: #721c24; border-color: #dc3545; }
        .warning { background-color: #fff3cd; color: #856404; border-color: #ffc107; }
        .video-test { border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f8f9ff; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔍 Simple Featured Video Test</h1>
        
        <div class="status {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>Status:</strong> {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? '✅ Video Available' : '⚠️ No Video' }}
        </div>

        @if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
            <div class="video-test">
                <h3>🎬 Testing Component:</h3>
                <p>Video: {{ $randomFeaturedVideo->title ?: 'Untitled' }}</p>
                <p>URL: {{ $randomFeaturedVideo->youtube_url }}</p>
                <p>Active: {{ $randomFeaturedVideo->is_active ? 'Yes' : 'No' }}</p>
                
                <div style="border: 2px dashed #28a745; padding: 20px; margin: 10px 0;">
                    <h4>Component Output:</h4>
                    <x-featured-video-player />
                </div>
            </div>
        @else
            <div class="error">
                <h3>❌ No Video Found</h3>
                <p>The randomFeaturedVideo variable is not available or is null.</p>
            </div>
        @endif

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>🚀 Actions:</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <a href="{{ url('/') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 Homepage</a>
                <a href="{{ route('admin.featured-videos.index') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">⚙️ Admin Panel</a>
                <a href="javascript:location.reload()" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🔄 Refresh</a>
            </div>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
            <h4>💡 Debug Tips:</h4>
            <ul>
                <li>Check if the ViewComposer is working: Look for <code>randomFeaturedVideo</code> variable</li>
                <li>Check Laravel logs: <code>storage/logs/laravel.log</code></li>
                <li>Clear cache: <code>php artisan cache:clear</code></li>
                <li>Check database connection in <code>.env</code> file</li>
            </ul>
        </div>
    </div>
</body>
</html>