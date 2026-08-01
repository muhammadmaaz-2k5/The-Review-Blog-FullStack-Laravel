<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('placement', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('placement')) {
            $query->where('placement', $request->placement);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $ads = $query->orderBy('placement')->orderBy('sort_order')->paginate(20);

        $placementOptions = Ad::$placementOptions;
        $typeOptions = Ad::$typeOptions;

        return view('admin.ads.index', compact('ads', 'placementOptions', 'typeOptions'));
    }

    public function create()
    {
        $placementOptions = Ad::$placementOptions;
        $typeOptions = Ad::$typeOptions;

        return view('admin.ads.create', compact('placementOptions', 'typeOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ads,slug',
            'placement' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'ad_code' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Ad::create($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully.');
    }

    public function edit(Ad $ad)
    {
        $placementOptions = Ad::$placementOptions;
        $typeOptions = Ad::$typeOptions;

        return view('admin.ads.edit', compact('ad', 'placementOptions', 'typeOptions'));
    }

    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ads,slug,' . $ad->id,
            'placement' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'ad_code' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully.');
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted successfully.');
    }

    public function toggle(Ad $ad)
    {
        $ad->update(['is_active' => !$ad->is_active]);

        return redirect()->route('admin.ads.index')->with('success', 'Ad ' . ($ad->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    public function toggleAll(Request $request)
    {
        $enabled = $request->boolean('enabled');

        Ad::query()->update(['is_active' => $enabled]);

        return redirect()->route('admin.ads.index')->with('success', 'All ads ' . ($enabled ? 'activated' : 'deactivated') . ' successfully.');
    }
}
