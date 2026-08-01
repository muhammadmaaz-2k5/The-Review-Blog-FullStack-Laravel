@extends('layouts.app')

@section('title', $category->name . ' - Nazaara Circle')

@section('content')
<!-- Hero Section (Cinematic Header) -->
<div class="relative w-full h-[400px] md:h-[500px] overflow-hidden">
    @php
        $headerBackground = $category->image_url ?? null;
        $fallbackColor = $category->color ?? 'linear-gradient(135deg, #E50914 0%, #B20710 100%)';
    @endphp

    @if($headerBackground)
        <img src="{{ $headerBackground }}" 
             alt="{{ $category->name }}" 
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <div class="absolute inset-0" @style(['background: ' . $fallbackColor])></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
    @endif
    
    <div class="absolute inset-0 bg-gradient-to-t from-bg-primary via-bg-primary/60 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

    <div class="relative z-10 h-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-end pb-12 md:pb-16">
        <!-- Breadcrumbs -->
        <div aria-label="breadcrumb" class="flex items-center gap-3 text-xs font-bold tracking-widest uppercase text-gray-300 mb-8 bg-transparent" style="font-family: 'Poppins', sans-serif;">
            <a href="{{ route('home') }}" class="hover:text-accent transition-colors duration-300">Home</a>
            <span class="text-accent">/</span>
            <a href="{{ route('categories.index') }}" class="hover:text-accent transition-colors duration-300">Collections</a>
            <span class="text-accent">/</span>
            <span class="text-white border-b-2 border-accent pb-0.5">{{ $category->name }}</span>
        </div>

        <h1 class="text-5xl md:text-7xl font-black text-white mb-4 uppercase tracking-tighter" style="font-family: 'Poppins', sans-serif; text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
            {{ $category->name }}
        </h1>
        
        @if($category->description)
            <p class="text-lg md:text-xl text-gray-200 max-w-3xl mb-8 font-medium leading-relaxed drop-shadow-md">
                {{ $category->description }}
            </p>
        @endif

        <div class="flex flex-wrap gap-4 text-sm font-bold">
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-full border border-white/20 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span>{{ number_format($categoryStats['total_articles']) }} Stories</span>
            </div>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-full border border-white/20 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span>{{ number_format($categoryStats['total_views']) }} Views</span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Content (Articles Grid) -->
        <div class="lg:col-span-8">
            
            <!-- Filters and Sorting -->
            <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-2xl p-6 mb-10 shadow-sm">
                <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="flex flex-col md:flex-row gap-6 items-end">
                    <!-- Sort By -->
                    <div class="flex-1 w-full">
                        <label class="block text-xs text-gray-500 dark:!text-text-tertiary mb-2 font-bold uppercase tracking-wider">Sort By</label>
                        <select name="sort" 
                                class="w-full px-4 py-2.5 bg-gray-50 dark:!bg-bg-card-hover border border-gray-200 dark:!border-border-primary rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent dark:!text-white font-medium text-sm transition-all outline-none appearance-none cursor-pointer"
                                onchange="this.form.submit()">
                            <option value="latest" {{ $filters['sort'] === 'latest' ? 'selected' : '' }}>Latest Updates</option>
                            <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="title" {{ $filters['sort'] === 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                            <option value="reading_time" {{ $filters['sort'] === 'reading_time' ? 'selected' : '' }}>Reading Time</option>
                        </select>
                    </div>
                    
                    <!-- Reading Time Filter -->
                    <div class="flex-1 w-full">
                        <label class="block text-xs text-gray-500 dark:!text-text-tertiary mb-2 font-bold uppercase tracking-wider">Duration</label>
                        <select name="reading_time" 
                                class="w-full px-4 py-2.5 bg-gray-50 dark:!bg-bg-card-hover border border-gray-200 dark:!border-border-primary rounded-xl focus:ring-2 focus:ring-accent focus:border-transparent dark:!text-white font-medium text-sm transition-all outline-none appearance-none cursor-pointer"
                                onchange="this.form.submit()">
                            <option value="">All Lengths</option>
                            <option value="short" {{ $filters['reading_time'] === 'short' ? 'selected' : '' }}>Short (≤5 min)</option>
                            <option value="medium" {{ $filters['reading_time'] === 'medium' ? 'selected' : '' }}>Medium (6-15 min)</option>
                            <option value="long" {{ $filters['reading_time'] === 'long' ? 'selected' : '' }}>Long (>15 min)</option>
                        </select>
                    </div>
                    
                    <!-- Clear Filters -->
                    @if($filters['sort'] !== 'latest' || $filters['reading_time'] || $filters['date_from'] || $filters['date_to'] || $filters['per_page'] != 15)
                        <div class="pb-1">
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-gray-700 flex items-center gap-2"
                               >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Reset
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:!border-border-secondary">
                <h2 class="text-2xl font-black text-gray-900 dark:!text-white uppercase tracking-tight" style="font-family: 'Poppins', sans-serif;">
                    Latest in {{ $category->name }}
                </h2>
            </div>

            <!-- Articles Grid -->
            <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($articles as $article)
                    @include('articles._card', ['article' => $article])
                @empty
                    <div class="col-span-full text-center py-20 bg-gray-50 dark:!bg-bg-card rounded-2xl border border-dashed border-gray-300 dark:!border-border-secondary">
                        <div class="text-6xl mb-4">🎬</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif;">No Stories Found</h3>
                        <p class="text-gray-500 dark:!text-text-secondary">Try adjusting your filters or check back later.</p>
                    </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            @if($articles->hasPages())
                <div class="text-center mt-16" id="load-more-container">
                    <button type="button" id="load-more-btn" 
                            class="group relative inline-flex items-center justify-center px-8 py-3 font-bold text-white transition-all duration-200 bg-accent font-lg rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent"
                            data-page="2" 
                            data-loading="false"
                            data-url="{{ route('categories.load-more', $category->slug) }}"
                            data-filters="{{ json_encode($filters) }}">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="btn-text relative">Load More Stories</span>
                        <span class="btn-loader relative hidden">
                            <svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                </div>
            @endif

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtn = document.getElementById('load-more-btn');
                if (!loadMoreBtn) return;

                const articlesContainer = document.getElementById('articles-container');
                let currentPage = parseInt(loadMoreBtn.dataset.page);
                const baseUrl = loadMoreBtn.dataset.url;
                const filters = JSON.parse(loadMoreBtn.dataset.filters || '{}');
                let isLoading = false;

                console.log('Load More initialized:', {
                    url: baseUrl,
                    currentPage: currentPage,
                    filters: filters
                });

                loadMoreBtn.addEventListener('click', function() {
                    if (isLoading || loadMoreBtn.dataset.loading === 'true') return;
                    
                    const btn = this;
                    const originalText = btn.querySelector('.btn-text');
                    const loader = btn.querySelector('.btn-loader');
                    
                    isLoading = true;
                    btn.dataset.loading = 'true';
                    originalText.classList.add('hidden');
                    loader.classList.remove('hidden');

                    // Build params properly - only include non-null values
                    const params = new URLSearchParams();
                    params.append('page', currentPage);
                    
                    // Only add filters that have actual values
                    Object.keys(filters).forEach(key => {
                        if (filters[key] !== null && filters[key] !== '' && filters[key] !== undefined) {
                            params.append(key, filters[key]);
                        }
                    });

                    // Handle both relative and absolute URLs
                    const url = baseUrl.startsWith('http://') || baseUrl.startsWith('https://') 
                        ? baseUrl 
                        : window.location.origin + baseUrl;
                    
                    console.log('Fetching:', url + '?' + params.toString());
                    console.log('Filters being sent:', filters);
                    
                    fetch(url + '?' + params.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Received data:', data);
                            if (data.success && data.html) {
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = data.html;
                                
                                // Process the new articles to ensure they are appended correctly
                                const newArticles = [];
                                // If the returned HTML contains article tags directly
                                tempDiv.querySelectorAll('article').forEach(article => {
                                    newArticles.push(article);
                                });
                                
                                // If no article tags found (maybe div wrappers), try children
                                if (newArticles.length === 0) {
                                     Array.from(tempDiv.children).forEach(child => {
                                         newArticles.push(child);
                                     });
                                }

                                console.log('New articles to append:', newArticles.length);
                                
                                newArticles.forEach(article => {
                                    articlesContainer.appendChild(article);
                                });

                                if (data.hasMore) {
                                    currentPage = data.nextPage;
                                    btn.dataset.page = currentPage;
                                    isLoading = false;
                                    btn.dataset.loading = 'false';
                                    originalText.classList.remove('hidden');
                                    loader.classList.add('hidden');
                                } else {
                                    console.log('No more pages, removing button');
                                    btn.parentElement.remove();
                                }
                            } else {
                                console.log('No data or not success, removing button');
                                btn.parentElement.remove();
                            }
                        })
                        .catch(error => {
                            console.error('Error loading more articles:', error);
                            alert('Failed to load more articles. Please try again.');
                            originalText.textContent = 'Error - Retry';
                            originalText.classList.remove('hidden');
                            loader.classList.add('hidden');
                            isLoading = false;
                            btn.dataset.loading = 'false';
                        });
                });
            });
            </script>
        </div>

        <!-- Sidebar (Right) -->
        <div class="lg:col-span-4 space-y-10">
            
            <!-- Statistics Widget -->
            <div class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:!border-border-primary bg-gray-50 dark:!bg-bg-card-hover">
                    <h3 class="text-lg font-bold text-gray-900 dark:!text-white flex items-center gap-2" style="font-family: 'Poppins', sans-serif;">
                        <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                        Category Stats
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:!border-border-primary">
                        <span class="text-sm text-gray-600 dark:!text-text-secondary font-medium uppercase tracking-wider">Total Articles</span>
                        <span class="text-lg font-black text-gray-900 dark:!text-white">{{ number_format($categoryStats['total_articles']) }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:!border-border-primary">
                        <span class="text-sm text-gray-600 dark:!text-text-secondary font-medium uppercase tracking-wider">Total Views</span>
                        <span class="text-lg font-black text-gray-900 dark:!text-white">{{ number_format($categoryStats['total_views']) }}</span>
                    </div>
                    @if($categoryStats['latest_article'])
                    <div class="pt-2">
                        <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-2 uppercase tracking-wider font-bold">Latest Update</p>
                        <a href="{{ route('articles.show', $categoryStats['latest_article']->slug) }}" class="text-sm font-bold text-gray-900 dark:!text-white hover:text-accent transition-colors line-clamp-2">
                            {{ $categoryStats['latest_article']->title }}
                        </a>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $categoryStats['latest_article']->published_at ? $categoryStats['latest_article']->published_at->format('M d, Y') : $categoryStats['latest_article']->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Popular in Category Widget -->
            @if($popularInCategory->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:!border-border-primary bg-gray-50 dark:!bg-bg-card-hover">
                    <h3 class="text-lg font-bold text-gray-900 dark:!text-white flex items-center gap-2" style="font-family: 'Poppins', sans-serif;">
                        <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                        Popular Now
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:!divide-border-primary">
                    @foreach($popularInCategory as $index => $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="flex gap-4 p-5 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors group items-start">
                        <span class="text-3xl font-black text-gray-200 dark:!text-gray-700 leading-none -mt-1 group-hover:text-accent/20 transition-colors" style="font-family: 'Poppins', sans-serif;">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:!text-white leading-snug line-clamp-2 group-hover:text-accent transition-colors mb-1" style="font-family: 'Poppins', sans-serif;">
                                {{ $article->title }}
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:!text-text-secondary">
                                <span>{{ $article->views }} views</span>
                                <span>•</span>
                                <span>{{ $article->reading_time ?? 5 }} min</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Newsletter Widget -->
            <div class="relative rounded-2xl overflow-hidden p-8 text-center group shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-accent to-purple-700 opacity-95"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-white mb-3" style="font-family: 'Poppins', sans-serif;">Don't Miss Out!</h3>
                    <p class="text-white/90 text-sm mb-6 font-medium">Get the latest {{ $category->name }} updates delivered straight to your inbox.</p>
                    <form action="#" class="space-y-3">
                        <input type="email" placeholder="Your email address" class="w-full px-5 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:bg-white/30 focus:border-white transition-all backdrop-blur-sm">
                        <button type="button" class="w-full px-5 py-3 bg-white text-accent font-black rounded-xl hover:bg-gray-100 transition-colors shadow-lg uppercase tracking-wide">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Other Categories -->
            @if($categories->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-6 flex items-center gap-2" style="font-family: 'Poppins', sans-serif;">
                    <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                    Explore More
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories->take(15) as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" 
                           class="px-4 py-2 text-xs font-bold rounded-lg border transition-all {{ $cat->id === $category->id ? 'bg-accent text-white border-accent shadow-lg shadow-accent/30' : 'bg-gray-50 text-gray-700 border-gray-200 hover:border-accent hover:text-accent dark:!bg-bg-card-hover dark:!text-white dark:!border-border-primary dark:!hover:border-accent' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    
    <!-- Who We Are Section (Cinematic Banner) -->
    <div class="mt-20 relative rounded-3xl overflow-hidden">
        <div class="absolute inset-0 bg-gray-900">
            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Audience" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-gray-900/90 to-transparent"></div>
        </div>
        <div class="relative z-10 px-8 py-16 md:p-20 max-w-4xl">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight" style="font-family: 'Poppins', sans-serif;">
                We Are <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-red-400">Nazaara Circle</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed max-w-2xl">
                Your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg hover:shadow-accent/50">Join the Circle</a>
                <a href="#" class="px-8 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-full hover:bg-white/20 transition-all border border-white/20">Read Our Story</a>
            </div>
        </div>
    </div>
</div>

<!-- Social Bar Ad -->
@push('scripts')
@endpush

@endsection
