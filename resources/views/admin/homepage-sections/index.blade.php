@extends('layouts.app')

@section('title', 'Homepage Sections Manager')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white" style="font-family: 'Poppins', sans-serif;">
                Homepage Sections
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Manage the sections displayed on your homepage
            </p>
        </div>
        <a href="{{ route('admin.homepage-sections.create') }}" 
           class="px-6 py-3 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors shadow-lg font-semibold"
           style="font-family: 'Poppins', sans-serif;">
            ➕ Add New Section
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded mb-6">
            <p class="text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded mb-6">
            <p class="text-red-800 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Sections List -->
    <div class="bg-white dark:bg-bg-card rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-border-primary">
                <thead class="bg-gray-50 dark:bg-bg-card-hover">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Section Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Slug
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Categories
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Articles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-bg-card divide-y divide-gray-200 dark:divide-border-primary">
                    @forelse($sections as $section)
                        <tr class="hover:bg-gray-50 dark:hover:bg-bg-card-hover transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent/10 text-accent font-bold text-sm">
                                    {{ $section->display_order + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $section->section_title ?? $section->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $section->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                    {{ $section->slug }}
                                </code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($section->category_ids && count($section->category_ids) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ count($section->category_ids) }} Category(s)
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">No categories</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ $section->articles_per_section ?? 4 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($section->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.homepage-sections.edit', $section) }}" 
                                       class="text-accent hover:text-accent-light transition-colors">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.homepage-sections.destroy', $section) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this section?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                    <p class="mt-2 text-sm">No homepage sections configured yet.</p>
                                    <p class="text-xs mt-1">Click "Add New Section" to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM7 9a1 1 0 00-1 1v3a1 1 0 001 1h1a1 1 0 100-2V7H7a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                    How It Works
                </h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Each section displays articles from selected categories</li>
                        <li>Use the display order to control which sections appear first</li>
                        <li>Only active sections will be shown on the homepage</li>
                        <li>The current active sections are: {{ $sections->where('is_active', true)->pluck('name')->implode(', ') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
