<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of careers
     */
    public function index(Request $request)
    {
        $query = Career::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status == 'active');
        }

        // Type filter
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $careers = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.careers.index', compact('careers'));
    }

    /**
     * Show the form for creating a new career
     */
    public function create()
    {
        return view('admin.careers.create');
    }

    /**
     * Store a newly created career
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:careers,slug',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:full-time,part-time,contract,remote,internship',
            'department' => 'nullable|string|max:255',
            'experience_level' => 'nullable|in:entry,mid,senior,executive',
            'salary_range' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Career::create($validated);

        return redirect()->route('admin.careers.index')
            ->with('success', 'Career posted successfully.');
    }

    /**
     * Show the form for editing the specified career
     */
    public function edit(Career $career)
    {
        return view('admin.careers.edit', compact('career'));
    }

    /**
     * Update the specified career
     */
    public function update(Request $request, Career $career)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:careers,slug,' . $career->id,
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:full-time,part-time,contract,remote,internship',
            'department' => 'nullable|string|max:255',
            'experience_level' => 'nullable|in:entry,mid,senior,executive',
            'salary_range' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $career->update($validated);

        return redirect()->route('admin.careers.index')
            ->with('success', 'Career updated successfully.');
    }

    /**
     * Remove the specified career
     */
    public function destroy(Career $career)
    {
        $career->delete();

        return redirect()->route('admin.careers.index')
            ->with('success', 'Career deleted successfully.');
    }
}

