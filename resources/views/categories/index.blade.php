@extends('layouts.app')

@section('title', 'Topics & Themes - Nazaara Circle')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 dark:!text-white mb-4 uppercase tracking-tight flex items-center gap-3" style="font-family: 'Poppins', sans-serif;">
                <span class="w-2 h-10 md:h-12 bg-accent rounded-full"></span>
                Explore Topics
            </h1>
            <p class="text-lg text-gray-600 dark:!text-text-secondary font-medium max-w-2xl">
                Dive into our collection of entertainment news, reviews, and features organized by category.
            </p>
        </div>
        
        <!-- Search Toggle / Simple Search -->
        <div class="w-full md:w-auto">
            <form method="GET" action="{{ route('categories.index') }}" class="relative group">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Find a category..." 
                       class="w-full md:w-80 px-5 py-3 rounded-full bg-gray-100 dark:!bg-bg-card border-2 border-transparent focus:border-accent focus:bg-white dark:!focus:bg-bg-card-hover focus:outline-none transition-all dark:!text-white font-medium pl-12 shadow-sm"
                >
                <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 group-focus-within:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>
    </div>

    <!-- Featured Categories (Hero Grid) -->
    @if($featuredCategories->count() > 0)
    <div class="mb-16">
        <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-6 flex items-center gap-2 uppercase tracking-wider" style="font-family: 'Poppins', sans-serif;">
            <span class="text-accent">★</span> Featured Collections
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredCategories as $featured)
                <a href="{{ route('categories.show', $featured->slug) }}" class="group relative h-64 md:h-80 rounded-2xl overflow-hidden shadow-2xl block">
                    <!-- Background -->
                    @if($featured->image)
                        <img src="{{ $featured->image_url }}" 
                             alt="{{ $featured->name }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-black"></div>
                    @endif
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    
                    <!-- Content -->
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <span class="inline-block px-3 py-1 bg-accent text-white text-xs font-bold rounded-full mb-3 uppercase tracking-wider shadow-lg">
                                {{ number_format($featured->articles_count) }} Stories
                            </span>
                            <h3 class="text-3xl font-black text-white mb-2 leading-tight uppercase" style="font-family: 'Poppins', sans-serif; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                {{ $featured->name }}
                            </h3>
                            <p class="text-gray-200 text-sm line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100 font-medium">
                                {{ $featured->description }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filter Bar -->
    <div class="sticky top-20 z-30 bg-white/90 dark:!bg-bg-primary/90 backdrop-blur-md border-y border-gray-100 dark:!border-border-secondary py-4 mb-10 -mx-4 px-4 sm:mx-0 sm:px-0 sm:rounded-xl sm:border sm:shadow-sm">
        <form method="GET" action="{{ route('categories.index') }}" class="flex flex-wrap items-center justify-between gap-4 max-w-[1400px] mx-auto">
            @if($search)
                <input type="hidden" name="search" value="{{ $search }}">
            @endif
            
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar w-full sm:w-auto">
                <span class="text-xs font-bold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider whitespace-nowrap mr-2">Sort By:</span>
                
                @foreach([
                    'sort_order' => 'Default',
                    'name' => 'Name (A-Z)',
                    'articles' => 'Most Stories',
                    'latest' => 'Newest'
                ] as $val => $label)
                    <button type="submit" name="sort" value="{{ $val }}" 
                            class="px-4 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ $sort === $val ? 'bg-gray-900 text-white dark:!bg-white dark:!text-black shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-text-secondary dark:!hover:text-white' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select name="order" onchange="this.form.submit()" class="px-4 py-1.5 rounded-full bg-gray-100 dark:!bg-bg-card border-none text-sm font-semibold focus:ring-0 cursor-pointer hover:bg-gray-200 dark:!hover:bg-bg-card-hover transition-colors">
                    <option value="asc" {{ $order === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ $order === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
                
                @if($search)
                    <a href="{{ route('categories.index') }}" class="p-2 text-red-500 hover:bg-red-50 rounded-full transition-colors" title="Clear Search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- All Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="group bg-white dark:!bg-bg-card rounded-2xl overflow-hidden border border-gray-100 dark:!border-border-secondary hover:shadow-xl hover:border-accent/30 dark:!hover:border-accent/30 transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                <!-- Header with Color/Image -->
                <div class="relative h-32 overflow-hidden">
                    @if($category->image)
                        <img src="{{ $category->image_url }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors"></div>
                    @else
                        @php
                            $bgColor = $category->color ?? 'linear-gradient(135deg, #E50914 0%, #B20710 100%)';
                        @endphp
                        <div class="w-full h-full" @style(['background: ' . $bgColor])></div>
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                    @endif
                    
                    <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-md text-white text-xs font-bold px-2 py-1 rounded-lg border border-white/10">
                        {{ $category->articles_count }}
                    </div>
                </div>
                
                <!-- Body -->
                <div class="p-6 flex-1 flex flex-col relative">
                    <!-- Icon placeholder or overlay logo could go here -->
                    <div class="w-12 h-12 rounded-xl bg-white dark:!bg-bg-card shadow-lg absolute -top-6 left-6 flex items-center justify-center border border-gray-100 dark:!border-border-secondary group-hover:scale-110 transition-transform duration-300">
                        <span class="text-xl font-bold text-accent">{{ substr($category->name, 0, 1) }}</span>
                    </div>
                    
                    <div class="mt-4 mb-auto">
                        <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 leading-tight group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                            {{ $category->name }}
                        </h3>
                        @if($category->description)
                            <p class="text-sm text-gray-500 dark:!text-text-secondary line-clamp-2">
                                {{ $category->description }}
                            </p>
                        @endif
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:!border-border-primary flex items-center justify-between text-xs font-medium text-gray-400 dark:!text-text-tertiary">
                        <span>
                            @if($category->articles->first())
                                Updated {{ $category->articles->first()->published_at?->diffForHumans() ?? 'Recently' }}
                            @else
                                No articles yet
                            @endif
                        </span>
                        <span class="group-hover:translate-x-1 transition-transform text-accent">
                            Explore &rarr;
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:!bg-bg-card mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2">No Categories Found</h3>
                <p class="text-gray-500 dark:!text-text-secondary max-w-md mx-auto">
                    We couldn't find any categories matching your criteria. Try adjusting your search or filters.
                </p>
                @if($search)
                    <a href="{{ route('categories.index') }}" class="inline-block mt-6 px-6 py-2 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-colors">
                        Clear Search
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    
    <!-- Who We Are Section (Cinematic Banner) -->
    <div class="mt-24 relative rounded-3xl overflow-hidden group">
        <div class="absolute inset-0 bg-gray-900">
            <img src="https://images.unsplash.com/photo-1517604931442-710e8ed05b54?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Cinema" class="w-full h-full object-cover opacity-30 transition-transform duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-gray-900/80 to-transparent"></div>
        </div>
        <div class="relative z-10 px-8 py-16 md:p-20 max-w-4xl">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight" style="font-family: 'Poppins', sans-serif;">
                Experience the <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-400">Extraordinary</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl font-medium">
                Nazaara Circle brings you closer to the stars. From exclusive interviews to in-depth analysis of your favorite shows, we are your front-row seat to entertainment.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg hover:shadow-accent/50 transform hover:-translate-y-1">Join Our Community</a>
                <a href="#" class="px-8 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-full hover:bg-white/20 transition-all border border-white/20">Latest News</a>
            </div>
        </div>
    </div>
</div>
@endsection
