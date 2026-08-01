@extends('layouts.app')

@section('title', 'Admin - User Management')

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
                User Management
            </h1>
            <p class="text-gray-600 dark:!text-text-secondary mt-1" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                Manage users, roles, and permissions
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
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Total Users</p>
            <p class="text-2xl font-bold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Admins</p>
            <p class="text-2xl font-bold text-red-600 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['admins'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Authors</p>
            <p class="text-2xl font-bold text-blue-600 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['authors'] }}</p>
        </div>
        <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
            <p class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Regular Users</p>
            <p class="text-2xl font-bold text-green-600 dark:!text-green-400" style="font-family: 'Poppins', sans-serif; font-weight: 700;">{{ $stats['regular'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Role</label>
                <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="author" {{ request('role') === 'author' ? 'selected' : '' }}>Author</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-semibold text-gray-700 dark:!text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Author Status</label>
                <select name="is_author" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent dark:!bg-bg-card-hover dark:!border-border-primary dark:!text-white">
                    <option value="">All</option>
                    <option value="1" {{ request('is_author') === '1' ? 'selected' : '' }}>Authors Only</option>
                    <option value="0" {{ request('is_author') === '0' ? 'selected' : '' }}>Non-Authors</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent hover:bg-accent-light text-white rounded-lg transition-colors" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg transition-colors dark:!bg-bg-card dark:!text-white dark:!hover:bg-bg-card-hover ml-2" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:!bg-bg-card rounded-lg border border-gray-200 dark:!border-border-secondary overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:!divide-border-secondary">
            <thead class="bg-gray-50 dark:!bg-bg-card-hover">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Articles</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:!text-white uppercase tracking-wider" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:!bg-bg-card divide-y divide-gray-200 dark:!divide-border-secondary">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:!hover:bg-bg-card-hover">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-accent flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900 dark:!text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                                    {{ $user->name }}
                                </div>
                                <div class="text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->role === 'admin')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:!bg-red-900/20 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Admin</span>
                        @elseif($user->is_author || $user->role === 'author')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:!bg-blue-900/20 dark:!text-blue-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Author</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:!bg-gray-900/20 dark:!text-gray-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        {{ $user->articles_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-accent hover:text-accent-light mr-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">View</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 dark:!text-blue-400 mr-3" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Edit</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 dark:!text-red-400" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:!text-text-secondary" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection

