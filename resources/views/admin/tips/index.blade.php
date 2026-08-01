@extends('layouts.app')

@section('title', 'Admin - Tips')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Tips Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage tips submitted by users
            </p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4">
            <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Total Tips</div>
            <div class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4">
            <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Pending</div>
            <div class="text-2xl font-bold text-orange-600 dark:!text-orange-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4">
            <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Reviewed</div>
            <div class="text-2xl font-bold text-blue-600 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['reviewed'] }}</div>
        </div>
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4">
            <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Approved</div>
            <div class="text-2xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['approved'] }}</div>
        </div>
        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4">
            <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Rejected</div>
            <div class="text-2xl font-bold text-red-600 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['rejected'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.tips.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search tips..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                       style="font-family: 'Poppins', sans-serif; font-weight: 400;">
            </div>
            <div class="min-w-[150px]">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                        style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.tips.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tips Table -->
    <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:!divide-border-secondary">
                <thead class="bg-gray-50 dark:!bg-bg-card-hover">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Subject
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Submitted By
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Date
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:!text-text-secondary uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:!bg-bg-card divide-y divide-gray-200 dark:!divide-border-secondary">
                    @forelse($tips as $tip)
                        <tr class="hover:bg-gray-50 dark:!hover:bg-bg-card-hover">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    {{ Str::limit($tip->subject, 50) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ Str::limit(strip_tags($tip->content), 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                    {{ $tip->name ?: ($tip->user ? $tip->user->name : 'Anonymous') }}
                                </div>
                                @if($tip->email)
                                    <div class="text-xs text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        {{ $tip->email }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-100 text-orange-800 dark:!bg-orange-900/20 dark:!text-orange-400',
                                        'reviewed' => 'bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400',
                                        'approved' => 'bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400',
                                        'rejected' => 'bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$tip->status] ?? 'bg-gray-100 text-gray-800' }}" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    {{ ucfirst($tip->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ $tip->created_at->format('M d, Y') }}
                                <div class="text-xs">{{ $tip->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tips.show', $tip) }}" class="text-blue-600 hover:text-blue-900 dark:!text-blue-400 dark:!hover:text-blue-300" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                No tips found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tips->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:!border-border-secondary">
                {{ $tips->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

