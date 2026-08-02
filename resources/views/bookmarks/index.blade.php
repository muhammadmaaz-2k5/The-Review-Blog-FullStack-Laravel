@extends('layouts.app')

@section('title', 'My Bookmarks - Nazaara Circle')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                My Bookmarks
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Your saved articles for later reading
            </p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            ← Dashboard
        </a>
    </div>

    <!-- Search Bar -->
    @if($bookmarks->count() > 0)
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('bookmarks.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bookmarks..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('bookmarks.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            @endif
        </form>
    </div>
    @endif

    <!-- Bookmarks Grid -->
    @if($bookmarks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($bookmarks as $bookmark)
                <article class="group relative bg-white overflow-hidden cursor-pointer dark:!bg-bg-card transition-all duration-300 rounded-lg border border-gray-200 dark:!border-border-secondary hover:shadow-lg">
                    <a href="{{ route('articles.show', $bookmark->article->slug) }}" class="block">
                        <!-- Featured Image -->
                        <div class="relative overflow-hidden w-full aspect-video bg-gray-200 dark:bg-gray-800">
                            @if($bookmark->article->featured_image)
                                <img src="{{ $bookmark->article->featured_image_url }}" 
                                     alt="{{ $bookmark->article->featured_image_alt ?: $bookmark->article->title }}" 
                                     title="{{ $bookmark->article->featured_image_title ?: $bookmark->article->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                     onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}'">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-700 dark:to-gray-800">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Bookmark Badge -->
                            <div class="absolute top-2 right-2 p-2 bg-yellow-500 rounded-full">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </div>
                            
                            <!-- Category Badge -->
                            @if($bookmark->article->category)
                            <div class="absolute top-2 left-2 bg-accent text-white px-3 py-1 rounded-full text-xs font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600; z-index: 3; backdrop-filter: blur(4px); background-color: rgba(229, 9, 20, 0.9);">
                                {{ $bookmark->article->category->name }}
                            </div>
                            @endif
                        </div>
                        
                        <!-- Article Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 line-clamp-2 group-hover:text-accent transition-colors duration-300" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                {{ $bookmark->article->title }}
                            </h3>
                            
                            @if($bookmark->article->excerpt)
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-3 line-clamp-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ Str::limit($bookmark->article->excerpt, 100) }}
                                </p>
                            @endif
                            
                            @if($bookmark->notes)
                                <div class="mb-3 p-2 bg-yellow-50 dark:!bg-yellow-900/10 rounded border border-yellow-200 dark:!border-yellow-800">
                                    <p class="text-xs text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        <strong>Your note:</strong> {{ Str::limit($bookmark->notes, 80) }}
                                    </p>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <span>Bookmarked {{ $bookmark->created_at->diffForHumans() }}</span>
                                <span>👁 {{ number_format($bookmark->article->views) }}</span>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Remove Bookmark Button -->
                    <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST" class="absolute bottom-4 right-4" onclick="event.stopPropagation();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to remove this bookmark?');"
                                class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-full transition-colors shadow-lg"
                                title="Remove bookmark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </form>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($bookmarks->hasPages())
        <div class="mt-8">
            {{ $bookmarks->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-16 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                No Bookmarks Yet
            </h2>
            <p class="text-gray-600 dark:!text-text-secondary mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Start bookmarking articles you want to read later!
            </p>
            <a href="{{ route('articles.index') }}" class="inline-block px-6 py-3 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Browse Articles
            </a>
        </div>
    @endif
</div>
@endsection

