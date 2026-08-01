@props([
    'platform' => 'youtube',
    'embedUrl',
    'videoId',
    'width' => '100%',
    'aspectRatio' => '16by9',
    'autoplay' => false,
    'loop' => false,
    'muted' => false,
])

@php
$aspectClasses = [
    '16by9' => 'aspect-video',
    '4by3' => 'aspect-[4/3]',
    '21by9' => 'aspect-[21/9]',
    '1by1' => 'aspect-square',
];
$aspectClass = $aspectClasses[$aspectRatio] ?? 'aspect-video';
@endphp

<div class="video-embed-container w-full my-8" style="width: {{ $width }}">
    <div class="{{ $aspectClass }} w-full relative overflow-hidden rounded-lg bg-black shadow-lg">
        @if($platform === 'tiktok')
            {{-- TikTok embed - Force horizontal layout --}}
            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@user/video/{{ $videoId }}" data-video-id="{{ $videoId }}" style="max-width: 100%; min-width: 325px; width: 100%; height: 100%;">
                <section></section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        @elseif($platform === 'custom' || $platform === 'doodstream' || $platform === 'mixdrop' || $platform === 'voe' || $platform === 'filemoon')
            {{-- Custom iframe embed for Doodstream and other video hosts --}}
            <iframe
                src="{{ $embedUrl }}"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allowfullscreen
                scrolling="no"
                allow="encrypted-media; autoplay; fullscreen"
            ></iframe>
        @elseif($platform === 'instagram')
            {{-- Instagram embed - Force horizontal layout --}}
            <iframe 
                src="{{ $embedUrl }}"
                class="absolute inset-0 w-full h-full border-0"
                frameborder="0"
                scrolling="no"
                allowtransparency="true"
                allow="encrypted-media"
                style="min-height: {{ $aspectRatio === '16by9' ? '56.25vw' : '100%' }};"
            ></iframe>
        @elseif($platform === 'twitter')
            {{-- Twitter/X embed --}}
            <iframe 
                src="{{ $embedUrl }}"
                class="absolute inset-0 w-full h-full border-0"
                frameborder="0"
            ></iframe>
        @else
            {{-- Standard iframe for YouTube, Vimeo, Dailymotion, Facebook, Loom --}}
            <iframe
                src="{{ $embedUrl }}"
                class="absolute inset-0 w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                @if($autoplay) autoplay @endif
                @if($loop) loop @endif
                @if($muted) muted @endif
            ></iframe>
        @endif
    </div>
    
    @if($platform === 'youtube')
        <p class="text-xs text-gray-500 mt-2 text-center">
            <a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank" rel="noopener noreferrer" class="text-accent hover:underline">
                Watch on YouTube
            </a>
        </p>
    @elseif($platform === 'vimeo')
        <p class="text-xs text-gray-500 mt-2 text-center">
            <a href="https://vimeo.com/{{ $videoId }}" target="_blank" rel="noopener noreferrer" class="text-accent hover:underline">
                Watch on Vimeo
            </a>
        </p>
    @endif
</div>

@push('styles')
<style>
    .video-embed-container {
        position: relative;
        margin: 2rem 0;
    }
    
    .video-embed-container iframe,
    .video-embed-container .tiktok-embed {
        transition: opacity 0.3s ease;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .video-embed-container:hover iframe,
    .video-embed-container:hover .tiktok-embed {
        opacity: 0.95;
    }
    
    /* Force horizontal/landscape orientation for all video embeds */
    .video-embed-container .aspect-video {
        aspect-ratio: 16 / 9;
    }
    
    /* TikTok specific overrides for horizontal layout */
    .video-embed-container blockquote.tiktok-embed {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 325px !important;
        height: 100% !important;
        margin: 0 auto;
    }
    
    /* Instagram embed container fix */
    .video-embed-container iframe[src*="instagram.com"] {
        min-height: 56.25vw; /* 16:9 aspect ratio */
    }
</style>
@endpush
