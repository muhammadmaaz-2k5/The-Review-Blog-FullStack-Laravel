@extends('layouts.app')

@section('title', 'Author Management - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Author Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage authors and their permissions
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.authors.requests') }}" class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors dark:!bg-purple-900/20 dark:!text-purple-400 dark:!hover:bg-purple-900/30" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Author Requests
            </a>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('admin.authors.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Authors List -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
        <div class="p-6 border-b border-gray-200 dark:!border-border-secondary">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Authors ({{ $authors->total() }})
            </h2>
        </div>

        @if($authors->count() > 0)
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                @foreach($authors as $author)
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="flex-shrink-0">
                                @if($author->avatar)
                                    <img src="{{ $author->avatar }}" alt="{{ $author->name }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xl">
                                        {{ strtoupper(substr($author->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $author->name }}
                                    </h3>
                                    @if($author->role === 'admin')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs dark:!bg-red-900/20 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                            Admin
                                        </span>
                                    @elseif($author->is_author || $author->role === 'author')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                            Author
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $author->email }}
                                </p>
                                <div class="flex items-center gap-6 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span><strong>{{ $author->articles_count ?? 0 }}</strong> Articles</span>
                                    <span>Joined: {{ $author->created_at->format('M j, Y') }}</span>
                                </div>
                                @if($author->bio)
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mt-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ Str::limit($author->bio, 100) }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <a href="{{ route('admin.authors.show', $author->id) }}" 
                               class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors dark:!bg-blue-900/20 dark:!text-blue-400 dark:!hover:bg-blue-900/30 text-sm font-semibold" 
                               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:!border-border-secondary">
                {{ $authors->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No authors found.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

