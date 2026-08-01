@extends('layouts.app')

@section('title', 'Sitemaps - Nazaara Circle')

@section('content')
<style>
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
    .dark .section-title {
        color: #fff;
    }
    .content-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }
    .dark .content-card {
        background: #1a1a1a;
        border-color: #333;
    }
    .who-we-are {
        background-color: #000;
        color: #fff;
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        margin-top: 50px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .who-we-are::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #E50914, #ff4d4d);
    }
    .who-title {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 1px;
    }
    .who-text {
        font-size: 16px;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto 25px;
        color: #ccc;
    }
    .who-btn {
        display: inline-block;
        background: #E50914;
        color: #fff;
        padding: 10px 25px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 4px;
        transition: background 0.3s;
    }
    .who-btn:hover {
        background: #ff1f2a;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="section-title">
            Sitemaps
        </h1>
        <p class="text-gray-600 dark:text-text-secondary text-lg" style="font-family: 'Poppins', sans-serif;">
            Browse all pages and content on Nazaara Circle. You can also access our XML sitemaps for search engines.
        </p>
    </div>

    <!-- XML Sitemap Links -->
    <div class="content-card">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            XML Sitemaps (For Search Engines)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('sitemap.index') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white">Main Sitemap Index</div>
                    <div class="text-sm text-gray-500 dark:text-text-secondary">/sitemap.xml</div>
                </div>
            </a>
            <a href="{{ route('sitemap.home') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white">Home Page Sitemap</div>
                    <div class="text-sm text-gray-500 dark:text-text-secondary">/sitemap/home.xml</div>
                </div>
            </a>
            <a href="{{ route('sitemap.pages') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white">Pages Sitemap</div>
                    <div class="text-sm text-gray-500 dark:text-text-secondary">/sitemap/pages.xml</div>
                </div>
            </a>
            <a href="{{ route('sitemap.articles') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white">Articles Sitemap</div>
                    <div class="text-sm text-gray-500 dark:text-text-secondary">/sitemap/articles.xml</div>
                </div>
            </a>

            <a href="{{ route('sitemap.categories') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white">Categories Sitemap</div>
                    <div class="text-sm text-gray-500 dark:text-text-secondary">/sitemap/categories.xml</div>
                </div>
            </a>
            <a href="{{ route('sitemap.tags') }}" class="flex items-center gap-3 p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-semibold text-gray-900 dark:text-white">Tags Sitemap</div>
                    <div class="text-sm text-gray-500 dark:text-text-secondary">/sitemap/tags.xml</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Static Pages -->
    @if(!empty($pages))
    <div class="content-card">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Static Pages
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($pages as $page)
                <a href="{{ $page['loc'] }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-white text-sm">{{ parse_url($page['loc'], PHP_URL_PATH) }}</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Categories -->
    @if(!empty($categories))
    <div class="content-card">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Categories ({{ count($categories) }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($categories as $category)
                <a href="{{ $category['loc'] }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-white text-sm">{{ parse_url($category['loc'], PHP_URL_PATH) }}</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tags -->
    @if(!empty($tags))
    <div class="content-card">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Tags ({{ count($tags) }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($tags as $tag)
                <a href="{{ $tag['loc'] }}" class="flex items-center gap-2 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-white text-sm">{{ parse_url($tag['loc'], PHP_URL_PATH) }}</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Articles (Limited to 100) -->
    @if(!empty($articles))
    <div class="bg-white dark:bg-bg-card border border-gray-200 dark:border-border-primary rounded-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Recent Articles (Showing {{ min(count($articles), 100) }} of {{ count($articles) }})
        </h2>
        <p class="text-sm text-gray-500 dark:text-text-secondary mb-4">
            For the complete list, please visit our <a href="{{ route('articles.index') }}" class="text-accent hover:underline">Articles page</a> or check the <a href="{{ route('sitemap.articles') }}" class="text-accent hover:underline">Articles XML Sitemap</a>.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach(array_slice($articles, 0, 100) as $article)
                <a href="{{ $article['loc'] }}" class="flex items-start gap-2 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                    <svg class="w-4 h-4 text-accent mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-white text-sm line-clamp-2">{{ parse_url($article['loc'], PHP_URL_PATH) }}</span>
                </a>
            @endforeach
        </div>
        @if(count($articles) > 100)
            <div class="mt-4 text-center">
                <a href="{{ route('articles.index') }}" class="inline-block px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold transition-colors">
                    View All Articles
                </a>
            </div>
        @endif
    </div>
    @endif



    <!-- Help Section -->
    <div class="bg-gray-50 dark:bg-bg-card-hover border border-gray-200 dark:border-border-primary rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            About Sitemaps
        </h2>
        <div class="text-gray-600 dark:text-text-secondary space-y-2 text-sm" style="font-family: 'Poppins', sans-serif;">
            <p>
                Sitemaps help search engines discover and index all pages on our website. We provide both human-readable HTML sitemaps and XML sitemaps for search engines.
            </p>
            <p>
                <strong>For Users:</strong> Use the links above to browse all content on Nazaara Circle.
            </p>
            <p>
                <strong>For Search Engines:</strong> Submit our XML sitemap (<a href="{{ route('sitemap.index') }}" class="text-accent hover:underline">{{ route('sitemap.index') }}</a>) to Google Search Console, Bing Webmaster Tools, or other search engines.
            </p>
        </div>
    </div>

    <!-- Who We Are Section -->
    <div class="who-we-are">
        <h2 class="who-title">Who We Are</h2>
        <p class="who-text">
            Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
        </p>
        <a href="{{ route('about') }}" class="who-btn">Read More About Us</a>
    </div>
</div>
@endsection

