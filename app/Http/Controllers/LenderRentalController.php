<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LenderRentalController extends Controller
{
    /**
     * Display rentals received by the logged-in lender.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');

        $query = Rental::whereHas('equipment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['equipment.category', 'user', 'inspectionReports'])
        ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $rentals = $query->paginate(10)->withQueryString();

        // Lender statistics
        $baseQuery = Rental::whereHas('equipment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'revenue' => (clone $baseQuery)->whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_price'),
        ];

        return view('lender.rentals.index', compact('rentals', 'stats', 'status'));
    }
}
