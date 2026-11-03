<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Display the public marketplace with all equipment and categories with advanced filtering.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount(['equipment' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        $brands = Equipment::where('status', 'approved')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $query = Equipment::with(['category', 'user'])
            ->where('status', 'approved');

        // 1. Search keyword (name, brand, or description)
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('brand', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // 2. Filter by category slug or id
        $selectedCategory = null;
        if ($request->filled('category')) {
            $catParam = $request->category;
            $selectedCategory = Category::where('slug', $catParam)->orWhere('id', $catParam)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // 3. Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // 4. Price range (daily_rate in TND/day)
        if ($request->filled('min_price')) {
            $query->where('daily_rate', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('daily_rate', '<=', (float) $request->max_price);
        }

        // 5. Minimum Power (Watts)
        if ($request->filled('min_power')) {
            $query->where('power_watts', '>=', (int) $request->min_power);
        }

        // 6. Minimum Battery Capacity (Wh)
        if ($request->filled('min_capacity')) {
            $query->where('capacity_wh', '>=', (int) $request->min_capacity);
        }

        // 7. Maximum Deposit (Caution max)
        if ($request->filled('max_deposit')) {
            $query->where('deposit_amount', '<=', (float) $request->max_deposit);
        }

        // 8. Availability filter
        if ($request->boolean('available') || $request->query('available') === '1') {
            $query->where('is_available', true);
        }

        // 9. Sorting
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'     => $query->orderBy('daily_rate', 'asc'),
            'price_desc'    => $query->orderBy('daily_rate', 'desc'),
            'power_desc'    => $query->orderBy('power_watts', 'desc'),
            'capacity_desc' => $query->orderBy('capacity_wh', 'desc'),
            default         => $query->latest(),
        };

        // Active filters count (excluding category which has its own main tabs)
        $activeFiltersCount = 0;
        if ($request->filled('search')) $activeFiltersCount++;
        if ($request->filled('brand')) $activeFiltersCount++;
        if ($request->filled('min_price')) $activeFiltersCount++;
        if ($request->filled('max_price')) $activeFiltersCount++;
        if ($request->filled('min_power')) $activeFiltersCount++;
        if ($request->filled('min_capacity')) $activeFiltersCount++;
        if ($request->filled('max_deposit')) $activeFiltersCount++;
        if ($request->boolean('available') || $request->query('available') === '1') $activeFiltersCount++;
        if ($sort && $sort !== 'latest') $activeFiltersCount++;

        $equipment = $query->paginate(9)->withQueryString();
        $totalCount = Equipment::where('status', 'approved')->count();

        return view('pages.front.marketplace', compact(
            'categories',
            'brands',
            'equipment',
            'selectedCategory',
            'totalCount',
            'activeFiltersCount',
            'sort'
        ));
    }

    /**
     * Display details for a specific equipment in the marketplace.
     */
    public function show(Equipment $equipment)
    {
        // Non-approved equipment can only be previewed by its owner or an admin
        if ($equipment->status !== 'approved') {
            $canPreview = auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $equipment->user_id);
            if (! $canPreview) {
                abort(404, 'Cet équipement est en cours de validation par un administrateur.');
            }
        }

        $equipment->load(['category', 'user']);

        $related = Equipment::with(['category', 'user'])
            ->where('status', 'approved')
            ->where('category_id', $equipment->category_id)
            ->where('id', '!=', $equipment->id)
            ->take(3)
            ->get();

        return view('pages.front.marketplace_show', compact('equipment', 'related'));
    }
}
