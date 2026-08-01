@extends('layouts.app')

@section('title', 'Admin - Category Management')

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
                Category Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage article categories
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Dashboard
            </a>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Add New Category
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

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                            {{ $category->name }}
                        </h3>
                        @if($category->description)
                            <p class="text-sm text-gray-600 dark:!text-text-secondary mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                        @endif
                    </div>
                    @if($category->image)
                        <div class="w-16 h-16 rounded overflow-hidden bg-gray-200 dark:!bg-gray-700 flex-shrink-0 ml-4">
                            @php
                                $imageUrl = str_starts_with($category->image, 'http') 
                                    ? $category->image 
                                    : asset('storage/' . $category->image);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $category->name }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/100?text=No+Image'">
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Articles</p>
                        <p class="text-lg font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                            {{ number_format($category->articles_count) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Status</p>
                        @if($category->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs dark:!bg-green-900/20 dark:!text-green-400">Active</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs dark:!bg-gray-800 dark:!text-gray-400">Inactive</span>
                        @endif
                    </div>
                    @if($category->color)
                        <div>
                            <p class="text-xs text-gray-500 dark:!text-text-tertiary mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Color</p>
                            <div class="w-8 h-8 rounded" style="background-color: {{ $category->color }}"></div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:!border-border-secondary">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="flex-1 px-4 py-2 bg-accent hover:bg-accent-light text-white text-center rounded-lg transition-colors text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-600 dark:!text-text-secondary mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No categories found. <a href="{{ route('admin.categories.create') }}" class="text-accent hover:underline">Create your first category</a>
                </p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

