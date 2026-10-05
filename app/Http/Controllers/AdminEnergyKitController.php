<?php

namespace App\Http\Controllers;

use App\Models\EnergyKit;
use Illuminate\Http\Request;

class AdminEnergyKitController extends Controller
{
    /**
     * Display a listing of Energy Kits for the administrator.
     */
    public function index(Request $request)
    {
        $targetUse = $request->query('target_use');
        $search = $request->query('q');

        $query = EnergyKit::with(['equipment.category', 'user'])
            ->withCount(['equipment', 'reservations'])
            ->latest();

        if ($targetUse && in_array($targetUse, ['camping', 'chantier', 'urgence', 'vanlife', 'evenement'])) {
            $query->where('target_use', $targetUse);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $kits = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => EnergyKit::count(),
            'active' => EnergyKit::where('is_active', true)->count(),
            'avg_discount' => round(EnergyKit::avg('discount_percentage') ?? 0, 1),
            'total_bundled_items' => \Illuminate\Support\Facades\DB::table('energy_kit_equipment')->sum('quantity'),
            'total_reservations' => \App\Models\EnergyKitReservation::count(),
        ];

        return view('pages.admin.kits.index', compact('kits', 'stats', 'targetUse', 'search'));
    }

    /**
     * Quick toggle activation status.
     */
    public function toggle(EnergyKit $energyKit)
    {
        $energyKit->update(['is_active' => ! $energyKit->is_active]);

        return back()->with('success', 'Statut du pack mis à jour avec succès.');
    }
}
