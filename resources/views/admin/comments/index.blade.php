@extends('layouts.app')

@section('title', 'Admin - Comments Moderation')

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
                Comments Moderation
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Moderate and manage article comments
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Total</p>
            <p class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Approved</p>
            <p class="text-2xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Spam</p>
            <p class="text-2xl font-bold text-red-600 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['spam'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('admin.comments.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search comments..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Filter
                </button>
                <a href="{{ route('admin.comments.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <form id="bulkForm" method="POST" action="{{ route('admin.comments.bulk-action') }}">
            @csrf
            <div class="p-4 border-b border-gray-200 dark:!border-border-secondary flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <select name="action" id="bulkAction" class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                        <option value="">Bulk Actions</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                        <option value="spam">Mark as Spam</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Apply
                    </button>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:!divide-border-secondary">
                @forelse($comments as $comment)
                <div class="p-6 hover:bg-gray-50 dark:!hover:bg-bg-card-hover {{ $comment->status === 'pending' ? 'bg-yellow-50 dark:!bg-yellow-900/10' : '' }}">
                    <div class="flex items-start gap-4">
                        <input type="checkbox" name="comments[]" value="{{ $comment->id }}" class="comment-checkbox mt-1 rounded border-gray-300 text-accent focus:ring-accent">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                        {{ $comment->name }}
                                    </div>
                                    <span class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                        {{ $comment->email }}
                                    </span>
                                    @if($comment->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:!bg-yellow-900/20 dark:!text-yellow-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Pending</span>
                                    @elseif($comment->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Approved</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Spam</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $comment->created_at->format('M d, Y g:i A') }}
                                </div>
                            </div>
                            <p class="text-gray-700 dark:!text-text-secondary mb-3 whitespace-pre-wrap" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $comment->content }}</p>
                            <div class="text-sm text-gray-600 dark:!text-text-secondary mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                On: <a href="{{ route('articles.show', $comment->article->slug) }}" class="text-accent hover:underline">{{ $comment->article->title }}</a>
                            </div>
                            <div class="flex gap-2">
                                @if($comment->status === 'pending')
                                <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-sm bg-green-600 hover:bg-green-700 text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Approve</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.comments.mark-spam', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Mark Spam</button>
                                </form>
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    No comments found.
                </div>
                @endforelse
            </div>
        </form>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>

<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.comment-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
</script>
@endsection

