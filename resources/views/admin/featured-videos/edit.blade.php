@extends('layouts.app')

@section('title', 'Admin - Edit Featured Video')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.featured-videos.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                ← Back to List
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Edit Featured Video
        </h1>
        <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Update the YouTube link details
        </p>
    </div>

    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-8">
        <form action="{{ route('admin.featured-videos.update', $featuredVideo) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-bold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Video Title (Optional)
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $featuredVideo->title) }}" 
                    class="w-full px-4 py-2 border border-gray-300 dark:!border-border-secondary dark:!bg-bg-dark dark:!text-white rounded-lg focus:ring-accent focus:border-accent" 
                    placeholder="Enter video title">
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:!text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="youtube_url" class="block text-sm font-bold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    YouTube URL
                </label>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $featuredVideo->youtube_url) }}" required 
                            class="w-full px-4 py-2 border border-gray-300 dark:!border-border-secondary dark:!bg-bg-dark dark:!text-white rounded-lg focus:ring-accent focus:border-accent" 
                            placeholder="https://www.youtube.com/watch?v=...">
                        <p class="mt-1 text-xs text-gray-500">Supports standard, shorts, and embed URLs</p>
                        @error('youtube_url')
                            <p class="mt-1 text-sm text-red-600 dark:!text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div id="video_preview_container" class="hidden">
                        <div class="relative bg-black rounded-lg overflow-hidden shadow-lg">
                            <div id="video_preview_wrapper" class="w-[280px] aspect-video">
                                <iframe id="video_preview_iframe" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="absolute top-2 right-2 bg-black/70 text-white px-2 py-1 rounded text-xs">
                                <span id="video_type_label">Standard</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $featuredVideo->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-accent-light dark:peer-focus:ring-accent-dark rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-accent"></div>
                    <span class="ms-3 text-sm font-bold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        Is Active
                    </span>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.featured-videos.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-dark dark:!text-white dark:!hover:bg-gray-800" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Update Video
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const youtubeUrlInput = document.getElementById('youtube_url');
    const videoPreviewContainer = document.getElementById('video_preview_container');
    const videoPreviewIframe = document.getElementById('video_preview_iframe');
    const videoPreviewWrapper = document.getElementById('video_preview_wrapper');
    const videoTypeLabel = document.getElementById('video_type_label');

    function updateVideoPreview() {
        const url = youtubeUrlInput.value.trim();
        
        if (!url) {
            videoPreviewContainer.classList.add('hidden');
            return;
        }

        // Extract video ID from various YouTube URL formats
        let videoId = null;
        let isShort = false;

        // Check for shorts URL
        if (url.includes('/shorts/')) {
            const match = url.match(/\/shorts\/([a-zA-Z0-9_-]{11})/);
            if (match) {
                videoId = match[1];
                isShort = true;
            }
        }
        // Check for standard YouTube URLs
        else if (url.includes('youtube.com/watch')) {
            const match = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
            if (match) {
                videoId = match[1];
            }
        }
        // Check for youtu.be URLs
        else if (url.includes('youtu.be/')) {
            const match = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
            if (match) {
                videoId = match[1];
            }
        }
        // Check for embed URLs
        else if (url.includes('youtube.com/embed/')) {
            const match = url.match(/embed\/([a-zA-Z0-9_-]{11})/);
            if (match) {
                videoId = match[1];
            }
        }

        if (videoId) {
            // Update preview based on video type
            if (isShort) {
                videoPreviewWrapper.className = 'w-[180px] aspect-[9/16]';
                videoTypeLabel.textContent = 'Short';
            } else {
                videoPreviewWrapper.className = 'w-[280px] aspect-video';
                videoTypeLabel.textContent = 'Standard';
            }

            // Set iframe src with autoplay disabled for preview
            videoPreviewIframe.src = `https://www.youtube.com/embed/${videoId}`;
            videoPreviewContainer.classList.remove('hidden');
        } else {
            videoPreviewContainer.classList.add('hidden');
        }
    }

    // Add event listener for input changes
    if (youtubeUrlInput) {
        youtubeUrlInput.addEventListener('input', updateVideoPreview);
        // Initialize preview on page load
        updateVideoPreview();
    }
});
</script>
@endsection
