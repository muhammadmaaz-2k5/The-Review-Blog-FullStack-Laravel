@extends('layouts.app')

@section('title', 'View Homepage Section - Admin')

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
                {{ $homepageSection->section_title ?? $homepageSection->name }}
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Homepage section details
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.homepage-sections.edit', $homepageSection) }}" 
               class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" 
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                ✏️ Edit
            </a>
        </div>
    </div>

    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Name
                </label>
                <p class="text-gray-900 dark:!text-white font-medium">{{ $homepageSection->name }}</p>
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Slug
                </label>
                <code class="text-sm bg-gray-100 dark:!bg-gray-800 px-2 py-1 rounded text-gray-900 dark:!text-white">{{ $homepageSection->slug }}</code>
            </div>

            <!-- Section Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Section Title
                </label>
                <p class="text-gray-900 dark:!text-white font-medium">{{ $homepageSection->section_title ?? 'Same as name' }}</p>
            </div>

            <!-- Display Order -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Display Order
                </label>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent/10 text-accent font-bold text-sm">
                    {{ $homepageSection->display_order }}
                </span>
            </div>

            <!-- Articles Per Section -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Articles Per Section
                </label>
                <p class="text-gray-900 dark:!text-white font-medium">{{ $homepageSection->articles_per_section ?? 4 }}</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Status
                </label>
                @if($homepageSection->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:!bg-green-900/30 dark:!text-green-300">
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:!bg-gray-800 dark:!text-gray-300">
                        Inactive
                    </span>
                @endif
            </div>

            <!-- Categories -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                    Categories
                </label>
                @if($homepageSection->category_ids && count($homepageSection->category_ids) > 0)
                    <div class="flex flex-wrap gap-2">
                        @php
                            $sectionCategories = \App\Models\Category::whereIn('id', $homepageSection->category_ids)->get();
                        @endphp
                        @foreach($sectionCategories as $cat)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:!bg-blue-900/30 dark:!text-blue-300">
                                {{ $cat->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm">No categories assigned</p>
                @endif
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:!border-border-primary">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                        Created At
                    </label>
                    <p class="text-sm text-gray-700 dark:!text-gray-300">{{ $homepageSection->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:!text-text-tertiary uppercase tracking-wider mb-1" style="font-family: 'Poppins', sans-serif;">
                        Updated At
                    </label>
                    <p class="text-sm text-gray-700 dark:!text-gray-300">{{ $homepageSection->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
