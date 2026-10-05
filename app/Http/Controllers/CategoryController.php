<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories in the lender space.
     */
    public function index(): View
    {
        $categories = Category::withCount('equipment')
            ->orderBy('name')
            ->get();

        return view('lender.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('lender.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure unique slug fallback
        $baseSlug = $validated['slug'];
        $count = 1;
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$baseSlug}-{$count}";
            $count++;
        }

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified category and its equipment.
     */
    public function show(Category $category): View
    {
        $userId = auth()->id();

        // Load equipment belonging to the lender in this category
        $category->load(['equipment' => function ($query) use ($userId) {
            $query->where('user_id', $userId)->latest();
        }]);

        return view('lender.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        return view('lender.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $categoryName = $category->name;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "La catégorie « {$categoryName} » a été supprimée avec succès.");
    }
}
