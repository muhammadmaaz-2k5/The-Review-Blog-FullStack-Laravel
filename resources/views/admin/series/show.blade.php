@extends('layouts.app')

@section('title', 'View Series - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.series.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Series
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $series->title }}
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Series Details
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('series.show', $series->slug) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                View Public
            </a>
            <a href="{{ route('admin.series.edit', $series) }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Edit
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-800 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-800 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Featured Image -->
            @if($series->featured_image)
                <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        Featured Image
                    </h2>
                    <img src="{{ $series->featured_image_url }}" alt="{{ $series->title }}" class="w-full rounded-lg" onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}'">
                </div>
            @endif

            <!-- Description -->
            @if($series->description)
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Description
                </h2>
                <p class="text-gray-700 dark:!text-text-primary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $series->description }}
                </p>
            </div>
            @endif

            <!-- Add Article to Series -->
            @if(isset($availableArticles) && $availableArticles->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Add Article to Series
                </h2>
                <form method="POST" action="{{ route('admin.series.add-article', $series) }}" class="flex gap-3">
                    @csrf
                    <div class="flex-1">
                        <select name="article_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            <option value="">Select an article...</option>
                            @foreach($availableArticles as $article)
                                <option value="{{ $article->id }}">
                                    {{ $article->title }} 
                                    <span class="text-xs text-gray-500">({{ ucfirst($article->status) }})</span>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <input type="number" name="series_order" 
                               value="{{ $series->articles->max('series_order') + 1 }}" 
                               min="1"
                               placeholder="Order"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    </div>
                    <button type="submit" 
                            class="px-6 py-2 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all hover:scale-105"
                            style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Add Article
                    </button>
                </form>
            </div>
            @endif

            <!-- Articles in Series -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        Articles in Series ({{ $series->articles->count() }})
                    </h2>
                </div>
                
                @if($series->articles->count() > 0)
                    <div class="space-y-3">
                        @foreach($series->articles as $article)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-sm text-gray-500 dark:!text-text-secondary font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        #{{ $article->series_order ?? $loop->iteration }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $article->title }}
                                    </h3>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:!text-text-secondary ml-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span class="px-2 py-1 bg-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-800 rounded dark:!bg-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-900/20 dark:!text-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-400">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                    <span>{{ $article->views }} views</span>
                                    <span>{{ $article->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.series.update-article-order', $series) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="article_id" value="{{ $article->id }}">
                                    <input type="number" name="series_order" 
                                           value="{{ $article->series_order ?? $loop->iteration }}" 
                                           min="1"
                                           class="w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card dark:!border-border-primary dark:!text-white"
                                           style="font-family: 'Poppins', sans-serif; font-weight: 400;"
                                           onchange="this.form.submit()">
                                </form>
                                <a href="{{ route('admin.articles.edit', $article) }}" 
                                   class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors dark:!bg-blue-900/20 dark:!text-blue-400 dark:!hover:bg-blue-900/30 text-sm font-semibold" 
                                   style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.series.remove-article', [$series, $article]) }}" 
                                      onsubmit="return confirm('Are you sure you want to remove this article from the series?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors dark:!bg-red-900/20 dark:!text-red-400 dark:!hover:bg-red-900/30 text-sm font-semibold" 
                                            style="font-family: 'Poppins', sans-serif; font-weight: 600;"
                                            title="Remove from series">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            No articles in this series yet. <a href="{{ route('admin.articles.create') }}" class="text-accent hover:underline">Create an article</a> and assign it to this series.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Series Info -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Series Information
                </h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Status</p>
                        <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            @if($series->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">Active</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs dark:!bg-gray-800 dark:!text-gray-400">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Total Articles</p>
                        <p class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                            {{ $series->articles->count() }}
                        </p>
                    </div>
                    @if($series->author)
                    <div>
                        <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Author</p>
                        <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            {{ $series->author->name }}
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Created</p>
                        <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            {{ $series->created_at->format('M j, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Slug</p>
                        <p class="font-semibold text-gray-900 dark:!text-white text-sm break-all" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            {{ $series->slug }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

