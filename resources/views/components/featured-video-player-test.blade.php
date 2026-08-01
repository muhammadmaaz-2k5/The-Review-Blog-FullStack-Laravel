@if(isset($randomFeaturedVideo) && $randomFeaturedVideo)
    @php
        // Check if it's a YouTube Short
        $isShort = str_contains($randomFeaturedVideo->youtube_url, '/shorts/');

        // Extract Video ID
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $randomFeaturedVideo->youtube_url, $match);
        $videoId = $match[1] ?? null;

        // Determine container classes based on video type
        $containerClass = $isShort
            ? 'is-short w-48 sm:w-56' // Narrower for shorts
            : 'is-standard w-64 sm:w-80 md:w-96'; // Wider for standard videos

        // Determine player wrapper classes
        $playerClass = $isShort ? 'aspect-[9/16]' : 'aspect-video';

        // Build the embed URL
        $embedUrl = "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=0&loop=1&playlist={$videoId}&modestbranding=1&rel=0&iv_load_policy=3&controls=1";
    @endphp

    @if($videoId)
    <div id="featured-video-container-test"
        class="fixed bottom-4 left-4 z-[9999] {{ $containerClass }} shadow-2xl rounded-xl overflow-hidden border-2 border-white dark:border-border-secondary bg-black transition-all duration-300 transform translate-x-0"
        style="font-family: 'Poppins', sans-serif;">

        <!-- Header -->
        <div class="bg-accent/90 backdrop-blur-sm text-white px-3 py-1 flex items-center justify-between text-xs font-bold">
            <span class="truncate pr-2">{{ $randomFeaturedVideo->title ?: 'Featured Video' }}</span>
            <button onclick="closeTestFeaturedVideo()" class="hover:bg-accent-light p-1 rounded-full transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Video Player -->
        <div class="{{ $playerClass }} relative bg-black">
            <iframe
                id="featured-video-iframe-test"
                src="{{ $embedUrl }}"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>
        </div>
    </div>

    <script>
        function closeTestFeaturedVideo() {
            const container = document.getElementById('featured-video-container-test');
            if (container) {
                container.style.transform = 'translateX(-120%)';
                setTimeout(() => container.remove(), 300);
            }
        }

        // Log that the test component is loaded
        console.log('🎬 Featured Video Test Component Loaded!');
        console.log('Video ID:', '{{ $videoId }}');
        console.log('Is Short:', '{{ $isShort ? "Yes" : "No" }}');
        console.log('Container Class:', '{{ $containerClass }}');
        console.log('Player Class:', '{{ $playerClass }}');
    </script>

    <style>
        /* Mobile-specific adjustments */
        @media (max-width: 640px) {
            #featured-video-container-test {
                /* Position above the bottom navigation bar */
                bottom: 5.5rem; /* 4.5rem nav + 1rem spacing */
                left: 0.5rem;
                border-width: 1px;
            }

            /* Adjust width for shorts vs standard on mobile */
            #featured-video-container-test.is-short {
                width: 140px; /* Even smaller for portrait video */
            }
            #featured-video-container-test.is-standard {
                width: 220px; /* Keep standard videos wider */
            }
        }
    </style>
    @endif
@else
    <script>
        console.log('❌ Featured Video Test: No video available or variable not set');
    </script>
@endif