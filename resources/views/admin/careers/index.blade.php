@extends('layouts.app')

@section('title', 'Admin - Career Management')

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
                Career Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage job postings and career opportunities
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Dashboard
            </a>
            <a href="{{ route('admin.careers.create') }}" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Post New Job
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4 mb-6">
        <form method="GET" action="{{ route('admin.careers.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs..." class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                <option value="">All Types</option>
                <option value="full-time" {{ request('type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                <option value="part-time" {{ request('type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                <option value="remote" {{ request('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Internship</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'type']))
            <a href="{{ route('admin.careers.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card-hover dark:!text-white">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Careers Table -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:!bg-bg-card-hover">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:!text-text-secondary">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:!text-text-secondary">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:!text-text-secondary">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:!text-text-secondary">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:!text-text-secondary">Deadline</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:!text-text-secondary">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:!divide-border-primary">
                    @forelse($careers as $career)
                    <tr class="hover:bg-gray-50 dark:!hover:bg-bg-card-hover">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($career->is_featured)
                                <span class="mr-2 px-2 py-1 text-xs bg-accent text-white rounded">Featured</span>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:!text-white">{{ $career->title }}</div>
                                    @if($career->department)
                                    <div class="text-xs text-gray-500 dark:!text-text-secondary">{{ $career->department }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:!text-white capitalize">{{ str_replace('-', ' ', $career->type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:!text-white">{{ $career->location ?? 'Not specified' }}</td>
                        <td class="px-6 py-4">
                            @if($career->is_active)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded dark:!bg-green-900/20 dark:!text-green-400">Active</span>
                            @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded dark:!bg-red-900/20 dark:!text-red-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:!text-white">
                            @if($career->application_deadline)
                                {{ $career->application_deadline->format('M d, Y') }}
                                @if($career->isDeadlinePassed())
                                <span class="text-red-500 text-xs">(Expired)</span>
                                @endif
                            @else
                                No deadline
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('careers.show', $career->slug) }}" target="_blank" class="text-accent hover:text-accent-light" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.careers.edit', $career) }}" class="text-blue-600 hover:text-blue-800 dark:!text-blue-400" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.careers.destroy', $career) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this career posting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:!text-red-400" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:!text-text-secondary">
                            No careers found. <a href="{{ route('admin.careers.create') }}" class="text-accent hover:underline">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($careers->hasPages())
    <div class="mt-6">
        {{ $careers->links() }}
    </div>
    @endif
</div>
@endsection

