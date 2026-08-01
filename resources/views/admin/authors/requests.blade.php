@extends('layouts.app')

@section('title', 'Author Requests - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('admin.authors.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    ← Back to Authors
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Author Requests
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage requests to become an author
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.authors.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                All Authors
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Status Filter -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <div class="flex gap-4">
            <a href="{{ route('admin.authors.requests', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:!bg-yellow-900/20 dark:!text-yellow-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card' }}"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Pending
            </a>
            <a href="{{ route('admin.authors.requests', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ $status === 'approved' ? 'bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card' }}"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Approved
            </a>
            <a href="{{ route('admin.authors.requests', ['status' => 'rejected']) }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ $status === 'rejected' ? 'bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card' }}"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Rejected
            </a>
            <a href="{{ route('admin.authors.requests') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ !$status ? 'bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:!bg-bg-card-hover dark:!text-white dark:!hover:bg-bg-card' }}"
               style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                All
            </a>
        </div>
    </div>

    <!-- Requests List -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary">
        @if($requests->count() > 0)
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                @foreach($requests as $request)
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="flex-shrink-0">
                                @if($request->user->avatar)
                                    <img src="{{ $request->user->avatar }}" alt="{{ $request->user->name }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-xl">
                                        {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $request->user->name }}
                                    </h3>
                                    <span class="px-2 py-1 bg-{{ $request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red') }}-100 text-{{ $request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red') }}-800 rounded text-xs dark:!bg-{{ $request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red') }}-900/20 dark:!text-{{ $request->status === 'pending' ? 'yellow' : ($request->status === 'approved' ? 'green' : 'red') }}-400">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:!text-text-secondary mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $request->user->email }}
                                </p>
                                @if($request->message)
                                <div class="mb-2 p-3 bg-gray-50 dark:!bg-bg-card-hover rounded-lg">
                                    <p class="text-sm text-gray-700 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        <strong>Message:</strong> {{ $request->message }}
                                    </p>
                                </div>
                                @endif
                                @if($request->admin_notes)
                                <div class="mb-2 p-3 bg-yellow-50 dark:!bg-yellow-900/10 rounded-lg">
                                    <p class="text-sm text-yellow-700 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        <strong>Admin Notes:</strong> {{ $request->admin_notes }}
                                    </p>
                                </div>
                                @endif
                                <div class="flex items-center gap-4 text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    <span>Submitted: {{ $request->created_at->format('M j, Y g:i A') }}</span>
                                    @if($request->reviewed_at)
                                        <span>Reviewed: {{ $request->reviewed_at->format('M j, Y g:i A') }}</span>
                                        @if($request->reviewer)
                                            <span>by {{ $request->reviewer->name }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($request->status === 'pending')
                        <div class="flex gap-2 ml-4">
                            <form action="{{ route('admin.authors.requests.approve', $request) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-semibold" 
                                        style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Approve
                                </button>
                            </form>
                            <button onclick="document.getElementById('reject-form-{{ $request->id }}').classList.toggle('hidden')" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-semibold" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Reject
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    @if($request->status === 'pending')
                    <div id="reject-form-{{ $request->id }}" class="hidden mt-4 pt-4 border-t border-gray-200 dark:!border-border-secondary">
                        <form action="{{ route('admin.authors.requests.reject', $request) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    Admin Notes (Optional)
                                </label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                                          placeholder="Add notes about why this request was rejected..."></textarea>
                            </div>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-semibold" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Confirm Rejection
                            </button>
                            <button type="button" onclick="document.getElementById('reject-form-{{ $request->id }}').classList.add('hidden')" 
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-semibold dark:!bg-bg-card-hover dark:!text-white ml-2" 
                                    style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                Cancel
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:!border-border-secondary">
                {{ $requests->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No {{ $status ?? 'author requests' }} found.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

