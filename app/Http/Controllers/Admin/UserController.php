<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by author status
        if ($request->has('is_author') && $request->is_author !== '') {
            $query->where('is_author', $request->is_author === '1');
        }

        $users = $query->withCount('articles')->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'authors' => User::where('is_author', true)->count(),
            'regular' => User::where('role', 'user')->where('is_author', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->loadCount(['articles', 'bookmarks']);
        $recentArticles = $user->articles()->latest()->limit(5)->get();

        return view('admin.users.show', compact('user', 'recentArticles'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,author,user',
            'is_author' => 'boolean',
            'password' => 'nullable|min:8|confirmed',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'name', 'email', 'username', 'role', 'bio', 'website', 'twitter', 'github', 'linkedin'
        ]);

        $data['is_author'] = $request->has('is_author') ? 1 : 0;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}

