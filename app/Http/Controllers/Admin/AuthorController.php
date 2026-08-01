<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuthorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors
     */
    public function index(Request $request)
    {
        $query = User::where('is_author', true)
            ->orWhere('role', 'author')
            ->orWhere('role', 'admin');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $authors = $query->withCount('articles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.authors.index', compact('authors'));
    }

    /**
     * Display author requests
     */
    public function requests(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $requests = AuthorRequest::with(['user', 'reviewer'])
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.authors.requests', compact('requests', 'status'));
    }

    /**
     * Approve author request
     */
    public function approveRequest(AuthorRequest $request)
    {
        $user = $request->user;
        
        DB::transaction(function() use ($request, $user) {
            $user->update([
                'is_author' => true,
                'role' => $user->role === 'admin' ? 'admin' : 'author',
            ]);

            $request->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        return redirect()->route('admin.authors.requests')
            ->with('success', 'Author request approved successfully.');
    }

    /**
     * Reject author request
     */
    public function rejectRequest(Request $httpRequest, AuthorRequest $request)
    {
        $validated = $httpRequest->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $request->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.authors.requests')
            ->with('success', 'Author request rejected.');
    }

    /**
     * Show author details and statistics
     */
    public function show(User $author)
    {
        $articles = $author->articles()
            ->with(['category', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_articles' => $author->articles()->count(),
            'published_articles' => $author->articles()->where('status', 'published')->count(),
            'draft_articles' => $author->articles()->where('status', 'draft')->count(),
            'total_views' => $author->articles()->sum('views'),
            'total_likes' => $author->articles()->withCount('likes')->get()->sum('likes_count'),
        ];

        return view('admin.authors.show', compact('author', 'articles', 'stats'));
    }

    /**
     * Update author permissions
     */
    public function updatePermissions(Request $request, User $author)
    {
        $validated = $request->validate([
            'is_author' => 'required|boolean',
            'role' => 'required|string|in:user,author,admin',
        ]);

        $author->update($validated);

        return redirect()->route('admin.authors.show', $author)
            ->with('success', 'Author permissions updated successfully.');
    }

    /**
     * Remove author status
     */
    public function removeAuthorStatus(User $author)
    {
        $author->update([
            'is_author' => false,
            'role' => 'user',
        ]);

        return redirect()->route('admin.authors.index')
            ->with('success', 'Author status removed successfully.');
    }
}
