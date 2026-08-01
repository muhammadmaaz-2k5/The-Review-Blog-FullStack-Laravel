@extends('layouts.app')

@section('title', 'Archives - Nazaara Circle')

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

    .archive-year {
        margin-bottom: 2rem;
    }
    
    .archive-year-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #E50914;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #E50914;
    }
    
    .archive-month {
        margin-bottom: 1.5rem;
    }
    
    .archive-month-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.75rem;
        padding-left: 0.5rem;
        border-left: 3px solid #E50914;
    }
    
    .archive-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e5e5;
    }
    
    .archive-item:last-child {
        border-bottom: none;
    }
    
    .archive-item-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        color: #333;
        transition: color 0.3s;
    }
    
    .archive-item-link:hover {
        color: #E50914;
    }
    
    .archive-date {
        font-size: 0.875rem;
        color: #666;
        min-width: 80px;
    }
    
    .archive-title {
        flex: 1;
        font-weight: 500;
    }
    
    .archive-category {
        font-size: 0.75rem;
        color: #E50914;
        background: rgba(229, 9, 20, 0.1);
        padding: 2px 8px;
        border-radius: 3px;
        text-transform: uppercase;
        font-weight: 600;
    }
    
    .filter-section {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    
    .filter-group {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-select {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        font-size: 0.875rem;
        cursor: pointer;
    }
    
    .filter-btn {
        padding: 0.5rem 1.5rem;
        background: #E50914;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .filter-btn:hover {
        background: #B20710;
    }
    
    .stats-card {
        background: white;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #E50914;
    }
    
    .stats-label {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.25rem;
    }
    
    html.dark .archive-year-title,
    body.dark-mode .archive-year-title {
        color: #E50914;
        border-color: #E50914;
    }
    
    html.dark .archive-month-title,
    body.dark-mode .archive-month-title {
        color: #fff;
        border-color: #E50914;
    }
    
    html.dark .archive-item-link,
    body.dark-mode .archive-item-link {
        color: #fff;
    }
    
    html.dark .archive-item,
    body.dark-mode .archive-item {
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    html.dark .filter-section,
    body.dark-mode .filter-section {
        background: #1F1F1F;
    }
    
    html.dark .filter-select,
    body.dark-mode .filter-select {
        background: #2A2A2A;
        border-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    
    html.dark .stats-card,
    body.dark-mode .stats-card {
        background: #1F1F1F;
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumbs -->
    @if(isset($seo['breadcrumbs']))
        @include('layouts.partials.breadcrumbs', ['items' => $seo['breadcrumbs']])
    @endif
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="section-title">
            Archives
        </h1>
        <p class="text-gray-600 dark:text-text-secondary text-lg" style="font-family: 'Poppins', sans-serif;">
            Browse all articles organized by publication date. Filter by year and month to find specific content.
        </p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="stats-card">
            <div class="stats-number">{{ $articles->total() }}</div>
            <div class="stats-label">Total Articles</div>
        </div>
        <div class="stats-card">
            <div class="stats-number">{{ $years->count() }}</div>
            <div class="stats-label">Years</div>
        </div>
        <div class="stats-card">
            <div class="stats-number">{{ $articlesByYearMonth->count() }}</div>
            <div class="stats-label">Months</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" action="{{ route('archives') }}" class="filter-group">
            <label for="year" class="text-sm font-semibold text-gray-700 dark:text-white">Filter by Year:</label>
            <select name="year" id="year" class="filter-select" onchange="this.form.submit()">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            
            @if($selectedYear)
            <label for="month" class="text-sm font-semibold text-gray-700 dark:text-white">Filter by Month:</label>
            <select name="month" id="month" class="filter-select" onchange="this.form.submit()">
                <option value="">All Months</option>
                @php
                    $monthList = [
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ];
                @endphp
                @foreach($monthList as $num => $name)
                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @endif
            
            @if($selectedYear || $selectedMonth)
            <a href="{{ route('archives') }}" class="filter-btn" style="text-decoration: none; display: inline-block;">
                Clear Filters
            </a>
            @endif
        </form>
    </div>

    <!-- Articles List -->
    @php
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
    @endphp
    
    @if($articles->count() > 0)
        @if($selectedYear || $selectedMonth)
            <!-- Filtered View - Simple List -->
            <div class="content-card">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    @if($selectedYear && $selectedMonth)
                        Articles from {{ $monthNames[$selectedMonth] }} {{ $selectedYear }}
                    @elseif($selectedYear)
                        Articles from {{ $selectedYear }}
                    @else
                        Articles
                    @endif
                    ({{ $articles->total() }})
                </h2>
                <div class="space-y-1">
                    @foreach($articles as $article)
                        <div class="archive-item">
                            <a href="{{ route('articles.show', $article->slug) }}" class="archive-item-link">
                                <span class="archive-date">{{ $article->published_at->format('M d, Y') }}</span>
                                <span class="archive-title">{{ $article->title }}</span>
                                @if($article->category)
                                    <span class="archive-category">{{ $article->category->name }}</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="mt-6">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Full Archives View - Grouped by Year/Month -->
            <div class="space-y-6">
                @foreach($groupedArticles as $year => $months)
                    <div class="archive-year">
                        <h2 class="archive-year-title">{{ $year }}</h2>
                        
                        @foreach($months as $month => $monthArticles)
                            <div class="archive-month">
                                <h3 class="archive-month-title">{{ $month }}</h3>
                                <div class="content-card p-4">
                                    <div class="space-y-1">
                                        @foreach($monthArticles as $article)
                                            <div class="archive-item">
                                                <a href="{{ route('articles.show', $article->slug) }}" class="archive-item-link">
                                                    <span class="archive-date">{{ $article->published_at->format('d') }}</span>
                                                    <span class="archive-title">{{ $article->title }}</span>
                                                    @if($article->category)
                                                        <span class="archive-category">{{ $article->category->name }}</span>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-bg-card border border-gray-200 dark:border-border-primary rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Articles Found</h3>
            <p class="text-gray-600 dark:text-text-secondary">
                @if($selectedYear || $selectedMonth)
                    No articles found for the selected filters. Try selecting a different year or month.
                @else
                    No articles available in the archives yet.
                @endif
            </p>
            @if($selectedYear || $selectedMonth)
                <a href="{{ route('archives') }}" class="inline-block mt-4 px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold transition-colors">
                    View All Archives
                </a>
            @endif
        </div>
    @endif

    <!-- Year/Month Summary -->
    @if(!$selectedYear && !$selectedMonth && $articlesByYearMonth->count() > 0)
    <div class="mt-8 bg-white dark:bg-bg-card border border-gray-200 dark:border-border-primary rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Archive Summary
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($articlesByYearMonth as $item)
                @php
                    $monthName = $monthNames[$item->month] ?? date('F', mktime(0, 0, 0, $item->month, 1));
                    $year = $item->year;
                @endphp
                <a href="{{ route('archives', ['year' => $year, 'month' => $item->month]) }}" 
                   class="block p-4 border border-gray-200 dark:border-border-primary rounded-lg hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ $monthName }} {{ $year }}</div>
                    <div class="text-sm text-gray-600 dark:text-text-secondary mt-1">{{ $item->count }} {{ Str::plural('article', $item->count) }}</div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Who We Are Section -->
    <div class="who-we-are">
        <h2 class="who-title">Who We Are</h2>
        <p class="who-text">
            Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
        </p>
        <a href="{{ route('login') }}" class="who-btn">Join the Circle</a>
    </div>
</div>
@endsection

