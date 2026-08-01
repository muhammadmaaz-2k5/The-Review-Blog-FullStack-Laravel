@extends('layouts.app')

@section('title', 'Article Revisions - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.articles.edit', $article) }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Article
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Revision History
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Article: <strong>{{ $article->title }}</strong>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.articles.edit', $article) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Edit Article
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Current Version Card -->
    <div class="mb-6 bg-gradient-to-r from-blue-50 to-purple-50 dark:!from-blue-900/10 dark:!to-purple-900/10 rounded-lg border border-blue-200 dark:!border-blue-800 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Current Version
                </h2>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Status: <span class="px-2 py-1 bg-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-800 rounded text-xs dark:!bg-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-900/20 dark:!text-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-400">
                        {{ ucfirst($article->status) }}
                    </span>
                    @if($article->published_at)
                        • Published: {{ $article->published_at->format('M j, Y g:i A') }}
                    @endif
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Last Updated
                </p>
                <p class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ $article->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Revisions List -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
        <div class="p-6 border-b border-gray-200 dark:!border-border-secondary">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Previous Revisions ({{ $revisions->count() }})
            </h2>
        </div>

        @if($revisions->count() > 0)
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                @foreach($revisions as $revision)
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-gray-100 dark:!bg-gray-800 text-gray-700 dark:!text-gray-300 rounded-full text-sm font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Revision #{{ $revision->revision_number }}
                                </span>
                                <span class="px-2 py-1 bg-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-800 rounded text-xs dark:!bg-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-900/20 dark:!text-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-400">
                                    {{ ucfirst($revision->status) }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                {{ $revision->title }}
                            </h3>
                            
                            @if($revision->change_summary)
                            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <strong>Changes:</strong> {{ $revision->change_summary }}
                            </p>
                            @endif
                            
                            <div class="flex items-center gap-4 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <span>Created: {{ $revision->created_at->format('M j, Y g:i A') }}</span>
                                @if($revision->creator)
                                    <span>by {{ $revision->creator->name }}</span>
                                @endif
                                <span>{{ $revision->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 ml-4">
                            <a href="{{ route('admin.articles.revisions.show', [$article, $revision]) }}" 
                               class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors dark:!bg-blue-900/20 dark:!text-blue-400 dark:!hover:bg-blue-900/30 text-sm font-semibold" 
                               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                View
                            </a>
                            <a href="{{ route('admin.articles.revisions.compare', [$article, $revision]) }}" 
                               class="px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors dark:!bg-purple-900/20 dark:!text-purple-400 dark:!hover:bg-purple-900/30 text-sm font-semibold" 
                               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Compare
                            </a>
                            <form action="{{ route('admin.articles.revisions.restore', [$article, $revision]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to restore this revision? This will create a new revision from the current state before restoring.');">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors dark:!bg-green-900/20 dark:!text-green-400 dark:!hover:bg-green-900/30 text-sm font-semibold" 
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Restore
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No revisions yet. Revisions are created automatically when you update the article.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

