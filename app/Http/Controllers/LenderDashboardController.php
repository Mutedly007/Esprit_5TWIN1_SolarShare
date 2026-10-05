<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LenderDashboardController extends Controller
{
    /**
     * Display the lender personal dashboard.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $totalCount = Equipment::where('user_id', $userId)->count();
        $pendingCount = Equipment::where('user_id', $userId)->where('status', 'pending')->count();
        $availableCount = Equipment::where('user_id', $userId)->where('is_available', true)->count();
        $categoryCount = Category::count();

        $equipment = Equipment::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount(['equipment' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();

        return view('lender.dashboard', compact(
            'equipment',
            'totalCount',
            'pendingCount',
            'availableCount',
            'categoryCount',
            'categories'
        ));
    }
}
