@extends('layouts.app')

@section('title', 'Compare Revisions - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.articles.revisions', $article) }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Revisions
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Compare Revisions
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Article: <strong>{{ $article->title }}</strong>
            </p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.articles.revisions.restore', [$article, $revision1]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to restore this revision? This will create a new revision from the current state before restoring.');">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors" 
                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Restore Revision #{{ $revision1->revision_number }}
                </button>
            </form>
        </div>
    </div>

    <!-- Comparison Info -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-r border-gray-200 dark:!border-border-secondary pr-6">
                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Revision #{{ $revision1->revision_number }}
                </h3>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Created: {{ $revision1->created_at->format('M j, Y g:i A') }}<br>
                    @if($revision1->creator)
                        By: {{ $revision1->creator->name }}
                    @endif
                </p>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    @if($revision2)
                        Revision #{{ $revision2->revision_number }}
                    @else
                        Current Version
                    @endif
                </h3>
                <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    @if($revision2)
                        Created: {{ $revision2->created_at->format('M j, Y g:i A') }}<br>
                        @if($revision2->creator)
                            By: {{ $revision2->creator->name }}
                        @endif
                    @else
                        Last Updated: {{ $article->updated_at->format('M j, Y g:i A') }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Side-by-Side Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revision 1 -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
            <div class="p-4 border-b border-gray-200 dark:!border-border-secondary bg-blue-50 dark:!bg-blue-900/10">
                <h2 class="text-lg font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Revision #{{ $revision1->revision_number }}
                </h2>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ $revision['title'] ?? $revision1->title }}
                </h3>
                
                @if(($revision['excerpt'] ?? $revision1->excerpt))
                <p class="text-gray-600 dark:!text-text-secondary mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $revision['excerpt'] ?? $revision1->excerpt }}
                </p>
                @endif

                <div class="article-content prose max-w-none dark:prose-invert">
                    {!! $revision['content'] ?? $revision1->content !!}
                </div>
            </div>
        </div>

        <!-- Current/Revision 2 -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
            <div class="p-4 border-b border-gray-200 dark:!border-border-secondary bg-purple-50 dark:!bg-purple-900/10">
                <h2 class="text-lg font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    @if($revision2)
                        Revision #{{ $revision2->revision_number }}
                    @else
                        Current Version
                    @endif
                </h2>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ $current['title'] ?? $article->title }}
                </h3>
                
                @if(($current['excerpt'] ?? $article->excerpt))
                <p class="text-gray-600 dark:!text-text-secondary mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $current['excerpt'] ?? $article->excerpt }}
                </p>
                @endif

                <div class="article-content prose max-w-none dark:prose-invert">
                    {!! $current['content'] ?? $article->rendered_content !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Differences Summary -->
    <div class="mt-6 bg-yellow-50 dark:!bg-yellow-900/10 border border-yellow-200 dark:!border-yellow-800 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Differences Detected
        </h3>
        <ul class="space-y-2 text-sm text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            @if(($revision['title'] ?? $revision1->title) !== ($current['title'] ?? $article->title))
                <li>• Title changed</li>
            @endif
            @if(($revision['excerpt'] ?? $revision1->excerpt) !== ($current['excerpt'] ?? $article->excerpt))
                <li>• Excerpt changed</li>
            @endif
            @if(($revision['content'] ?? $revision1->content) !== ($current['content'] ?? $article->content))
                <li>• Content changed</li>
            @endif
            @if($revision1->status !== ($revision2->status ?? $article->status))
                <li>• Status changed</li>
            @endif
        </ul>
    </div>
</div>
@endsection

