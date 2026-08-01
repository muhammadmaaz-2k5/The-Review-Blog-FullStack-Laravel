@extends('layouts.app')

@section('title', 'Admin - Featured Videos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Featured Videos
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage floating YouTube videos
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.featured-videos.create') }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Add New Video
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($videos as $video)
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 hover:shadow-lg transition-shadow">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ $video->title ?: 'Untitled Video' }}
                    </h3>
                    <p class="text-xs text-gray-500 truncate mb-3">
                        {{ $video->youtube_url }}
                    </p>
                    
                    @php
                        // Extract video ID for preview
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->youtube_url, $match);
                        $videoId = $match[1] ?? null;
                    @endphp

                    @if($videoId)
                        <div class="aspect-video bg-gray-100 rounded overflow-hidden mb-4">
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-1">Views</p>
                        <p class="font-bold text-gray-900 dark:!text-white">{{ number_format($video->views) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-1">Status</p>
                        <form action="{{ route('admin.featured-videos.toggle-status', $video) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2 py-1 rounded text-xs {{ $video->is_active ? 'bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400' : 'bg-gray-100 text-gray-800 dark:!bg-gray-800 dark:!text-gray-400' }}">
                                {{ $video->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:!border-border-secondary">
                    <a href="{{ route('admin.featured-videos.edit', $video) }}" class="flex-1 px-4 py-2 bg-accent hover:bg-accent-light text-white text-center rounded-lg transition-colors text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Edit
                    </a>
                    <form action="{{ route('admin.featured-videos.destroy', $video) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-600 dark:!text-text-secondary">No videos found.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $videos->links() }}
    </div>
</div>
@endsection
