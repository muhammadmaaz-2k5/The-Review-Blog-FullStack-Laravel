@extends('layouts.app')

@section('title', 'Contact Message - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.contacts.index') }}" class="text-gray-600 hover:text-accent dark:!text-text-secondary dark:!hover:text-accent transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            ← Back to Messages
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg dark:!bg-green-900/20 dark:!border-green-700 dark:!text-green-400">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg dark:!bg-red-900/20 dark:!border-red-700 dark:!text-red-400">
        {{ session('error') }}
    </div>
    @endif

    <!-- Message Details -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
                Contact Message
            </h1>
            <div class="flex gap-2">
                @if($contact->status === 'unread')
                <form action="{{ route('admin.contacts.mark-read', $contact->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Mark as Read
                    </button>
                </form>
                @endif
                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Name</label>
                <p class="text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $contact->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Email</label>
                <p class="text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    <a href="mailto:{{ $contact->email }}" class="text-accent hover:underline">{{ $contact->email }}</a>
                </p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Subject</label>
                <p class="text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $contact->subject ?: 'No Subject' }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Message</label>
                <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark:!border-border-secondary">
                    <p class="text-gray-900 dark:!text-white whitespace-pre-wrap" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $contact->message }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:!border-border-secondary">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Status</label>
                    @if($contact->status === 'unread')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Unread</span>
                    @elseif($contact->status === 'read')
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Read</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:!bg-green-900/20 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Replied</span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Date</label>
                    <p class="text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $contact->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Section -->
    @if($contact->status !== 'replied')
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Reply to Message
        </h2>
        <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reply_message" class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Reply Message
                </label>
                <textarea name="reply_message" id="reply_message" rows="6" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white"
                          placeholder="Type your reply here..."></textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                Send Reply
            </button>
        </form>
    </div>
    @else
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Reply Sent
        </h2>
        <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-4 border border-gray-200 dark:!border-border-secondary">
            <p class="text-gray-900 dark:!text-white whitespace-pre-wrap mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 400;">{{ $contact->reply_message }}</p>
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Replied by {{ $contact->repliedBy->name ?? 'Admin' }} on {{ $contact->replied_at->format('F d, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
    @endif
</div>
@endsection

