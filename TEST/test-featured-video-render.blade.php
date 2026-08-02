<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Render Test - Featured Video</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .warning { background-color: #fff3cd; color: #856404; }
        .render-output { background: #f8f9fa; border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .code { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 12px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔍 Render Test - Featured Video</h1>
        
        <div class="status {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>Status:</strong> {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? '✅ Video Available' : '⚠️ No Video' }}
        </div>

        @if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
            <div class="render-output">
                <h3>🎬 Raw Component Render:</h3>
                <p>This is what the component actually renders:</p>
                
                <div class="code">
@php
    // Try to render the component and capture output
    try {
        $output = view('components.featured-video-player-no-session', ['randomFeaturedVideo' => $randomFeaturedVideo])->render();
        echo htmlentities($output);
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString();
    }
@endphp
                </div>
            </div>

            <div class="render-output">
                <h3>📋 Component Data:</h3>
                <div class="code">
Video Object:
{{ json_encode($randomFeaturedVideo->toArray(), JSON_PRETTY_PRINT) }}

Extracted Video ID: {{ preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $randomFeaturedVideo->youtube_url, $match) ? $match[1] : 'NOT FOUND' }}

Is YouTube Short: {{ str_contains($randomFeaturedVideo->youtube_url, '/shorts/') ? 'YES' : 'NO' }}

Embed URL: {{ isset($match[1]) ? "https://www.youtube.com/embed/{$match[1]}?autoplay=1&mute=1&loop=1&playlist={$match[1]}&modestbranding=1&rel=0&iv_load_policy=3&controls=0" : 'INVALID' }}
                </div>
            </div>

            <div class="render-output">
                <h3>🎯 Live Preview:</h3>
                <p>This should show the actual video player:</p>
                <div style="border: 2px dashed #28a745; padding: 20px; margin: 10px 0; background: #f0fff0;">
                    <x-featured-video-player-no-session />
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
                <li>Check if the component renders any HTML output</li>
                <li>Verify the YouTube URL is valid and extractable</li>
                <li>Check browser console for JavaScript errors</li>
                <li>Inspect the DOM to see if elements are created</li>
            </ul>
        </div>
    </div>

    <script>
        // Log component status
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎬 Featured Video Render Test Loaded');
            
            // Check if component exists
            const container = document.querySelector('[id*="featured-video-container"]');
            if (container) {
                console.log('✅ Component container found:', container.id);
                console.log('Container classes:', container.className);
                console.log('Container styles:', container.style.cssText);
                
                const iframe = container.querySelector('iframe');
                if (iframe) {
                    console.log('✅ Iframe found, src:', iframe.src);
                } else {
                    console.log('❌ No iframe found');
                }
            } else {
                console.log('❌ No featured video container found');
            }
            
            // Check for any errors
            const errors = document.querySelectorAll('.error');
            if (errors.length > 0) {
                console.log('⚠️ Errors detected:', errors.length);
            }
        });
    </script>
</body>
</html>