<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\ArticleService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    protected $articleService;
    protected $imageService;

    public function __construct(ArticleService $articleService, ImageService $imageService)
    {
        $this->articleService = $articleService;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of tags
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('articles');

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $tags = $query->orderBy('name', 'asc')->paginate(30);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $this->imageService->convertToWebp($request->file('image'), 'tags');
            if ($path) {
                $validated['image'] = $path;
            }
        }

        Tag::create($validated);

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Show the form for editing the specified tag
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($tag->image && Storage::disk('public')->exists($tag->image)) {
                Storage::disk('public')->delete($tag->image);
            }

            $path = $this->imageService->convertToWebp($request->file('image'), 'tags');
            if ($path) {
                $validated['image'] = $path;
            }
        } else {
            unset($validated['image']);
        }

        $tag->update($validated);

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag
     */
    public function destroy(Tag $tag)
    {
        if ($tag->image && Storage::disk('public')->exists($tag->image)) {
            Storage::disk('public')->delete($tag->image);
        }

        $tag->delete();

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}

