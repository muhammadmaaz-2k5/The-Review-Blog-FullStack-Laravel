<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Featured Video JS</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .debug-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .status { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background-color: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .error { background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .warning { background-color: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .info { background-color: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px; margin: 10px 0; }
        .video-test { border: 2px dashed #007bff; padding: 20px; margin: 20px 0; background: #f8f9ff; }
    </style>
</head>
<body>
    <div class="debug-container">
        <h1>🔍 JavaScript Debug - Featured Video</h1>
        
        <div class="info">
            <h3>📊 Session Storage Check:</h3>
            <div id="session-storage-info" class="code"></div>
        </div>

        <div class="info">
            <h3>🎬 Component Test:</h3>
            <p>Testing the featured video component:</p>
            
            <div class="video-test">
                <x-featured-video-player />
            </div>
        </div>

        <div class="info">
            <h3>🔧 JavaScript Console:</h3>
            <div id="js-console" class="code"></div>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>🚀 Actions:</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <button onclick="clearSessionStorage()" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">🗑️ Clear Session Storage</button>
                <button onclick="location.reload()" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">🔄 Refresh Page</button>
                <a href="{{ url('/') }}" style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 Go to Homepage</a>
            </div>
        </div>
    </div>

    <script>
        // Debug function to log to the page
        function logToPage(message) {
            const consoleDiv = document.getElementById('js-console');
            const timestamp = new Date().toLocaleTimeString();
            consoleDiv.innerHTML += `[${timestamp}] ${message}<br>`;
        }

        // Check session storage
        function checkSessionStorage() {
            const sessionInfo = document.getElementById('session-storage-info');
            const featuredVideoClosed = sessionStorage.getItem('featuredVideoClosed');
            
            sessionInfo.innerHTML = `
                <strong>featuredVideoClosed:</strong> ${featuredVideoClosed || 'null'}<br>
                <strong>Type:</strong> ${typeof featuredVideoClosed}<br>
                <strong>Will hide video:</strong> ${featuredVideoClosed === 'true' ? 'YES' : 'NO'}
            `;
            
            logToPage(`Session storage check: featuredVideoClosed = ${featuredVideoClosed}`);
            return featuredVideoClosed === 'true';
        }

        // Clear session storage
        function clearSessionStorage() {
            sessionStorage.removeItem('featuredVideoClosed');
            logToPage('Session storage cleared! Refreshing page...');
            setTimeout(() => location.reload(), 1000);
        }

        // Check if featured video container exists
        function checkFeaturedVideo() {
            const container = document.getElementById('featured-video-container');
            if (container) {
                logToPage(`Featured video container found: ${container.style.display || 'visible'}`);
                logToPage(`Container classes: ${container.className}`);
                logToPage(`Container transform: ${container.style.transform}`);
                
                // Check if it's hidden
                const computedStyle = window.getComputedStyle(container);
                logToPage(`Computed display: ${computedStyle.display}`);
                logToPage(`Computed visibility: ${computedStyle.visibility}`);
                logToPage(`Computed opacity: ${computedStyle.opacity}`);
            } else {
                logToPage('❌ Featured video container NOT found in DOM');
            }
        }

        // Run checks when page loads
        document.addEventListener('DOMContentLoaded', function() {
            logToPage('=== Page Loaded ===');
            
            // Check session storage
            const shouldHide = checkSessionStorage();
            
            // Wait a bit for DOM to settle
            setTimeout(() => {
                checkFeaturedVideo();
                
                // Check if iframe exists
                const iframe = document.getElementById('featured-video-iframe');
                if (iframe) {
                    logToPage(`✅ iframe found, src: ${iframe.src}`);
                } else {
                    logToPage('❌ iframe NOT found');
                }
                
                // Check if component was removed by sessionStorage
                if (shouldHide) {
                    logToPage('⚠️ Video was hidden due to sessionStorage');
                }
            }, 1000);
        });

        // Override the close function for testing
        window.closeFeaturedVideo = function() {
            logToPage('🚫 closeFeaturedVideo() called - preventing video from being hidden');
            // Don't actually close it for testing
        };
    </script>
</body>
</html>