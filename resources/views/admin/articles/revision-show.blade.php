@extends('layouts.app')

@section('title', 'View Revision - Admin')

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
                Revision #{{ $revision->revision_number }}
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Article: <strong>{{ $article->title }}</strong>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.articles.revisions.compare', [$article, $revision]) }}" 
               class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors dark:!bg-purple-900/20 dark:!text-purple-400 dark:!hover:bg-purple-900/30" 
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Compare with Current
            </a>
            <form action="{{ route('admin.articles.revisions.restore', [$article, $revision]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to restore this revision? This will create a new revision from the current state before restoring.');">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors" 
                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Restore This Revision
                </button>
            </form>
        </div>
    </div>

    <!-- Revision Info -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Revision Number</p>
                <p class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">#{{ $revision->revision_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Created</p>
                <p class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ $revision->created_at->format('M j, Y g:i A') }}
                </p>
                <p class="text-xs text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $revision->created_at->diffForHumans() }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Created By</p>
                <p class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ $revision->creator ? $revision->creator->name : 'Unknown' }}
                </p>
            </div>
            @if($revision->change_summary)
            <div class="md:col-span-3">
                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Change Summary</p>
                <p class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ $revision->change_summary }}
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Revision Content -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <div class="mb-6 border-b border-gray-200 dark:!border-border-secondary pb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $revision->title }}
            </h2>
            @if($revision->excerpt)
            <p class="text-gray-600 dark:!text-text-secondary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                {{ $revision->excerpt }}
            </p>
            @endif
        </div>

        @if($revision->featured_image)
        <div class="mb-6">
            <img src="{{ $revision->featured_image }}" alt="{{ $revision->title }}" class="w-full rounded-lg">
        </div>
        @endif

        <div class="article-content prose max-w-none dark:prose-invert">
            {!! $revision->content !!}
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200 dark:!border-border-secondary">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Status</p>
                    <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <span class="px-2 py-1 bg-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-800 rounded text-xs dark:!bg-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-900/20 dark:!text-{{ $revision->status === 'published' ? 'green' : ($revision->status === 'draft' ? 'yellow' : 'blue') }}-400">
                            {{ ucfirst($revision->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Featured</p>
                    <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        {{ $revision->is_featured ? 'Yes' : 'No' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Comments</p>
                    <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        {{ $revision->allow_comments ? 'Enabled' : 'Disabled' }}
                    </p>
                </div>
                @if($revision->published_at)
                <div>
                    <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Published At</p>
                    <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        {{ $revision->published_at->format('M j, Y') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

