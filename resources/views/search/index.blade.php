@extends('layouts.app')

@section('title', 'Advanced Search' . ($query ? ' - ' . $query : '') . ' - Nazaara Circle')

@push('head')
<style>
    /* Custom scrollbar for filter sidebar */
    .filter-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .filter-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .filter-sidebar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .filter-sidebar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .dark .filter-sidebar::-webkit-scrollbar-track {
        background: #2a2a2a;
    }
    .dark .filter-sidebar::-webkit-scrollbar-thumb {
        background: #555;
    }
    .dark .filter-sidebar::-webkit-scrollbar-thumb:hover {
        background: #777;
    }
</style>
@endpush

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Advanced Search
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif;">
                Find articles using comprehensive filters
            </p>
        </div>

        <form method="GET" action="{{ route('search') }}" id="searchForm">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Sidebar - Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4 sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto filter-sidebar">
                        <!-- General Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-red-600 dark:!text-red-400 mb-3 uppercase" style="font-family: 'Poppins', sans-serif; font-weight: 700;">General</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search Query</label>
                                    <input type="text" name="q" value="{{ $query }}" placeholder="Search..." 
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Category</label>
                                    <select name="category_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Author</label>
                                    <select name="author_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <option value="">All Authors</option>
                                        @foreach($authors ?? [] as $author)
                                            <option value="{{ $author->id }}" {{ $selectedAuthor == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Year</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="Min" min="2000" max="{{ date('Y') }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <input type="number" name="year_to" value="{{ request('year_to') }}" placeholder="Max" min="2000" max="{{ date('Y') }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Content Type</label>
                                    <select name="type" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <option value="">All Types</option>
                                        <option value="article" {{ $selectedType === 'article' ? 'selected' : '' }}>Articles</option>
                                        <option value="application" {{ $selectedType === 'application' ? 'selected' : '' }}>Apps</option>
                                        
                                        
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-red-600 dark:!text-red-400 mb-3 uppercase" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Content</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Tags</label>
                                    <select name="tag_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <option value="">All Tags</option>
                                        @foreach($popularTags ?? [] as $tag)
                                            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Series</label>
                                    <select name="series_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <option value="">All Series</option>
                                        @foreach(\App\Models\Series::where('is_active', true)->orderBy('title')->get() as $series)
                                            <option value="{{ $series->id }}" {{ request('series_id') == $series->id ? 'selected' : '' }}>{{ $series->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_featured" value="1" {{ request('is_featured') ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-accent focus:ring-accent">
                                        <span class="text-xs text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Featured Only</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="allow_comments" value="1" {{ request('allow_comments') ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-accent focus:ring-accent">
                                        <span class="text-xs text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 500;">Comments Enabled</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Dates Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-red-600 dark:!text-red-400 mb-3 uppercase" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Dates</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Published Date</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="date" name="date_from" value="{{ $dateFrom }}" 
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <input type="date" name="date_to" value="{{ $dateTo }}" 
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-red-600 dark:!text-red-400 mb-3 uppercase" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Metadata</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Reading Time (min)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" name="reading_time_min" value="{{ request('reading_time_min') }}" placeholder="Min" min="1"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <input type="number" name="reading_time_max" value="{{ request('reading_time_max') }}" placeholder="Max" min="1"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Views</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="number" name="views_min" value="{{ request('views_min') }}" placeholder="Min" min="0"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <input type="number" name="views_max" value="{{ request('views_max') }}" placeholder="Max" min="0"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sorting Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-red-600 dark:!text-red-400 mb-3 uppercase" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Sorting</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Order By</label>
                                    <select name="order_by" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                                        <option value="popularity" {{ request('order_by', 'popularity') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                                        <option value="date" {{ request('order_by') == 'date' ? 'selected' : '' }}>Date (Newest)</option>
                                        <option value="date_old" {{ request('order_by') == 'date_old' ? 'selected' : '' }}>Date (Oldest)</option>
                                        <option value="views" {{ request('order_by') == 'views' ? 'selected' : '' }}>Most Views</option>
                                        <option value="title" {{ request('order_by') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                SHOW
                            </button>
                            <a href="{{ route('search') }}" class="block w-full px-4 py-2 text-center bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Clear Filters
                            </a>
                        </div>

                        <!-- Results Count -->
                        @if(isset($articles) && $articles->total() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:!border-border-primary">
                            <p class="text-sm text-red-600 dark:!text-red-400 font-semibold text-center" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                {{ number_format($articles->total()) }} results
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:col-span-3">
                    @if(count($results) > 0)
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                Search Results for "{{ $query }}"
                            </h2>
                        </div>

                        @if(isset($results['articles']) && $results['articles']->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-4 flex items-center gap-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                    Articles ({{ number_format($results['articles']->total()) }})
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($results['articles'] as $article)
                                        @include('articles._card', ['article' => $article])
                                    @endforeach
                                </div>
                                @if($results['articles']->hasPages())
                                    <div class="mt-6">{{ $results['articles']->links() }}</div>
                                @endif
                            </div>
                        @endif

                        @if(isset($results['applications']) && $results['applications']->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-4 flex items-center gap-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    Apps ({{ number_format($results['applications']->total()) }})
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($results['applications'] as $app)
                                        <div class="group bg-white dark:!bg-bg-card rounded-2xl border border-gray-200 dark:!border-border-secondary overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                                            <a href="{{ route('applications.show', $app->slug) }}" class="block relative aspect-[16/9] overflow-hidden">
                                                @if($app->image)
                                                    <img src="{{ Storage::url($app->image) }}" alt="{{ $app->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-teal-900 flex items-center justify-center text-white text-3xl font-black">NC</div>
                                                @endif
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                                @if($app->is_featured)
                                                    <span class="absolute top-4 left-4 bg-accent text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-lg">Featured</span>
                                                @endif
                                            </a>
                                            <div class="p-5">
                                                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 line-clamp-1 group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                                                    <a href="{{ route('applications.show', $app->slug) }}">{{ $app->title }}</a>
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-4 line-clamp-2" style="font-family: 'Poppins', sans-serif;">{{ Str::limit($app->description, 80) }}</p>
                                                <div class="flex items-center justify-between mt-4">
                                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                        {{ number_format($app->views) }} views
                                                    </div>
                                                    <a href="{{ route('applications.show', $app->slug) }}" class="text-[10px] font-black text-accent uppercase tracking-widest flex items-center gap-1 group/link">
                                                        Explore <span class="group-hover/link:translate-x-1 transition-transform">→</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($results['applications']->hasPages())
                                    <div class="mt-6">{{ $results['applications']->links() }}</div>
                                @endif
                            </div>
                        @endif

                        
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                                @if($game->is_featured)
                                                    <span class="absolute top-4 left-4 bg-accent text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-lg">Featured</span>
                                                @endif
                                            </a>
                                            <div class="p-5">
                                                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 line-clamp-1 group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                                                    <a href="{{ route('games.show', $game->slug) }}">{{ $game->title }}</a>
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-4 line-clamp-2" style="font-family: 'Poppins', sans-serif;">{{ Str::limit($game->description, 80) }}</p>
                                                <div class="flex items-center justify-between mt-4">
                                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                        {{ number_format($game->views) }} views
                                                    </div>
                                                    <a href="{{ route('games.show', $game->slug) }}" class="text-[10px] font-black text-accent uppercase tracking-widest flex items-center gap-1 group/link">
                                                        Explore <span class="group-hover/link:translate-x-1 transition-transform">→</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($results['games']->hasPages())
                                    <div class="mt-6">{{ $results['games']->links() }}</div>
                                @endif
                            </div>
                        @endif

                        
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                                                @if($tool->is_featured)
                                                    <span class="absolute top-4 left-4 bg-accent text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-lg">Featured</span>
                                                @endif
                                            </a>
                                            <div class="p-5">
                                                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2 line-clamp-1 group-hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif;">
                                                    <a href="{{ route('tools.show', $tool->slug) }}">{{ $tool->title }}</a>
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-4 line-clamp-2" style="font-family: 'Poppins', sans-serif;">{{ Str::limit($tool->description, 80) }}</p>
                                                <div class="flex items-center justify-between mt-4">
                                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                        {{ number_format($tool->views) }} views
                                                    </div>
                                                    <a href="{{ route('tools.show', $tool->slug) }}" class="text-[10px] font-black text-accent uppercase tracking-widest flex items-center gap-1 group/link">
                                                        Explore <span class="group-hover/link:translate-x-1 transition-transform">→</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($results['tools']->hasPages())
                                    <div class="mt-6">{{ $results['tools']->links() }}</div>
                                @endif
                            </div>
                        @endif

                        @if(count($results) === 0)
                            <div class="text-center py-16">
                                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">No Results Found</h3>
                                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif;">Try adjusting your search criteria or filters</p>
                            </div>
                        @endif

                    @elseif(isset($results['articles']) && $results['articles']->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif;">
                                Showing {{ $results['articles']->firstItem() }} - {{ $results['articles']->lastItem() }} of {{ number_format($results['articles']->total()) }} results
                            </p>
                        </div>
                        <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($results['articles'] as $article)
                                @include('articles._card', ['article' => $article])
                            @endforeach
                        </div>

                        @if($articles->hasPages())
                        <div class="mt-8 text-center">
                            <button id="load-more-btn" 
                                    data-page="2" 
                                    data-loading="false"
                                    data-url="{{ route('search.load-more') }}"
                                    data-filters="{{ json_encode(request()->all()) }}"
                                    class="px-6 py-3 bg-accent hover:bg-accent-light text-white rounded-lg font-semibold transition-all hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <span class="load-more-text">Load More</span>
                                <span class="load-more-spinner hidden">
                                    <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

                            loadMoreBtn.addEventListener('click', function() {
                                if (isLoading || loadMoreBtn.dataset.loading === 'true') return;
                                
                                isLoading = true;
                                loadMoreBtn.dataset.loading = 'true';
                                loadMoreBtn.disabled = true;
                                loadMoreBtn.querySelector('.load-more-text').classList.add('hidden');
                                loadMoreBtn.querySelector('.load-more-spinner').classList.remove('hidden');

                                const params = new URLSearchParams({
                                    page: currentPage,
                                    ...filters
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
                                            const newArticles = tempDiv.querySelectorAll('article');
                                            newArticles.forEach(article => {
                                                articlesContainer.appendChild(article);
                                            });

                                            if (data.hasMore) {
                                                currentPage = data.nextPage;
                                                loadMoreBtn.dataset.page = currentPage;
                                                isLoading = false;
                                                loadMoreBtn.dataset.loading = 'false';
                                                loadMoreBtn.disabled = false;
                                                loadMoreBtn.querySelector('.load-more-text').classList.remove('hidden');
                                                loadMoreBtn.querySelector('.load-more-spinner').classList.add('hidden');
                                            } else {
                                                loadMoreBtn.remove();
                                            }
                                        } else {
                                            loadMoreBtn.remove();
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error loading more articles:', error);
                                        loadMoreBtn.querySelector('.load-more-text').textContent = 'Error. Click to retry';
                                        isLoading = false;
                                        loadMoreBtn.dataset.loading = 'false';
                                        loadMoreBtn.disabled = false;
                                        loadMoreBtn.querySelector('.load-more-text').classList.remove('hidden');
                                        loadMoreBtn.querySelector('.load-more-spinner').classList.add('hidden');
                                    });
                            });
                        });
                        </script>
                    @elseif(request()->hasAny(['q', 'category_id', 'author_id', 'date_from', 'date_to', 'tag_id', 'series_id', 'is_featured', 'allow_comments', 'year_from', 'year_to', 'reading_time_min', 'reading_time_max', 'views_min', 'views_max', 'order_by']))
                        <div class="text-center py-16 bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg">
                            <p class="text-gray-600 dark:!text-text-secondary text-lg md:text-xl mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                No results found
                            </p>
                            <p class="text-sm text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif;">
                                Try adjusting your filters
                            </p>
                        </div>
                    @else
                        <div class="text-center py-16 bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg">
                            <p class="text-gray-600 dark:!text-text-secondary text-lg md:text-xl" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Use the filters on the left to search for articles
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Social Bar Ad -->
@push('scripts')
@endpush

@endsection
