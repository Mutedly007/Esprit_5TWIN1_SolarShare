<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Display the public marketplace with all equipment and categories.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('equipment')->get();

        $query = Equipment::with(['category', 'user']);

        // Search by keyword, title, brand or description
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('brand', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // Filter by category slug or id
        $selectedCategory = null;
        if ($request->filled('category')) {
            $catParam = $request->category;
            $selectedCategory = Category::where('slug', $catParam)->orWhere('id', $catParam)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // Availability filter
        if ($request->filled('available') && $request->available == '1') {
            $query->where('is_available', true);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('daily_rate', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('daily_rate', 'desc');
        } else {
            $query->latest();
        }

        $equipment = $query->paginate(9)->withQueryString();
        $totalCount = Equipment::count();

        return view('pages.front.marketplace', compact(
            'categories',
            'equipment',
            'selectedCategory',
            'totalCount'
        ));
    }

    /**
     * Display details for a specific equipment in the marketplace.
     */
    public function show(Equipment $equipment)
    {
        $equipment->load(['category', 'user']);

        $related = Equipment::with(['category', 'user'])
            ->where('category_id', $equipment->category_id)
            ->where('id', '!=', $equipment->id)
            ->take(3)
            ->get();

        return view('pages.front.marketplace_show', compact('equipment', 'related'));
    }
}
