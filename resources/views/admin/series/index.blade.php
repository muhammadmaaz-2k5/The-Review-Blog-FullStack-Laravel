@extends('layouts.app')

@section('title', 'Series Management - Admin')

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
                Series Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage article series and collections
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Dashboard
            </a>
            <a href="{{ route('admin.series.create') }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Add New Series
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
        <form method="GET" action="{{ route('admin.series.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Series List -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
        <div class="p-6 border-b border-gray-200 dark:!border-border-secondary">
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Series ({{ $series->total() }})
            </h2>
        </div>

        @if($series->count() > 0)
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                @foreach($series as $ser)
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            @if($ser->featured_image)
                                <div class="flex-shrink-0">
                                    <img src="{{ $ser->featured_image_url }}" alt="{{ $ser->title }}" class="w-20 h-20 rounded-lg object-cover">
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $ser->title }}
                                    </h3>
                                    @if($ser->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs dark:!bg-gray-800 dark:!text-gray-400" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                                @if($ser->description)
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ Str::limit($ser->description, 150) }}
                                </p>
                                @endif
                                <div class="flex items-center gap-6 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span><strong>{{ $ser->articles_count ?? 0 }}</strong> Articles</span>
                                    @if($ser->author)
                                        <span>By {{ $ser->author->name }}</span>
                                    @endif
                                    <span>Created: {{ $ser->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <a href="{{ route('admin.series.show', $ser) }}" 
                               class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors dark:!bg-blue-900/20 dark:!text-blue-400 dark:!hover:bg-blue-900/30 text-sm font-semibold" 
                               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                View
                            </a>
                            <a href="{{ route('admin.series.edit', $ser) }}" 
                               class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card text-sm font-semibold" 
                               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:!border-border-secondary">
                {{ $series->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No series found. <a href="{{ route('admin.series.create') }}" class="text-accent hover:underline">Create your first series</a>
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

