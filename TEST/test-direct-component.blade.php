<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Component Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .warning { background-color: #fff3cd; color: #856404; }
        .render-output { background: #f8f9fa; border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .code { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 12px; overflow-x: auto; }
        .direct-test { border: 2px dashed #28a745; padding: 20px; margin: 20px 0; background: #f0fff0; }
        .manual-html { border: 2px solid #dc3545; padding: 20px; margin: 20px 0; background: #fff5f5; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔍 Direct Component Test</h1>
        
        <div class="status {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>Status:</strong> {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? '✅ Video Available' : '⚠️ No Video' }}
        </div>

        @if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
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

            <div class="direct-test">
                <h3>🎯 Direct Component Call:</h3>
                <p>Calling component directly:</p>
                <x-featured-video-player-no-session />
            </div>

            <div class="manual-html">
                <h3>🛠️ Manual HTML (if component fails):</h3>
                <p>Manual HTML implementation:</p>
                
                @php
                    // Extract video ID manually
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $randomFeaturedVideo->youtube_url, $match);
                    $videoId = $match[1] ?? null;
                    $isShort = str_contains($randomFeaturedVideo->youtube_url, '/shorts/');
                    $containerClass = $isShort ? 'is-short w-48 sm:w-56' : 'is-standard w-64 sm:w-80 md:w-96';
                    $playerClass = $isShort ? 'aspect-[9/16]' : 'aspect-video';
                    $embedUrl = isset($videoId) ? "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&playlist={$videoId}&modestbranding=1&rel=0&iv_load_policy=3&controls=0" : '';
                @endphp

                @if($videoId)
                <div id="manual-featured-video"
                    class="fixed bottom-4 left-4 z-[9999] {{ $containerClass }} shadow-2xl rounded-xl overflow-hidden border-2 border-white dark:border-border-secondary bg-black"
                    style="font-family: 'Poppins', sans-serif;">
                    
                    <div class="bg-accent/90 backdrop-blur-sm text-white px-3 py-1 flex items-center justify-between text-xs font-bold">
                        <span class="truncate pr-2">{{ $randomFeaturedVideo->title ?: 'Featured Video' }}</span>
                        <button onclick="document.getElementById('manual-featured-video').style.display='none'" class="hover:bg-accent-light p-1 rounded-full transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="{{ $playerClass }} relative bg-black">
                        <iframe
                            src="{{ $embedUrl }}"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                @else
                    <div class="error">
                        <p>❌ Could not extract valid YouTube video ID from URL</p>
                        <p>URL: {{ $randomFeaturedVideo->youtube_url }}</p>
                    </div>
                @endif
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
                <li>The manual HTML should always show if the component fails</li>
            </ul>
        </div>
    </div>

    <script>
        // Log component status
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎯 Direct Component Test Loaded');
            
            // Check if manual HTML exists
            const manualContainer = document.getElementById('manual-featured-video');
            if (manualContainer) {
                console.log('✅ Manual HTML container found');
                console.log('Container classes:', manualContainer.className);
                console.log('Container styles:', manualContainer.style.cssText);
                
                const iframe = manualContainer.querySelector('iframe');
                if (iframe) {
                    console.log('✅ Manual iframe found, src:', iframe.src);
                } else {
                    console.log('❌ No manual iframe found');
                }
            } else {
                console.log('❌ Manual HTML container not found');
            }
            
            // Check if component container exists
            const componentContainer = document.querySelector('[id*="featured-video-container"]');
            if (componentContainer) {
                console.log('✅ Component container found:', componentContainer.id);
            } else {
                console.log('❌ Component container not found');
            }
        });
    </script>
</body>
</html>