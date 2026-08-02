<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Component Output Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .warning { background-color: #fff3cd; color: #856404; }
        .code { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 12px; overflow-x: auto; white-space: pre-wrap; }
        .output-section { background: #f8f9fa; border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .html-output { background: #f0f8ff; border: 2px dashed #28a745; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .comparison { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔍 Component Output Analysis</h1>
        
        <div class="status {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? 'success' : 'warning' }}">
            <strong>Status:</strong> {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? '✅ Video Available' : '⚠️ No Video' }}
        </div>

        @if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
            @php
                // Extract video data
                $isShort = str_contains($randomFeaturedVideo->youtube_url, '/shorts/');
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $randomFeaturedVideo->youtube_url, $match);
                $videoId = $match[1] ?? null;
                $containerClass = $isShort ? 'is-short w-48 sm:w-56' : 'is-standard w-64 sm:w-80 md:w-96';
                $playerClass = $isShort ? 'aspect-[9/16]' : 'aspect-video';
                $embedUrl = isset($videoId) ? "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&playlist={$videoId}&modestbranding=1&rel=0&iv_load_policy=3&controls=0" : '';
            @endphp

            <div class="output-section">
                <h3>📋 Video Data Analysis:</h3>
                <div class="code">
Video Object:
{{ json_encode($randomFeaturedVideo->toArray(), JSON_PRETTY_PRINT) }}

Extracted Video ID: {{ $videoId ?: 'NOT FOUND' }}
Is YouTube Short: {{ $isShort ? 'YES' : 'NO' }}
Container Class: {{ $containerClass }}
Player Class: {{ $playerClass }}
Embed URL: {{ $embedUrl ?: 'INVALID' }}
                </div>
            </div>

            <div class="comparison">
                <div class="output-section">
                    <h3>🔧 Component Render Output:</h3>
                    <p>What the Laravel component actually generates:</p>
                    <div class="code">
@php
    try {
        $componentOutput = view('components.featured-video-player-no-session', ['randomFeaturedVideo' => $randomFeaturedVideo])->render();
        echo htmlentities($componentOutput);
    } catch (\Exception $e) {
        echo "❌ COMPONENT RENDER ERROR:\n" . $e->getMessage() . "\n\n";
        echo "Stack trace:\n" . $e->getTraceAsString();
    }
@endphp
                    </div>
                </div>

                <div class="output-section">
                    <h3>🛠️ Manual HTML Equivalent:</h3>
                    <p>What the HTML should look like:</p>
                    <div class="code">
@if($videoId)
&lt;div id="featured-video-container"
    class="fixed bottom-4 left-4 z-[9999] {{ $containerClass }} shadow-2xl rounded-xl overflow-hidden border-2 border-white dark:border-border-secondary bg-black transition-all duration-300 transform translate-x-0"
    style="font-family: 'Poppins', sans-serif;"&gt;

    &lt;!-- Header --&gt;
    &lt;div class="bg-accent/90 backdrop-blur-sm text-white px-3 py-1 flex items-center justify-between text-xs font-bold"&gt;
        &lt;span class="truncate pr-2"&gt;{{ $randomFeaturedVideo->title ?: 'Featured Video' }}&lt;/span&gt;
        &lt;button onclick="closeFeaturedVideoNoSession()" class="hover:bg-accent-light p-1 rounded-full transition-colors"&gt;
            &lt;svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"&gt;&lt;path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /&gt;&lt;/svg&gt;
        &lt;/button&gt;
    &lt;/div&gt;

    &lt;!-- Video Player --&gt;
    &lt;div class="{{ $playerClass }} relative bg-black"&gt;
        &lt;iframe
            id="featured-video-iframe"
            src="{{ $embedUrl }}"
            class="absolute inset-0 w-full h-full"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen&gt;
        &lt;/iframe&gt;
    &lt;/div&gt;
&lt;/div&gt;
@else
&lt;div style="color: red;"&gt;❌ INVALID VIDEO ID&lt;/div&gt;
@endif
                    </div>
                </div>
            </div>

            <div class="html-output">
                <h3>🎯 Live Preview:</h3>
                <p>This should show the actual rendered component:</p>
                <div style="border: 2px solid #28a745; padding: 20px; background: #f0fff0;">
                    <x-featured-video-player-no-session />
                </div>
            </div>

            <div class="html-output">
                <h3>🛠️ Manual HTML Live Preview:</h3>
                <p>This should show the manual HTML version:</p>
                <div style="border: 2px solid #dc3545; padding: 20px; background: #fff5f5;">
                    @if($videoId)
                    <div id="manual-featured-video"
                        class="fixed bottom-4 left-4 z-[9999] {{ $containerClass }} shadow-2xl rounded-xl overflow-hidden border-2 border-white dark:border-border-secondary bg-black transition-all duration-300 transform translate-x-0"
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
                    <div style="color: red;">❌ Cannot render - invalid video ID</div>
                    @endif
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
                <li>Compare the component output vs manual HTML</li>
                <li>Check if the component renders any HTML at all</li>
                <li>Verify the YouTube URL extraction works</li>
                <li>Test both versions to see which one works</li>
            </ul>
        </div>
    </div>

    <script>
        // Log component status
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔍 Component Output Analysis Loaded');
            
            // Check if any video containers exist
            const containers = document.querySelectorAll('[id*="featured-video-container"]');
            console.log(`Found ${containers.length} video containers`);
            
            containers.forEach((container, index) => {
                console.log(`Container ${index + 1}:`, container.id);
                console.log(`Container ${index + 1} classes:`, container.className);
                console.log(`Container ${index + 1} styles:`, container.style.cssText);
                
                const iframe = container.querySelector('iframe');
                if (iframe) {
                    console.log(`Container ${index + 1} iframe src:`, iframe.src);
                }
            });
            
            // Check for manual HTML
            const manualContainer = document.getElementById('manual-featured-video');
            if (manualContainer) {
                console.log('✅ Manual HTML container found');
            } else {
                console.log('❌ Manual HTML container not found');
            }
        });
    </script>
</body>
</html>