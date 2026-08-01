@extends('layouts.app')

@section('title', 'Test Featured Video - Final')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:!text-white mb-6">🎯 Final Featured Video Test</h1>
        
        <div class="mb-6 p-4 bg-blue-50 dark:!bg-blue-900/20 border border-blue-200 dark:!border-blue-800 rounded-lg">
            <h2 class="text-lg font-semibold text-blue-900 dark:!text-blue-100 mb-2">📍 Check Bottom-Left Corner</h2>
            <p class="text-blue-700 dark:!text-blue-300">The featured video should appear in the bottom-left corner of this page. Look for a floating video player.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 dark:!bg-gray-800 p-6 rounded-lg">
                <h3 class="font-semibold mb-3">🔧 System Status</h3>
                <ul class="space-y-2 text-sm">
                    <li>✅ ViewComposer: {{ class_exists('\App\View\Composers\FeaturedVideoSafeComposer') ? 'Active' : 'Inactive' }}</li>
                    <li>✅ Component: {{ isset($randomFeaturedVideo) && $randomFeaturedVideo ? 'Video Found' : 'No Video' }}</li>
                    <li>✅ Layout Integration: Component included in app.blade.php</li>
                </ul>
            </div>
            
            <div class="bg-green-50 dark:!bg-green-900/20 p-6 rounded-lg">
                <h3 class="font-semibold mb-3">🎬 Current Video</h3>
                @if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
                    <div class="text-sm space-y-1">
                        <p><strong>Title:</strong> {{ $randomFeaturedVideo->title ?: 'Untitled' }}</p>
                        <p><strong>Type:</strong> {{ str_contains($randomFeaturedVideo->youtube_url, '/shorts/') ? 'YouTube Short' : 'Standard Video' }}</p>
                        <p><strong>URL:</strong> <a href="{{ $randomFeaturedVideo->youtube_url }}" target="_blank" class="text-blue-600 hover:underline">{{ Str::limit($randomFeaturedVideo->youtube_url, 40) }}</a></p>
                        <p><strong>Status:</strong> {{ $randomFeaturedVideo->is_active ? '✅ Active' : '❌ Inactive' }}</p>
                    </div>
                @else
                    <p class="text-red-600">No featured video available</p>
                @endif
            </div>
        </div>

        <div class="bg-yellow-50 dark:!bg-yellow-900/20 p-6 rounded-lg mb-6">
            <h3 class="font-semibold mb-3">🔍 Debug Information</h3>
            <div class="text-sm space-y-1">
                <p><strong>Session Storage:</strong> <span id="session-status"></span></p>
                <p><strong>Component Status:</strong> <span id="component-status"></span></p>
                <p><strong>Video Container:</strong> <span id="container-status"></span></p>
            </div>
        </div>

        <div class="flex flex-wrap gap-4">
            <button onclick="checkFeaturedVideo()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                🔍 Check Video Status
            </button>
            <button onclick="clearSessionStorage()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                🗑️ Clear Session Storage
            </button>
            <button onclick="forceShowVideo()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                👁️ Force Show Video
            </button>
            <a href="{{ route('admin.featured-videos.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center">
                ⚙️ Admin Panel
            </a>
        </div>

        <div id="debug-output" class="mt-6 p-4 bg-gray-100 dark:!bg-gray-800 rounded-lg hidden">
            <h4 class="font-semibold mb-2">📝 Debug Output:</h4>
            <div id="debug-messages" class="text-sm font-mono text-gray-700 dark:!text-gray-300"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function log(message) {
    const debugMessages = document.getElementById('debug-messages');
    const debugOutput = document.getElementById('debug-output');
    const timestamp = new Date().toLocaleTimeString();
    debugMessages.innerHTML += `[${timestamp}] ${message}<br>`;
    debugOutput.classList.remove('hidden');
    console.log(`[Featured Video Debug] ${message}`);
}

function checkFeaturedVideo() {
    log('=== Checking Featured Video Status ===');
    
    // Check session storage
    const sessionClosed = sessionStorage.getItem('featuredVideoClosed');
    document.getElementById('session-status').textContent = sessionClosed ? `Closed (${sessionClosed})` : 'Open';
    log(`Session Storage: featuredVideoClosed = ${sessionClosed}`);
    
    // Check if component exists
    const component = document.querySelector('[id*="featured-video-container"]');
    document.getElementById('component-status').textContent = component ? 'Found' : 'Not Found';
    log(`Component Element: ${component ? 'Found' : 'Not Found'}`);
    
    if (component) {
        log(`Component ID: ${component.id}`);
        log(`Component Classes: ${component.className}`);
        log(`Computed Display: ${window.getComputedStyle(component).display}`);
        log(`Computed Visibility: ${window.getComputedStyle(component).visibility}`);
        log(`Computed Opacity: ${window.getComputedStyle(component).opacity}`);
        log(`Computed Transform: ${window.getComputedStyle(component).transform}`);
        log(`Computed Z-Index: ${window.getComputedStyle(component).zIndex}`);
        
        // Check iframe
        const iframe = component.querySelector('iframe');
        if (iframe) {
            log(`Iframe Found: src = ${iframe.src}`);
        } else {
            log('❌ Iframe NOT found in component');
        }
    }
    
    // Check for all possible container IDs
    const possibleIds = ['featured-video-container', 'featured-video-container-test', 'featured-video-container-always-show'];
    possibleIds.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            log(`✅ Found container with ID: ${id}`);
        }
    });
}

function clearSessionStorage() {
    log('Clearing session storage...');
    sessionStorage.removeItem('featuredVideoClosed');
    log('✅ Session storage cleared');
    
    // Try to show any hidden video containers
    const containers = document.querySelectorAll('[id*="featured-video-container"]');
    containers.forEach(container => {
        container.style.display = 'block';
        container.style.visibility = 'visible';
        container.style.opacity = '1';
        container.style.transform = 'translateX(0)';
        log(`🔄 Attempted to show container: ${container.id}`);
    });
    
    setTimeout(() => {
        log('🔄 Page will refresh in 2 seconds...');
        setTimeout(() => location.reload(), 2000);
    }, 500);
}

function forceShowVideo() {
    log('Forcing video to show...');
    
    // Find and show all video containers
    const containers = document.querySelectorAll('[id*="featured-video-container"]');
    if (containers.length === 0) {
        log('❌ No video containers found');
        return;
    }
    
    containers.forEach(container => {
        // Force visibility
        container.style.display = 'block !important';
        container.style.visibility = 'visible !important';
        container.style.opacity = '1 !important';
        container.style.transform = 'translateX(0) !important';
        container.style.zIndex = '9999 !important';
        
        log(`✅ Forced show: ${container.id}`);
        
        // Check iframe
        const iframe = container.querySelector('iframe');
        if (iframe && iframe.src) {
            log(`🎬 Video URL: ${iframe.src}`);
        }
    });
    
    log('✅ All video containers should now be visible');
}

// Run initial check
document.addEventListener('DOMContentLoaded', function() {
    log('=== Page Loaded ===');
    
    // Wait for everything to load
    setTimeout(() => {
        checkFeaturedVideo();
        
        // Check if we're on the main layout
        const isMainLayout = document.querySelector('footer') !== null;
        log(`Main layout detected: ${isMainLayout}`);
        
        // Check for bottom navigation
        const bottomNav = document.getElementById('bottomNav');
        if (bottomNav) {
            log('📱 Bottom navigation detected - video should be positioned above it');
        }
        
    }, 2000);
});
</script>
@endpush