@extends('layouts.app')

@section('title', 'Create Homepage Section - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.homepage-sections.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Homepage Sections
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Create Homepage Section
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Add a new section to the homepage
            </p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <form action="{{ route('admin.homepage-sections.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="e.g., Biographies">
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="e.g., biographies">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">URL-friendly identifier for this section</p>
                </div>

                <!-- Section Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Section Title
                    </label>
                    <input type="text" name="section_title" value="{{ old('section_title') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="Display title (defaults to Name if empty)">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">The title shown on the homepage. Leave empty to use the section name.</p>
                </div>

                <!-- Display Order -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Display Order
                    </label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="0">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Lower numbers appear first on the homepage</p>
                </div>

                <!-- Articles Per Section -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Articles Per Section
                    </label>
                    <input type="number" name="articles_per_section" value="{{ old('articles_per_section', 4) }}" min="1" max="20"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                           placeholder="4">
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Number of articles to display in this section (1-20)</p>
                </div>

                <!-- Categories -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Categories
                    </label>
                    <p class="mb-3 text-xs text-gray-500 dark:!text-text-tertiary">Select which categories to pull articles from for this section</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-64 overflow-y-auto p-3 border border-gray-200 dark:!border-border-primary rounded-lg">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                       {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-accent focus:ring-accent">
                                <span class="text-sm text-gray-700 dark:!text-white">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @if($categories->isEmpty())
                        <p class="mt-2 text-sm text-gray-400">No active categories available.</p>
                    @endif
                </div>

                <!-- Is Active -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Active</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary ml-6">Only active sections are displayed on the homepage</p>
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Create Section
                </button>
                <a href="{{ route('admin.homepage-sections.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
