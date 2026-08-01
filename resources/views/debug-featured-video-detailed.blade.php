<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Debug Featured Video</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .debug-container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; border-left: 4px solid; }
        .success { background-color: #d4edda; color: #155724; border-color: #28a745; }
        .error { background-color: #f8d7da; color: #721c24; border-color: #dc3545; }
        .warning { background-color: #fff3cd; color: #856404; border-color: #ffc107; }
        .info { background-color: #d1ecf1; color: #0c5460; border-color: #17a2b8; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 12px; margin: 10px 0; white-space: pre-wrap; }
        .test-section { margin: 20px 0; padding: 20px; border: 2px dashed #007bff; border-radius: 10px; background: #f8f9ff; }
        .variable-dump { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px; overflow-x: auto; }
        .step { margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .step-number { background: #007bff; color: white; padding: 5px 10px; border-radius: 50%; font-weight: bold; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="debug-container">
        <h1>🔍 Detailed Featured Video Debug</h1>
        
        <div class="step">
            <span class="step-number">1</span>
            <strong>Checking if randomFeaturedVideo variable exists:</strong>
            <div class="variable-dump">
@if(isset($randomFeaturedVideo))
✅ Variable exists
@isset($randomFeaturedVideo)
✅ Variable is set and not null
@else
❌ Variable is null or not set
@endisset
@else
❌ Variable does not exist at all
@endif
            </div>
        </div>

        <div class="step">
            <span class="step-number">2</span>
            <strong>Variable type and content:</strong>
            <div class="variable-dump">
@php
    if (isset($randomFeaturedVideo)) {
        if (is_object($randomFeaturedVideo)) {
            echo "Type: " . get_class($randomFeaturedVideo) . "\n";
            if (method_exists($randomFeaturedVideo, 'toArray')) {
                echo "Content: " . json_encode($randomFeaturedVideo->toArray(), JSON_PRETTY_PRINT);
            } else {
                echo "Object properties: " . json_encode(get_object_vars($randomFeaturedVideo), JSON_PRETTY_PRINT);
            }
        } elseif (is_array($randomFeaturedVideo)) {
            echo "Type: Array\n";
            echo "Content: " . json_encode($randomFeaturedVideo, JSON_PRETTY_PRINT);
        } else {
            echo "Type: " . gettype($randomFeaturedVideo) . "\n";
            echo "Value: " . var_export($randomFeaturedVideo, true);
        }
    } else {
        echo "Variable is not set or is null";
    }
@endphp
            </div>
        </div>

        <div class="step">
            <span class="step-number">3</span>
            <strong>Checking component rendering:</strong>
            <div class="test-section">
                <h4>🎬 Component Test (should show video if available):</h4>
                <div style="border: 2px solid #007bff; padding: 20px; margin: 10px 0;">
                    <x-featured-video-player />
                </div>
                
                <h4>🔍 Raw Component Output:</h4>
                <div class="code">
@php
    // Try to render the component and capture output
    try {
        $output = view('components.featured-video-player', ['randomFeaturedVideo' => $randomFeaturedVideo ?? null])->render();
        echo "Component rendered successfully:\n";
        echo htmlentities($output);
    } catch (\Exception $e) {
        echo "Component rendering failed: " . $e->getMessage() . "\n";
        echo "Exception: " . $e->getTraceAsString();
    }
@endphp
                </div>
            </div>
        </div>

        <div class="step">
            <span class="step-number">4</span>
            <strong>Checking ViewComposer registration:</strong>
            <div class="info">
                <p><strong>Current Composer Class:</strong> {{ class_exists('\App\View\Composers\FeaturedVideoSafeComposer') ? '✅ FeaturedVideoSafeComposer exists' : '❌ FeaturedVideoSafeComposer not found' }}</p>
                <p><strong>Alternative Composer:</strong> {{ class_exists('\App\View\Composers\FeaturedVideoComposer') ? '✅ FeaturedVideoComposer exists' : '❌ FeaturedVideoComposer not found' }}</p>
                <p><strong>Fallback Composer:</strong> {{ class_exists('\App\View\Composers\FeaturedVideoFallbackComposer') ? '✅ FeaturedVideoFallbackComposer exists' : '❌ FeaturedVideoFallbackComposer not found' }}</p>
            </div>
        </div>

        <div class="step">
            <span class="step-number">5</span>
            <strong>Checking cache status:</strong>
            <div class="info">
                <p><strong>Cache Driver:</strong> {{ config('cache.default') }}</p>
                <p><strong>Cache Key Exists:</strong> {{ cache()->has('random_featured_video') ? '✅ Yes' : '❌ No' }}</p>
                <p><strong>Cache Key Value:</strong></p>
                <div class="variable-dump">
@php
    if (cache()->has('random_featured_video')) {
        $cached = cache()->get('random_featured_video');
        if (is_object($cached)) {
            echo "Cached object: " . (method_exists($cached, 'toArray') ? json_encode($cached->toArray(), JSON_PRETTY_PRINT) : json_encode(get_object_vars($cached), JSON_PRETTY_PRINT));
        } else {
            echo "Cached value: " . var_export($cached, true);
        }
    } else {
        echo "No cached value found";
    }
@endphp
                </div>
            </div>
        </div>

        <div class="step">
            <span class="step-number">6</span>
            <strong>Manual database check:</strong>
            <div class="info">
                <p><strong>Direct Database Query:</strong></p>
                <div class="variable-dump">
@php
    try {
        $directQuery = App\Models\FeaturedVideo::where('is_active', true)->first();
        if ($directQuery) {
            echo "✅ Direct query found video:\n";
            echo json_encode($directQuery->toArray(), JSON_PRETTY_PRINT);
        } else {
            echo "❌ Direct query found no active videos";
        }
    } catch (\Exception $e) {
        echo "❌ Direct query failed: " . $e->getMessage();
    }
@endphp
                </div>
            </div>
        </div>

        <div class="step">
            <span class="step-number">7</span>
            <strong>Checking layout inclusion:</strong>
            <div class="info">
                <p>The featured video component should be included in app.blade.php at line ~2454</p>
                <p>Check if this HTML appears in your page source:</p>
                <div class="code">&lt;x-featured-video-player /&gt;</div>
            </div>
        </div>

        <div class="test-section">
            <h3>🧪 Manual Component Test:</h3>
            <p>Testing component with a sample video:</p>
            @php
                $sampleVideo = new \stdClass();
                $sampleVideo->title = "Sample Test Video";
                $sampleVideo->youtube_url = "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
                $sampleVideo->is_active = true;
            @endphp
            
            <div style="border: 2px solid #28a745; padding: 20px; margin: 10px 0;">
                <x-featured-video-player :randomFeaturedVideo="$sampleVideo" />
            </div>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>🚀 Quick Actions:</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <a href="{{ url('/') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">🏠 Homepage</a>
                <a href="{{ route('admin.featured-videos.index') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">⚙️ Admin Panel</a>
                <a href="javascript:location.reload()" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">🔄 Refresh</a>
                <a href="/debug-featured-video" style="background-color: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">🔍 Simple Debug</a>
            </div>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
            <h4>💡 Next Steps:</h4>
            <ul>
                <li>If variable is null: Check database connection and ensure active videos exist</li>
                <li>If component fails to render: Check Laravel logs for errors</li>
                <li>If cache issues: Clear cache with <code>php artisan cache:clear</code></li>
                <li>If database connection fails: Check .env configuration</li>
            </ul>
        </div>
    </div>
</body>
</html>