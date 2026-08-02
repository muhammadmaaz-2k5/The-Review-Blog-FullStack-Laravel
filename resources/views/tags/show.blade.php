@extends('layouts.app')

@section('title', $tag->name . ' - Nazaara Circle')

@section('content')
<style>
    /* Entertainment Page Styles */
    .tag-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 15px;
    }
    
    .section-title {
        font-size: 32px;
        font-weight: 800;
        margin: 15px 0 25px;
        color: #000;
        text-transform: uppercase;
        letter-spacing: -0.5px;
        font-family: 'Poppins', sans-serif;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        display: block;
        width: 60%;
        height: 4px;
        background: #E50914;
        margin-top: 5px;
    }
    
    /* Sidebar Styles */
    .sidebar-section {
        background: #fff;
        border: 1px solid #e0e0e0;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 8px;
    }
    
    .sidebar-title {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #E50914;
        color: #000;
        font-family: 'Poppins', sans-serif;
    }
    
    .sidebar-item {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .sidebar-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .sidebar-thumb {
        width: 90px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 4px;
        overflow: hidden;
        background: #f0f0f0;
    }
    
    .sidebar-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .sidebar-content h4 {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }
    
    .sidebar-content h4 a {
        color: #000;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .sidebar-content h4 a:hover {
        color: #E50914;
    }

    .load-more-btn {
        background: #E50914;
        color: #fff;
        border: none;
        padding: 12px 40px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
        font-family: 'Poppins', sans-serif;
        text-transform: uppercase;
        margin: 40px auto;
        display: block;
    }
    
    .load-more-btn:hover:not(:disabled) {
        background: #B20710;
    }
    
    .load-more-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Who We Are Section */
    .who-we-are {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: #fff;
        padding: 60px 40px;
        border-radius: 16px;
        margin-top: 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .who-title {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(45deg, #fff, #ccc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .who-text {
        font-size: 16px;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto 30px;
        color: #e0e0e0;
    }
    .who-btn {
        display: inline-block;
        padding: 12px 30px;
        background: #E50914;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        border-radius: 30px;
        transition: transform 0.3s, background 0.3s;
    }
    .who-btn:hover {
        background: #ff0f1f;
        transform: translateY(-2px);
    }
    
    .text-shadow-md { text-shadow: 0 1px 6px rgba(0,0,0,0.8); }
    .text-shadow-lg { text-shadow: 0 2px 12px rgba(0,0,0,0.8); }

    /* Dark Mode Support */
    html.dark .section-title,
    body.dark-mode .section-title {
        color: #fff;
    }
    
    html.dark .sidebar-section,
    body.dark-mode .sidebar-section {
        background: #1a1a1a;
        border-color: #333;
    }
    
    html.dark .sidebar-title,
    body.dark-mode .sidebar-title {
        color: #fff;
        border-color: #E50914;
    }
    
    html.dark .sidebar-content h4 a,
    body.dark-mode .sidebar-content h4 a {
        color: #e0e0e0;
    }

    html.dark .tag-container,
    body.dark-mode .tag-container {
        background-color: #121212;
    }
</style>

<div class="tag-container">
    <!-- Breadcrumbs -->
    <div class="mb-6">
        @if(isset($seo['breadcrumbs']))
            @include('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']])
        @else
            <nav class="flex items-center space-x-2 text-sm font-normal">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary transition-colors">Home</a>
                <span class="text-gray-400 dark:!text-text-tertiary">/</span>
                <a href="{{ route('tags.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary transition-colors">Tags</a>
                <span class="text-gray-400 dark:!text-text-tertiary">/</span>
                <span class="text-gray-900 dark:!text-white font-semibold">{{ $tag->name }}</span>
            </nav>
        @endif
    </div>

    <!-- Tag Header with Gradient -->
    <div class="relative mb-8 rounded-lg overflow-hidden shadow-lg">
        <div class="relative h-64 md:h-80" style="background: linear-gradient(135deg, #E50914 0%, #B20710 100%);">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>
            
            <div class="absolute inset-0 flex items-end">
                <div class="w-full p-6 md:p-8 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold border border-white/10 uppercase tracking-wider">Tag</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-3 text-shadow-lg font-poppins uppercase tracking-tight">
                        {{ $tag->name }}
                    </h1>
                    @if($tag->description)
                        <p class="text-lg text-gray-200 max-w-3xl mb-4 font-normal text-shadow-md">
                            {{ $tag->description }}
                        </p>
                    @endif
                    
                    <!-- Quick Stats in Header -->
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-semibold">{{ number_format($articles->total()) }} Articles</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Content (Articles Grid) -->
        <div class="lg:col-span-8 order-1">
            
            <h2 class="section-title">LATEST IN {{ $tag->name }}</h2>

            <!-- Articles Grid -->
            <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($articles as $article)
                    @include('articles._card', ['article' => $article])
                @empty
                    <div class="col-span-full text-center py-16 bg-gray-50 rounded-lg dark:!bg-bg-card">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-600 dark:!text-text-secondary text-lg mb-2 font-normal">
                            No articles found with this tag.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            @if($articles->hasPages())
                <div class="text-center" id="load-more-container">
                    <button type="button" id="load-more-btn" 
                            class="load-more-btn" 
                            data-page="2" 
                            data-loading="false"
                            data-url="{{ route('tags.load-more', $tag->slug) }}">
                        <span class="btn-text">Load More</span>
                        <span class="btn-loader" style="display: none;">
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
                let isLoading = false;

                loadMoreBtn.addEventListener('click', function() {
                    if (isLoading || loadMoreBtn.dataset.loading === 'true') return;
                    
                    const btn = this;
                    const originalText = btn.querySelector('.btn-text');
                    const loader = btn.querySelector('.btn-loader');
                    
                    isLoading = true;
                    btn.dataset.loading = 'true';
                    originalText.style.display = 'none';
                    loader.style.display = 'inline-block';

                    const params = new URLSearchParams({
                        page: currentPage
                    });

                    // Handle both relative and absolute URLs
                    const url = baseUrl.startsWith('http://') || baseUrl.startsWith('https://') 
                        ? baseUrl 
                        : window.location.origin + baseUrl;
                    
                    fetch(url + '?' + params.toString())
                        .then(response => response.json())
                        .then(data => {
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

                                newArticles.forEach(article => {
                                    articlesContainer.appendChild(article);
                                });

                                if (data.hasMore) {
                                    currentPage = data.nextPage;
                                    btn.dataset.page = currentPage;
                                    isLoading = false;
                                    btn.dataset.loading = 'false';
                                    originalText.style.display = 'inline-block';
                                    loader.style.display = 'none';
                                } else {
                                    btn.style.display = 'none';
                                }
                            } else {
                                btn.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading more articles:', error);
                            originalText.textContent = 'Error. Retry';
                            originalText.style.display = 'inline-block';
                            loader.style.display = 'none';
                            isLoading = false;
                            btn.dataset.loading = 'false';
                        });
                });
            });
            </script>
        </div>

        <!-- Sidebar (Right) -->
        <div class="lg:col-span-4 order-2 space-y-8">
            
            <!-- Statistics -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">STATISTICS</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-600 dark:!text-text-secondary font-medium uppercase">Total Articles</span>
                        <span class="text-lg font-bold text-gray-900 dark:!text-white">{{ number_format($articles->total()) }}</span>
                    </div>
                </div>
            </div>

            <!-- Featured Articles -->
            @if(isset($featuredArticles) && $featuredArticles->count() > 0)
            <div class="sidebar-section">
                <h3 class="sidebar-title">MUST READ</h3>
                <div class="space-y-4">
                    @foreach($featuredArticles as $featured)
                        <div class="sidebar-item">
                            <a href="{{ route('articles.show', $featured->slug) }}" class="sidebar-thumb">
                                @if($featured->featured_image)
                                    <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}">
                                @else
                                    <img src="{{ asset('article_image_notfound.png') }}" alt="{{ $featured->title }}" class="w-full h-full object-cover">
                                @endif
                            </a>
                            <div class="sidebar-content">
                                <h4>
                                    <a href="{{ route('articles.show', $featured->slug) }}">
                                        {{ $featured->title }}
                                    </a>
                                </h4>
                                <span class="text-xs text-gray-500 mt-1 block">{{ $featured->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Popular Tags -->
            @if(isset($popularTags) && $popularTags->count() > 0)
            <div class="sidebar-section">
                <h3 class="sidebar-title">POPULAR TAGS</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularTags as $pTag)
                        <a href="{{ route('tags.show', $pTag->slug) }}" 
                           class="px-3 py-1 bg-gray-100 hover:bg-accent text-gray-700 hover:text-white rounded-full text-xs transition-all font-medium uppercase dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-accent {{ $pTag->id === $tag->id ? 'bg-accent text-white' : '' }}">
                            {{ $pTag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            
        </div>
    </div>

    <!-- Who We Are Section -->
    <div class="who-we-are">
        <h2 class="who-title">Who We Are</h2>
        <p class="who-text">
            Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
        </p>
        <a href="{{ route('login') }}" class="who-btn">Join the Circle</a>
    </div>
    
</div>

<!-- Social Bar Ad -->
@push('scripts')
@endpush

@endsection
