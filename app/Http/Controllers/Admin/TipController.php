<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipController extends Controller
{
    /**
     * Display a listing of tips
     */
    public function index(Request $request)
    {
        $query = Tip::with(['user', 'reviewer']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $tips = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Tip::count(),
            'pending' => Tip::where('status', 'pending')->count(),
            'reviewed' => Tip::where('status', 'reviewed')->count(),
            'approved' => Tip::where('status', 'approved')->count(),
            'rejected' => Tip::where('status', 'rejected')->count(),
        ];

        return view('admin.tips.index', compact('tips', 'stats'));
    }

    /**
     * Display the specified tip
     */
    public function show(Tip $tip)
    {
        $tip->load(['user', 'reviewer']);

        return view('admin.tips.show', compact('tip'));
    }

    /**
     * Update tip status
     */
    public function updateStatus(Request $request, Tip $tip)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,approved,rejected',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $tip->markAsReviewed(
            Auth::id(),
            $validated['status'],
            $validated['admin_notes'] ?? null
        );

        return redirect()->back()->with('success', 'Tip status updated successfully.');
    }

    /**
     * Delete a tip
     */
    public function destroy(Tip $tip)
    {
        $tip->delete();

        return redirect()->route('admin.tips.index')->with('success', 'Tip deleted successfully.');
    }
}

