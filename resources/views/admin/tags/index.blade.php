@extends('layouts.app')

@section('title', 'Admin - Tag Management')

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
                Tag Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage article tags
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Dashboard
            </a>
            <a href="{{ route('admin.tags.create') }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Add New Tag
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

    <!-- Search -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('admin.tags.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tags..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.tags.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Tags Table -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:!divide-gray-700">
                <thead class="bg-gray-50 dark:!bg-bg-card-hover">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Tag</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Articles</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:!bg-bg-card divide-y divide-gray-200 dark:!divide-gray-700">
                    @forelse($tags as $tag)
                        <tr class="hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    {{ $tag->name }}
                                </div>
                                @if($tag->description)
                                    <div class="text-xs text-gray-500 dark:!text-text-tertiary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        {{ Str::limit($tag->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:!text-text-secondary font-mono" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $tag->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">
                                    {{ number_format($tag->articles_count) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ $tag->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="text-accent hover:text-accent-light" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:!text-red-400 dark:!hover:text-red-300" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                No tags found. <a href="{{ route('admin.tags.create') }}" class="text-accent hover:underline">Create your first tag</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($tags->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:!border-border-secondary">
            {{ $tags->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

