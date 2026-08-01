<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Featured Video</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-container { max-width: 800px; margin: 0 auto; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .video-info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; padding: 15px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>Featured Video System Test</h1>
        
        <div class="status {{ $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>Status:</strong> {{ $randomFeaturedVideo ? 'Video Found' : 'No Video Available' }}
        </div>

        @if($randomFeaturedVideo)
            <div class="video-info">
                <h3>Video Details:</h3>
                <p><strong>Title:</strong> {{ $randomFeaturedVideo->title ?: 'No Title' }}</p>
                <p><strong>YouTube URL:</strong> {{ $randomFeaturedVideo->youtube_url }}</p>
                <p><strong>Is Active:</strong> {{ $randomFeaturedVideo->is_active ? 'Yes' : 'No' }}</p>
                <p><strong>Created:</strong> {{ $randomFeaturedVideo->created_at }}</p>
            </div>

            <h3>Video Preview:</h3>
            <x-featured-video-player />
        @else
            <div class="error">
                <p>No featured videos are available. Please check:</p>
                <ul>
                    <li>Are there any active featured videos in the database?</li>
                    <li>Is the database connection working properly?</li>
                    <li>Are there any errors in the Laravel logs?</li>
                </ul>
            </div>
        @endif

        <h3>System Information:</h3>
        <div class="video-info">
            <p><strong>Current Time:</strong> {{ now() }}</p>
            <p><strong>Cache Status:</strong> {{ cache()->has('random_featured_video') ? 'Cached' : 'Not Cached' }}</p>
            <p><strong>Total Videos (may be cached):</strong> {{ $totalVideos ?? 'Unknown' }}</p>
        </div>

        <h3>Actions:</h3>
        <div style="margin-top: 20px;">
            <a href="{{ url('/') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Go to Homepage</a>
            <a href="{{ route('admin.featured-videos.index') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Admin Panel</a>
        </div>
    </div>
</body>
</html>