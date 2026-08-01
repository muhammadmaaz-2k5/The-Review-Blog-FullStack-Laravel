<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ArticleService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $articleService;
    protected $imageService;

    public function __construct(ArticleService $articleService, ImageService $imageService)
    {
        $this->articleService = $articleService;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::withCount('articles')
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $path = $this->imageService->convertToWebp($request->file('image'), 'categories');
            if ($path) {
                $validated['image'] = $path;
            }
        } else {
            unset($validated['image']);
        }

        $category->update($validated);

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        $category->delete();

        // Clear cache
        $this->articleService->clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}

