<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of comments
     */
    public function index(Request $request)
    {
        $query = Comment::with(['article', 'user', 'parent']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('article', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
        ];

        return view('admin.comments.index', compact('comments', 'stats'));
    }

    /**
     * Approve a comment
     */
    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Comment approved successfully.');
    }

    /**
     * Reject a comment
     */
    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'spam']);
        $comment->delete(); // Soft delete

        return redirect()->back()->with('success', 'Comment rejected and deleted.');
    }

    /**
     * Mark comment as spam
     */
    public function markSpam(Comment $comment)
    {
        $comment->update(['status' => 'spam']);

        return redirect()->back()->with('success', 'Comment marked as spam.');
    }

    /**
     * Edit comment
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'status' => 'required|in:pending,approved,spam',
        ]);

        $comment->update([
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    /**
     * Delete comment
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,spam,delete',
            'comments' => 'required|array',
            'comments.*' => 'exists:comments,id',
        ]);

        $comments = Comment::whereIn('id', $request->comments);

        switch ($request->action) {
            case 'approve':
                $comments->update(['status' => 'approved']);
                $message = 'Selected comments approved.';
                break;
            case 'reject':
                $comments->update(['status' => 'spam']);
                $comments->delete();
                $message = 'Selected comments rejected and deleted.';
                break;
            case 'spam':
                $comments->update(['status' => 'spam']);
                $message = 'Selected comments marked as spam.';
                break;
            case 'delete':
                $comments->delete();
                $message = 'Selected comments deleted.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

