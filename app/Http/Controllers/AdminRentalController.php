<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class AdminRentalController extends Controller
{
    /**
     * Display a listing of all rentals for the administrator.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Rental::with(['equipment.category', 'equipment.user', 'user', 'inspectionReports'])
            ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('equipment', function ($eq) use ($search) {
                    $eq->where('name', 'like', "%{$search}%")
                       ->orWhere('brand', 'like', "%{$search}%");
                })->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $rentals = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Rental::count(),
            'pending' => Rental::where('status', 'pending')->count(),
            'active' => Rental::where('status', 'active')->count(),
            'completed' => Rental::where('status', 'completed')->count(),
            'total_volume' => Rental::whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_price'),
        ];

        return view('pages.admin.rentals.index', compact('rentals', 'stats', 'status', 'search'));
    }
}
