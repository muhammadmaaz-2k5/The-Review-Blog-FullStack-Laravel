@extends('layouts.app')

@section('title', 'View Article - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.articles.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Articles
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $article->title }}
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Article Details
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                View Public
            </a>
            <a href="{{ route('admin.articles.edit', $article) }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Featured Image -->
            @if($article->featured_image)
                <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        Featured Image
                    </h2>
                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full rounded-lg" onerror="this.onerror=null;this.src='{{ asset('article_image_notfound.png') }}'">
                </div>
            @endif

            <!-- Content -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Content
                </h2>
                @if($article->excerpt)
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Excerpt
                        </h3>
                        <p class="text-gray-700 dark:!text-text-primary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $article->excerpt }}
                        </p>
                    </div>
                @endif
                <div class="prose dark:!prose-invert max-w-none article-content">
                    {!! $article->rendered_content !!}
                </div>
            </div>

            <!-- Comments -->
            @if($article->comments->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Comments ({{ $article->comments->count() }})
                </h2>
                <div class="space-y-4">
                    @foreach($article->comments->take(10) as $comment)
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $comment->user ? $comment->user->name : $comment->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">
                                    {{ $comment->status }}
                                </span>
                            </div>
                            <p class="text-gray-700 dark:!text-text-primary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ $comment->content }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Article Info -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Article Info
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</p>
                        @if($article->status === 'published')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">Published</span>
                        @elseif($article->status === 'draft')
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs dark:!bg-gray-800 dark:!text-gray-400">Draft</span>
                        @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400">Scheduled</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Category</p>
                        <p class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $article->category ? $article->category->name : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Author</p>
                        <p class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $article->author ? $article->author->name : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Views</p>
                        <p class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ number_format($article->views) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Reading Time</p>
                        <p class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $article->reading_time ?? '—' }} min
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Published At</p>
                        <p class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Created At</p>
                        <p class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $article->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            @if($article->tags->count() > 0)
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Tags
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Options -->
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Options
                </h2>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Featured</span>
                        <span class="px-2 py-1 {{ $article->is_featured ? 'bg-yellow-100 text-yellow-800 dark:!bg-yellow-900/20 dark:!text-yellow-400' : 'bg-gray-100 text-gray-800 dark:!bg-gray-800 dark:!text-gray-400' }} rounded text-xs">
                            {{ $article->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Allow Comments</span>
                        <span class="px-2 py-1 {{ $article->allow_comments ? 'bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400' : 'bg-gray-100 text-gray-800 dark:!bg-gray-800 dark:!text-gray-400' }} rounded text-xs">
                            {{ $article->allow_comments ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
function copyToClipboard(event, elementId, label) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        // Use modern Clipboard API if available
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(element.value).then(() => {
                showCopySuccess(event, label);
            }).catch(() => {
                // Fallback to execCommand
                document.execCommand('copy');
                showCopySuccess(event, label);
            });
        } else {
            // Fallback for older browsers
            document.execCommand('copy');
            showCopySuccess(event, label);
        }
    } catch (err) {
        alert('Failed to copy. Please select and copy manually.');
    }
}

function showCopySuccess(event, label) {
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    button.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'bg-accent', 'hover:bg-accent-light');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
        if (label === 'Full URL') {
            button.classList.add('bg-accent', 'hover:bg-accent-light');
        } else {
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
    }, 2000);
}
</script>
@endsection

