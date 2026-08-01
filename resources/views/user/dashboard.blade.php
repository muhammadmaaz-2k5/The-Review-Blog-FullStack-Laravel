@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            My Dashboard
        </h1>
        <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Welcome back, {{ auth()->user()->name }}! Here's an overview of your activity.
        </p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:!bg-green-900/20 dark:!border-green-800 dark:!text-green-400" style="font-family: 'Poppins', sans-serif;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:!bg-red-900/20 dark:!border-red-800 dark:!text-red-400" style="font-family: 'Poppins', sans-serif;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Author Request Section -->
    @if(!auth()->user()->isAuthor())
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 dark:!from-blue-900/10 dark:!to-purple-900/10 rounded-lg border border-blue-200 dark:!border-blue-800 p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-blue-100 dark:!bg-blue-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                                Become an Author
                            </h2>
                            <p class="text-sm text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Share your knowledge and write articles for our community!
                            </p>
                        </div>
                    </div>

                    @if($authorRequest)
                        @if($authorRequest->isPending())
                            <div class="mt-4 p-4 bg-yellow-50 dark:!bg-yellow-900/10 border border-yellow-200 dark:!border-yellow-800 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-yellow-600 dark:!text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold text-yellow-800 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        Request Pending
                                    </span>
                                </div>
                                <p class="text-sm text-yellow-700 dark:!text-yellow-300" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    Your author request is currently under review. We'll notify you once a decision has been made.
                                </p>
                                @if($authorRequest->message)
                                    <div class="mt-3 p-3 bg-white dark:!bg-bg-card rounded border border-yellow-200 dark:!border-yellow-800">
                                        <p class="text-xs font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Your Message:</p>
                                        <p class="text-sm text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $authorRequest->message }}</p>
                                    </div>
                                @endif
                                <p class="text-xs text-yellow-600 dark:!text-yellow-400 mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    Submitted on {{ $authorRequest->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                        @elseif($authorRequest->isRejected())
                            <div class="mt-4 p-4 bg-red-50 dark:!bg-red-900/10 border border-red-200 dark:!border-red-800 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-red-600 dark:!text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="font-semibold text-red-800 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        Request Rejected
                                    </span>
                                </div>
                                <p class="text-sm text-red-700 dark:!text-red-300 mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    Your author request was not approved at this time.
                                </p>
                                @if($authorRequest->admin_notes)
                                    <div class="mt-3 p-3 bg-white dark:!bg-bg-card rounded border border-red-200 dark:!border-red-800">
                                        <p class="text-xs font-semibold text-gray-600 dark:!text-text-secondary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Admin Notes:</p>
                                        <p class="text-sm text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $authorRequest->admin_notes }}</p>
                                    </div>
                                @endif
                                <p class="text-xs text-red-600 dark:!text-red-400 mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    You can submit a new request if you'd like to try again.
                                </p>
                            </div>
                        @endif
                    @else
                        <form method="POST" action="{{ route('user.request-author') }}" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Why do you want to become an author? (Optional)
                                </label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="4" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card dark:!border-border-primary dark:!text-white dark:!placeholder-text-muted" 
                                    placeholder="Tell us about your writing experience, topics you'd like to cover, or any other relevant information..."
                                    style="font-family: 'Poppins', sans-serif; font-weight: 400;"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600 dark:!text-red-400" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
                                @enderror
                            </div>
                            <button 
                                type="submit" 
                                class="px-6 py-3 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-all hover:scale-105 hover:shadow-lg"
                                style="font-family: 'Poppins', sans-serif; font-weight: 600;"
                            >
                                Submit Author Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Bookmarks -->
        <a href="{{ route('bookmarks.index') }}" class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Bookmarks
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($totalBookmarks) }}
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:!bg-blue-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600 dark:!text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                {{ $bookmarksThisMonth }} added this month
            </div>
            <div class="mt-4 text-sm text-blue-600 dark:!text-blue-400 font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                View All →
            </div>
        </a>

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
                {{ $commentsThisMonth }} this month
            </div>
        </div>

        <!-- Likes -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Liked Articles
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
                Articles you've liked
            </div>
        </div>

        <!-- Reading History -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Reading History
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:!text-white mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                        {{ number_format($totalReadingHistory) }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:!bg-green-900/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-600 dark:!text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Articles you've read
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Bookmarks -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Bookmarks
                </h2>
                <a href="{{ route('bookmarks.index') }}" class="text-sm text-accent hover:text-accent-light font-semibold transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    View All →
                </a>
            </div>
            
            @if($recentBookmarks->count() > 0)
                <div class="space-y-4">
                    @foreach($recentBookmarks as $bookmark)
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('articles.show', $bookmark->article) }}" class="hover:text-accent transition-colors">
                                    {{ $bookmark->article->title }}
                                </a>
                            </h3>
                            @if($bookmark->article->category)
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                    {{ $bookmark->article->category->name }}
                                </span>
                            @endif
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Bookmarked {{ $bookmark->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No bookmarks yet. Start bookmarking articles you like!
                </p>
            @endif
        </div>

        <!-- Recent Comments -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Recent Comments
                </h2>
            </div>
            
            @if($recentComments->count() > 0)
                <div class="space-y-4">
                    @foreach($recentComments as $comment)
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('articles.show', $comment->article) }}#comment-{{ $comment->id }}" class="hover:text-accent transition-colors">
                                    {{ $comment->article->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-700 dark:!text-text-secondary mb-2 line-clamp-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ Str::limit($comment->content, 100) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No comments yet. Start engaging with articles!
                </p>
            @endif
        </div>
    </div>

    <!-- Additional Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reading History -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Reading History
                </h2>
            </div>
            
            @if($recentReadingHistory->count() > 0)
                <div class="space-y-4">
                    @foreach($recentReadingHistory as $history)
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('articles.show', $history->article) }}" class="hover:text-accent transition-colors">
                                    {{ $history->article->title }}
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($history->article->category)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                            {{ $history->article->category->name }}
                                        </span>
                                    @endif
                                    @if($history->progress > 0)
                                        <span class="ml-2 text-xs text-gray-500 dark:!text-text-tertiary">
                                            {{ $history->progress }}% read
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 dark:!text-text-tertiary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $history->last_read_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No reading history yet.
                </p>
            @endif
        </div>

        <!-- Liked Articles -->
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    Liked Articles
                </h2>
            </div>
            
            @if($likedArticles->count() > 0)
                <div class="space-y-4">
                    @foreach($likedArticles as $like)
                        <div class="p-4 bg-gray-50 dark:!bg-bg-card-hover rounded-lg hover:bg-gray-100 dark:!hover:bg-bg-card-hover transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('articles.show', $like->article) }}" class="hover:text-accent transition-colors">
                                    {{ $like->article->title }}
                                </a>
                            </h3>
                            @if($like->article->category)
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded text-xs dark:!bg-purple-900/20 dark:!text-purple-400">
                                    {{ $like->article->category->name }}
                                </span>
                            @endif
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                Liked {{ $like->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:!text-text-secondary text-center py-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No liked articles yet. Start liking articles you enjoy!
                </p>
            @endif
        </div>
    </div>
</div>
@endsection

