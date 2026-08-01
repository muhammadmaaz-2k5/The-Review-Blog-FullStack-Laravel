@extends('layouts.app')

@section('title', 'Edit Ad')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('admin.ads.index') }}" class="text-gray-600 hover:text-accent dark!:text-text-secondary dark!:hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                &larr; Back to Ads
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark!:text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Edit Ad: {{ $ad->name }}
        </h1>
        <p class="text-gray-600 dark!:text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            Update ad code, placement, and settings
        </p>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark!:bg-red-900/20 dark!:border-red-700 dark!:text-red-400">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark!:bg-bg-card rounded-lg border border-gray-200 dark!:border-border-secondary overflow-hidden">
        <form action="{{ route('admin.ads.update', $ad) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Ad Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $ad->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $ad->slug) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Placement <span class="text-red-500">*</span></label>
                    <select name="placement" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                        <option value="">Select placement...</option>
                        @foreach($placementOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('placement', $ad->placement) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Ad Type <span class="text-red-500">*</span></label>
                    <select name="type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                        @foreach($typeOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $ad->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $ad->sort_order) }}" min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                </div>
            </div>

            <!-- Ad Code -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Ad Code (HTML/JS)</label>
                <textarea name="ad_code" rows="12"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm font-mono">{{ old('ad_code', $ad->ad_code) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Paste the complete ad code from Adsterra, AdSense, or any other provider</p>
            </div>

            <!-- Ad Preview -->
            @if($ad->ad_code)
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Current Ad Code Preview</label>
                <div class="bg-gray-50 dark!:bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark!:border-border-secondary">
                    <pre class="text-xs text-gray-600 dark!:text-text-secondary overflow-x-auto whitespace-pre-wrap max-h-40 overflow-y-auto">{{ Str::limit($ad->ad_code, 500) }}</pre>
                </div>
            </div>
            @endif

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 dark!:text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Description / Notes</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark!:bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">{{ old('description', $ad->description) }}</textarea>
            </div>

            <!-- Active Toggle -->
            <div class="mb-6 flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                <label for="is_active" class="text-sm font-semibold text-gray-700 dark!:text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Active (show this ad on the site)</label>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark!:border-border-secondary">
                <button type="submit" class="px-6 py-2.5 bg-accent hover:bg-accent-light text-white font-semibold rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Update Ad
                </button>
                <a href="{{ route('admin.ads.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark!:bg-bg-card dark!:text-white dark!:hover:bg-bg-card-hover font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
