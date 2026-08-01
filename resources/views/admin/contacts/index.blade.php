@extends('layouts.app')

@section('title', 'Admin - Contact Messages')

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
                Contact Messages
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage contact form submissions
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
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Unread</p>
            <p class="text-2xl font-bold text-red-600 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['unread'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Read</p>
            <p class="text-2xl font-bold text-blue-600 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['read'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Replied</p>
            <p class="text-2xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['replied'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Status</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Filter
                </button>
                <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Messages Table -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <form id="bulkForm" method="POST" action="{{ route('admin.contacts.bulk-action') }}">
            @csrf
            <div class="p-4 border-b border-gray-200 dark:!border-border-secondary flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <select name="action" id="bulkAction" class="px-4 py-2 border border-gray-300 rounded-lg dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                        <option value="">Bulk Actions</option>
                        <option value="mark_read">Mark as Read</option>
                        <option value="mark_unread">Mark as Unread</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Apply
                    </button>
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:!divide-border-secondary">
                <thead class="bg-gray-50 dark:!bg-bg-card-hover">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-accent focus:ring-accent">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:!bg-bg-card divide-y divide-gray-200 dark:!divide-border-secondary">
                    @forelse($messages as $message)
                    <tr class="hover:bg-gray-50 dark:!hover:bg-bg-card-hover {{ $message->status === 'unread' ? 'bg-blue-50 dark:!bg-blue-900/10' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="messages[]" value="{{ $message->id }}" class="message-checkbox rounded border-gray-300 text-accent focus:ring-accent">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                {{ $message->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ $message->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                {{ $message->subject ?: 'No Subject' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($message->status === 'unread')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Unread</span>
                            @elseif($message->status === 'read')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Read</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Replied</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            {{ $message->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.contacts.show', $message->id) }}" class="text-accent hover:text-accent-light mr-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">View</a>
                            <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            No contact messages found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.message-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
</script>
@endsection

