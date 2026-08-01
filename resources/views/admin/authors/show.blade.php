@extends('layouts.app')

@section('title', 'Author Details - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.authors.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Authors
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $author->name }}
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Author Profile & Statistics
            </p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.authors.remove-status', $author) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove author status from this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors dark:!bg-red-900/20 dark:!text-red-400 dark:!hover:bg-red-900/30" 
                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Remove Author Status
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Author Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Author Profile Card -->
        <div class="lg:col-span-1 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
            <div class="text-center">
                @if($author->avatar)
                    <img src="{{ $author->avatar }}" alt="{{ $author->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4">
                @else
                    <div class="w-24 h-24 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-3xl mx-auto mb-4">
                        {{ strtoupper(substr($author->name, 0, 1)) }}
                    </div>
                @endif
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ $author->name }}
                </h2>
                <p class="text-gray-600 dark:!text-text-secondary mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $author->email }}
                </p>
                
                <div class="flex justify-center gap-2 mb-4">
                    @if($author->role === 'admin')
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm dark:!bg-red-900/20 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Admin
                        </span>
                    @elseif($author->is_author || $author->role === 'author')
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Author
                        </span>
                    @endif
                </div>

                @if($author->bio)
                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $author->bio }}
                </p>
                @endif

                <div class="text-sm text-gray-500 dark:!text-text-secondary space-y-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    <p>Joined: {{ $author->created_at->format('M j, Y') }}</p>
                    @if($author->email_verified_at)
                        <p class="text-green-600 dark:!text-green-400">✓ Email Verified</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Total Articles
                </p>
                <p class="text-4xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ number_format($stats['total_articles']) }}
                </p>
            </div>
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Published
                </p>
                <p class="text-4xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ number_format($stats['published_articles']) }}
                </p>
            </div>
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Total Views
                </p>
                <p class="text-4xl font-bold text-blue-600 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ number_format($stats['total_views']) }}
                </p>
            </div>
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
                <p class="text-sm font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Total Likes
                </p>
                <p class="text-4xl font-bold text-red-600 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                    {{ number_format($stats['total_likes']) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Permission Management -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Permissions
        </h2>
        <form action="{{ route('admin.authors.update-permissions', $author) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_author" value="1" {{ ($author->is_author || $author->role === 'author' || $author->role === 'admin') ? 'checked' : '' }} 
                               class="w-5 h-5 text-accent border-gray-300 rounded focus:ring-accent">
                        <span class="text-gray-900 dark:!text-white font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Author Status
                        </span>
                    </label>
                    <p class="text-sm text-gray-600 dark:!text-text-secondary mt-1 ml-8" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        Allow user to create and manage articles
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Role
                    </label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                        <option value="user" {{ $author->role === 'user' ? 'selected' : '' }}>User</option>
                        <option value="author" {{ $author->role === 'author' ? 'selected' : '' }}>Author</option>
                        <option value="admin" {{ $author->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Update Permissions
                </button>
            </div>
        </form>
    </div>

    <!-- Author Articles -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
        <div class="p-6 border-b border-gray-200 dark:!border-border-secondary">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Articles ({{ $articles->total() }})
            </h2>
        </div>

        @if($articles->count() > 0)
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                @foreach($articles as $article)
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="hover:text-accent">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <div class="flex items-center gap-4 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                <span class="px-2 py-1 bg-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-100 text-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-800 rounded text-xs dark:!bg-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-900/20 dark:!text-{{ $article->status === 'published' ? 'green' : ($article->status === 'draft' ? 'yellow' : 'blue') }}-400">
                                    {{ ucfirst($article->status) }}
                                </span>
                                <span>{{ $article->views }} views</span>
                                <span>{{ $article->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.articles.edit', $article) }}" 
                           class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors dark:!bg-blue-900/20 dark:!text-blue-400 dark:!hover:bg-blue-900/30 text-sm font-semibold ml-4" 
                           style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Edit
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:!border-border-secondary">
                {{ $articles->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No articles found.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

