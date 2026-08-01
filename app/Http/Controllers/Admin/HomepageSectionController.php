<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomepageSectionController extends Controller
{
    /**
     * Display a listing of homepage sections.
     */
    public function index()
    {
        $sections = HomepageSection::ordered()->get();
        return view('admin.homepage-sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new homepage section.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
        
        return view('admin.homepage-sections.create', compact('categories'));
    }

    /**
     * Store a newly created homepage section in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:homepage_sections,slug',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'category_ids' => 'nullable|array',
            'articles_per_section' => 'nullable|integer|min:1|max:20',
            'section_title' => 'nullable|string|max:255',
        ]);

        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $validated['is_active'] ?? false;
        $validated['articles_per_section'] = $validated['articles_per_section'] ?? 4;

        HomepageSection::create($validated);

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Homepage section created successfully.');
    }

    /**
     * Display the specified homepage section.
     */
    public function show(HomepageSection $homepageSection)
    {
        return view('admin.homepage-sections.show', compact('homepageSection'));
    }

    /**
     * Show the form for editing the specified homepage section.
     */
    public function edit(HomepageSection $homepageSection)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
        
        return view('admin.homepage-sections.edit', compact('homepageSection', 'categories'));
    }

    /**
     * Update the specified homepage section in storage.
     */
    public function update(Request $request, HomepageSection $homepageSection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:homepage_sections,slug,' . $homepageSection->id,
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'category_ids' => 'nullable|array',
            'articles_per_section' => 'nullable|integer|min:1|max:20',
            'section_title' => 'nullable|string|max:255',
        ]);

        $homepageSection->update($validated);

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Homepage section updated successfully.');
    }

    /**
     * Remove the specified homepage section from storage.
     */
    public function destroy(HomepageSection $homepageSection)
    {
        try {
            $homepageSection->delete();
            return redirect()->route('admin.homepage-sections.index')
                ->with('success', 'Homepage section deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete homepage section.');
        }
    }

    /**
     * Reorder homepage sections.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($validated['order'] as $index => $id) {
            HomepageSection::where('id', $id)->update(['display_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
