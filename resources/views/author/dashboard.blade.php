@extends('layouts.app')

@section('title', 'Author Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Author Dashboard
        </h1>
        <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Welcome back, {{ auth()->user()->name }}! Manage your articles and track your performance.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Total Articles
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($totalArticles) }}
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:!bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 dark:!text-green-400 font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ number_format($publishedArticles) }} Published
                </span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ number_format($draftArticles) }} Draft
                </span>
            </div>
        </div>

        <!-- Total Views -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Total Views
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($totalViews) }}
                    </p>
                </div>
                <div class="p-3 bg-orange-100 dark:!bg-orange-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600 dark:!text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                All time views
            </div>
        </div>

        <!-- Comments -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Comments
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($totalComments) }}
                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:!bg-purple-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600 dark:!text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                {{ $thisMonthComments }} this month
            </div>
        </div>

        <!-- Likes -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Total Likes
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($totalLikes) }}
                    </p>
                </div>
                <div class="p-3 bg-red-100 dark:!bg-red-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-red-600 dark:!text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                On your articles
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Published -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Published</p>
            <p class="text-2xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ number_format($publishedArticles) }}</p>
        </div>

        <!-- Draft -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Draft</p>
            <p class="text-2xl font-bold text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ number_format($draftArticles) }}</p>
        </div>

        <!-- Featured -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Featured</p>
            <p class="text-2xl font-bold text-yellow-600 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ number_format($featuredArticles) }}</p>
        </div>

        <!-- This Month -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">This Month</p>
            <p class="text-2xl font-bold text-blue-600 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ number_format($thisMonthArticles) }}</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Articles -->
        <div class="lg:col-span-2 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Articles
                </h2>
                <a href="{{ route('admin.articles.index') }}" class="text-sm text-accent hover:underline font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    View All →
                </a>
            </div>
            
            @if($recentArticles->count() > 0)
                <div class="space-y-4">
                    @foreach($recentArticles as $article)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <div class="w-20 h-20 rounded overflow-hidden bg-gray-200 dark:!bg-gray-700 flex-shrink-0">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    @if($article->featured_image)
                                        <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                    @endif
                                </a>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:!text-white mb-1 truncate" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-accent transition-colors">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    @if($article->category)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">{{ $article->category->name }}</span>
                                    @endif
                                </p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span>{{ $article->created_at->diffForHumans() }}</span>
                                    @if($article->status === 'published')
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded dark:!bg-green-900/20 dark:!text-green-400">Published</span>
                                    @elseif($article->status === 'draft')
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded dark:!bg-gray-800 dark:!text-gray-400">Draft</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded dark:!bg-blue-900/20 dark:!text-blue-400">Scheduled</span>
                                    @endif
                                    <span>{{ number_format($article->views) }} views</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.articles.edit', $article) }}" class="px-3 py-1 bg-accent hover:bg-accent-light text-white rounded text-sm transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">No articles yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Get started by creating your first article.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg text-sm font-semibold transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Create Article
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Top Viewed Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-6" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Top Viewed
            </h2>
            
            @if($topViewedArticles->count() > 0)
                <div class="space-y-4">
                    @foreach($topViewedArticles as $article)
                        <div class="p-3 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1 text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('articles.show', $article) }}" class="hover:text-accent transition-colors">
                                    {{ Str::limit($article->title, 50) }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ number_format($article->views) }} views
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8 text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No views yet
                </p>
            @endif
        </div>
    </div>

    <!-- Recent Comments -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Recent Comments on Your Articles
            </h2>
        </div>
        
        @if($recentComments->count() > 0)
            <div class="space-y-4">
                @foreach($recentComments as $comment)
                    <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    <a href="{{ route('articles.show', $comment->article) }}#comment-{{ $comment->id }}" class="hover:text-accent transition-colors">
                                        {{ $comment->article->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ Str::limit($comment->content, 150) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            <div class="flex items-center gap-2">
                                @if($comment->user)
                                    @if($comment->user->avatar)
                                        <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}" class="w-6 h-6 rounded-full">
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-accent flex items-center justify-center text-white text-xs font-semibold">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span>{{ $comment->user->name }}</span>
                                @else
                                    <span>{{ $comment->name }}</span>
                                @endif
                            </div>
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                No comments on your articles yet.
            </p>
        @endif
    </div>
</div>
@endsection

