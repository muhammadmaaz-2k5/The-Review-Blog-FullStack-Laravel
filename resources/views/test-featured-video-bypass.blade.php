<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bypass Test - Featured Video</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .warning { background-color: #fff3cd; color: #856404; }
        .video-test { border: 2px dashed #007bff; padding: 20px; margin: 20px 0; background: #f8f9ff; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🚀 Bypass Test - Featured Video</h1>
        
        <div class="status {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>Status:</strong> {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? '✅ Video Available' : '⚠️ No Video' }}
        </div>

        @if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
            <div class="video-test">
                <h3>🎬 Testing Component (No Session Storage):</h3>
                <p>Video: {{ $randomFeaturedVideo->title ?: 'Untitled' }}</p>
                <p>URL: {{ $randomFeaturedVideo->youtube_url }}</p>
                <p>Active: {{ $randomFeaturedVideo->is_active ? 'Yes' : 'No' }}</p>
                
                <div style="border: 2px solid #28a745; padding: 20px; margin: 10px 0;">
                    <x-featured-video-player-test />
                </div>
            </div>
        @else
            <div class="error">
                <h3>❌ No Video Found</h3>
                <p>The randomFeaturedVideo variable is not available.</p>
            </div>
        @endif

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>🚀 Actions:</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <a href="{{ url('/') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 Homepage</a>
                <a href="{{ route('admin.featured-videos.index') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">⚙️ Admin Panel</a>
                <button onclick="clearAllStorage()" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">🗑️ Clear All Storage</button>
            </div>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
            <h4>💡 Debug Tips:</h4>
            <ul>
                <li>This test bypasses session storage completely</li>
                <li>Check browser console for JavaScript errors</li>
                <li>Check if the video appears in the bottom-left corner</li>
                <li>Use browser dev tools to inspect the DOM</li>
            </ul>
        </div>
    </div>

    <script>
        function clearAllStorage() {
            sessionStorage.clear();
            localStorage.clear();
            alert('All storage cleared! Refreshing page...');
            setTimeout(() => location.reload(), 500);
        }

        // Log to console for debugging
        console.log('🚀 Featured Video Bypass Test Loaded');
        console.log('Session Storage Status:', sessionStorage.getItem('featuredVideoClosed'));
        
        // Check if component exists
        setTimeout(() => {
            const container = document.getElementById('featured-video-container-test');
            if (container) {
                console.log('✅ Test component found in DOM');
                console.log('Container styles:', window.getComputedStyle(container));
            } else {
                console.log('❌ Test component NOT found in DOM');
            }
        }, 1000);
    </script>
</body>
</html>