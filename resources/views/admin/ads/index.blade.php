@extends('layouts.app')

@section('title', 'Ad Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark!:hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    &larr; Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Ad Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage all Adsterra, AdSense, and custom ads across the site
            </p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <form method="POST" action="{{ route('admin.ads.toggle-all') }}" class="inline">
                @csrf
                <input type="hidden" name="enabled" value="{{ \App\Models\Ad::where('is_active', true)->exists() ? '0' : '1' }}">
                <button type="submit" class="px-4 py-2 {{ \App\Models\Ad::where('is_active', true)->exists() ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ \App\Models\Ad::where('is_active', true)->exists() ? 'Disable All Ads' : 'Enable All Ads' }}
                </button>
            </form>
            <a href="{{ route('admin.ads.create') }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                + Add New Ad
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark!:border-green-700 dark!:text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('admin.ads.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ads..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Placement</label>
                <select name="placement" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                    <option value="">All Placements</option>
                    @foreach($placementOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('placement') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                    <option value="">All Types</option>
                    @foreach($typeOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark!:border-border-primary dark!:text-white text-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors text-sm font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Filter
                </button>
                <a href="{{ route('admin.ads.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark!:text-white dark!:hover:bg-bg-card-hover ml-2 text-sm font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Bar -->
    <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 text-center">
            <p class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ \App\Models\Ad::count() }}</p>
            <p class="text-xs text-gray-500 dark:!text-text-secondary uppercase tracking-wider font-semibold">Total Ads</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 text-center">
            <p class="text-2xl font-bold text-green-600" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ \App\Models\Ad::where('is_active', true)->count() }}</p>
            <p class="text-xs text-gray-500 dark:!text-text-secondary uppercase tracking-wider font-semibold">Active</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 text-center">
            <p class="text-2xl font-bold text-red-600" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ \App\Models\Ad::where('is_active', false)->count() }}</p>
            <p class="text-xs text-gray-500 dark:!text-text-secondary uppercase tracking-wider font-semibold">Inactive</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 text-center">
            <p class="text-2xl font-bold text-accent" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ \App\Models\Ad::distinct('placement')->count('placement') }}</p>
            <p class="text-xs text-gray-500 dark:!text-text-secondary uppercase tracking-wider font-semibold">Placements</p>
        </div>
    </div>

    <!-- Ads Table -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark!:divide-gray-700">
                <thead class="bg-gray-50 dark!:bg-bg-card-hover">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark!:text-text-secondary uppercase tracking-wider">Ad</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark!:text-text-secondary uppercase tracking-wider">Placement</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark!:text-text-secondary uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark!:text-text-secondary uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark!:text-text-secondary uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark!:text-text-secondary uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark!:bg-bg-card divide-y divide-gray-200 dark!:divide-gray-700">
                    @forelse($ads as $ad)
                        <tr class="hover:bg-gray-50 dark!:hover:bg-bg-card-hover transition-colors">
                            <td class="px-4 py-4">
                                <div class="text-sm font-bold text-gray-900 dark!:text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">{{ $ad->name }}</div>
                                <div class="text-xs text-gray-500 dark!:text-text-secondary">{{ $ad->slug }}</div>
                                @if($ad->description)
                                    <div class="text-xs text-gray-400 dark!:text-text-tertiary mt-1 truncate max-w-[250px]">{{ $ad->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark!:bg-blue-900/20 dark!:text-blue-400">
                                    {{ $placementOptions[$ad->placement] ?? $ad->placement }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $ad->type === 'adsense' ? 'bg-yellow-100 text-yellow-800 dark!:bg-yellow-900/20 dark!:text-yellow-400' :
                                       (str_starts_with($ad->type, 'adsterra') ? 'bg-purple-100 text-purple-800 dark!:bg-purple-900/20 dark!:text-purple-400' :
                                       'bg-gray-100 text-gray-800 dark!:bg-gray-700 dark!:text-gray-300') }}">
                                    {{ $typeOptions[$ad->type] ?? $ad->type }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <form action="{{ route('admin.ads.toggle', $ad) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2
                                        {{ $ad->is_active ? 'bg-green-500' : 'bg-gray-300 dark!:bg-gray-600' }}" role="switch" aria-checked="{{ $ad->is_active }}">
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                            {{ $ad->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark!:text-text-secondary text-center">
                                {{ $ad->sort_order }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.ads.edit', $ad) }}" class="text-accent hover:text-accent-light text-sm font-semibold">Edit</a>
                                    <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" onsubmit="return confirm('Delete this ad?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark!:text-text-secondary">
                                No ads found. <a href="{{ route('admin.ads.create') }}" class="text-accent hover:text-accent-light font-semibold">Add your first ad</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $ads->links() }}
        </div>
    </div>
</div>
@endsection
