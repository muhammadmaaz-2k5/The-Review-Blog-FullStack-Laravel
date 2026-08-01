@extends('layouts.app')

@section('title', 'Admin - View Tip')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.tips.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors mb-4 inline-block" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            ← Back to Tips
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            View Tip
        </h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:!bg-green-900/20 dark:!border-green-600 dark:!text-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 space-y-6">
        <!-- Tip Details -->
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                {{ $tip->subject }}
            </h2>
            <div class="flex items-center gap-4 mb-4">
                @php
                    $statusColors = [
                        'pending' => 'bg-orange-100 text-orange-800 dark:!bg-orange-900/20 dark:!text-orange-400',
                        'reviewed' => 'bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400',
                        'approved' => 'bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400',
                        'rejected' => 'bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400',
                    ];
                @endphp
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$tip->status] ?? 'bg-gray-100 text-gray-800' }}" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    {{ ucfirst($tip->status) }}
                </span>
                <span class="text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Submitted: {{ $tip->created_at->format('M d, Y h:i A') }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Content
            </h3>
            <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark:!border-border-secondary">
                <p class="text-gray-700 dark:!text-text-secondary whitespace-pre-wrap" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    {{ $tip->content }}
                </p>
            </div>
        </div>

        <!-- Submitter Info -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Submitted By
            </h3>
            <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark:!border-border-secondary">
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Name:</span>
                        <span class="text-sm text-gray-900 dark:!text-white ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                            {{ $tip->name ?: ($tip->user ? $tip->user->name : 'Anonymous') }}
                        </span>
                    </div>
                    @if($tip->email)
                        <div>
                            <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Email:</span>
                            <span class="text-sm text-gray-900 dark:!text-white ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                <a href="mailto:{{ $tip->email }}" class="text-accent hover:text-accent-light">{{ $tip->email }}</a>
                            </span>
                        </div>
                    @endif
                    @if($tip->user)
                        <div>
                            <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">User Account:</span>
                            <span class="text-sm text-gray-900 dark:!text-white ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                {{ $tip->user->name }} (ID: {{ $tip->user->id }})
                            </span>
                        </div>
                    @endif
                    @if($tip->ip_address)
                        <div>
                            <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">IP Address:</span>
                            <span class="text-sm text-gray-900 dark:!text-white ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                {{ $tip->ip_address }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Review Info -->
        @if($tip->reviewed_at)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Review Information
                </h3>
                <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark:!border-border-secondary">
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Reviewed By:</span>
                            <span class="text-sm text-gray-900 dark:!text-white ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                {{ $tip->reviewer ? $tip->reviewer->name : 'Unknown' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Reviewed At:</span>
                            <span class="text-sm text-gray-900 dark:!text-white ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                                {{ $tip->reviewed_at->format('M d, Y h:i A') }}
                            </span>
                        </div>
                        @if($tip->admin_notes)
                            <div>
                                <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Admin Notes:</span>
                                <p class="text-sm text-gray-900 dark:!text-white mt-1 whitespace-pre-wrap" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $tip->admin_notes }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="border-t border-gray-200 dark:!border-border-secondary pt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Update Status
            </h3>
            <form action="{{ route('admin.tips.update-status', $tip) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Status
                    </label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                            style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        <option value="pending" {{ $tip->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ $tip->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="approved" {{ $tip->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $tip->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label for="admin_notes" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Admin Notes (Optional)
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                              placeholder="Add any notes about this tip...">{{ old('admin_notes', $tip->admin_notes) }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Update Status
                    </button>
                    <form action="{{ route('admin.tips.destroy', $tip) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this tip?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                            Delete Tip
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

