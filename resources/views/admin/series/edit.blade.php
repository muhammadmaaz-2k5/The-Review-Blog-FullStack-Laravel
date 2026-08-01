@extends('layouts.app')

@section('title', 'Edit Series - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.series.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Series
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Edit Series
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Update series: {{ $series->title }}
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
        <form action="{{ route('admin.series.update', $series) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $series->title) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               placeholder="Enter series title">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Slug
                        </label>
                        <input type="text" name="slug" value="{{ old('slug', $series->slug) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                               placeholder="auto-generated-from-title">
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Leave empty to auto-generate from title</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Description
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                  placeholder="Describe what this series is about...">{{ old('description', $series->description) }}</textarea>
                    </div>

                    <!-- Featured Image -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Featured Image
                        </label>
                        <input type="file" name="featured_image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Upload to replace current image. Max 2MB.</p>
                        @if($series->featured_image)
                            <div class="mt-2">
                                @php
                                    $imageUrl = str_starts_with($series->featured_image, 'http') 
                                        ? $series->featured_image 
                                        : asset('storage/' . $series->featured_image);
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Current featured image" class="w-32 h-32 object-cover rounded" onerror="this.style.display='none'">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Status
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $series->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-accent focus:ring-accent">
                            <span class="text-sm font-semibold text-gray-700 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Active</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Only active series are visible on the frontend</p>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Sort Order
                        </label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $series->sort_order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:!text-text-tertiary">Lower numbers appear first</p>
                    </div>

                    <!-- Series Info -->
                    <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Series Statistics</p>
                        <p class="text-lg font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                            {{ $series->articles()->count() }} Articles
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Update Series
                </button>
                <a href="{{ route('admin.series.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

