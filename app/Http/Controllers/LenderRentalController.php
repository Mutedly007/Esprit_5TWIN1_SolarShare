<?php

namespace App\Http\Controllers;

use App\Models\EnergyKitReservation;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LenderRentalController extends Controller
{
    /**
     * Display rentals & pack reservations received by the logged-in lender.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');
        $search = $request->query('search');
        $tab = $request->query('tab');

        // 1. Base queries for individual equipment rentals
        $baseRentalQuery = Rental::whereHas('equipment', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // 2. Base queries for Energy Kit (Pack Solaire) reservations
        $baseKitQuery = EnergyKitReservation::whereHas('energyKit', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // Statistics for individual equipment rentals
        $rentalStats = [
            'total' => (clone $baseRentalQuery)->count(),
            'pending' => (clone $baseRentalQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseRentalQuery)->where('status', 'confirmed')->count(),
            'active' => (clone $baseRentalQuery)->where('status', 'active')->count(),
            'completed' => (clone $baseRentalQuery)->where('status', 'completed')->count(),
            'revenue' => (clone $baseRentalQuery)->whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_price'),
        ];

        // Statistics for pack reservations
        $kitStats = [
            'total' => (clone $baseKitQuery)->count(),
            'pending' => (clone $baseKitQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseKitQuery)->where('status', 'confirmed')->count(),
            'active' => (clone $baseKitQuery)->where('status', 'active')->count(),
            'completed' => (clone $baseKitQuery)->where('status', 'completed')->count(),
            'revenue' => (clone $baseKitQuery)->whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_price'),
        ];

        // Combined statistics
        $stats = [
            'total' => $rentalStats['total'] + $kitStats['total'],
            'pending' => $rentalStats['pending'] + $kitStats['pending'],
            'confirmed' => $rentalStats['confirmed'] + $kitStats['confirmed'],
            'active' => $rentalStats['active'] + $kitStats['active'],
            'completed' => $rentalStats['completed'] + $kitStats['completed'],
            'revenue' => (float) $rentalStats['revenue'] + (float) $kitStats['revenue'],
            'equipment_total' => $rentalStats['total'],
            'packs_total' => $kitStats['total'],
            'equipment_pending' => $rentalStats['pending'],
            'packs_pending' => $kitStats['pending'],
        ];

        // Default tab selection: if user has no equipment rentals but has pack reservations, default to 'packs'
        if (! $tab) {
            $tab = ($rentalStats['total'] === 0 && $kitStats['total'] > 0) ? 'packs' : 'equipment';
        }

        // Fetch equipment rentals
        $rentalQuery = (clone $baseRentalQuery)
            ->with(['equipment.category', 'user', 'inspectionReports'])
            ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled'])) {
            $rentalQuery->where('status', $status);
        }

        if ($request->filled('search')) {
            $term = trim($search);
            $rentalQuery->where(function ($q) use ($term) {
                $q->where('id', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('name', 'like', "%{$term}%")
                         ->orWhere('email', 'like', "%{$term}%");
                  })
                  ->orWhereHas('equipment', function ($eq) use ($term) {
                      $eq->where('name', 'like', "%{$term}%")
                         ->orWhere('brand', 'like', "%{$term}%");
                  });
            });
        }

        $rentals = $rentalQuery->paginate(10, ['*'], 'rentals_page')->withQueryString();

        // Fetch pack reservations
        $kitQuery = (clone $baseKitQuery)
            ->with(['energyKit.equipment', 'user'])
            ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled'])) {
            $kitQuery->where('status', $status);
        }

        if ($request->filled('search')) {
            $term = trim($search);
            $kitQuery->where(function ($q) use ($term) {
                $q->where('id', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('name', 'like', "%{$term}%")
                         ->orWhere('email', 'like', "%{$term}%");
                  })
                  ->orWhereHas('energyKit', function ($kq) use ($term) {
                      $kq->where('title', 'like', "%{$term}%")
                         ->orWhere('description', 'like', "%{$term}%");
                  });
            });
        }

        $kitReservations = $kitQuery->paginate(10, ['*'], 'packs_page')->withQueryString();

        return view('lender.rentals.index', compact(
            'rentals',
            'kitReservations',
            'stats',
            'rentalStats',
            'kitStats',
            'status',
            'search',
            'tab'
        ));
    }

    /**
     * Update status of an Energy Kit reservation.
     */
    public function updateKitReservationStatus(Request $request, EnergyKitReservation $reservation)
    {
        $user = Auth::user();

        // Security check: Must be owner of the kit or admin
        if (! $user->isAdmin() && $reservation->energyKit->user_id !== $user->id) {
            abort(403, 'Action non autorisée. Vous ne pouvez modifier que les réservations de vos propres packs.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,active,completed,cancelled'],
        ]);

        $reservation->update(['status' => $validated['status']]);

        $statusLabels = [
            'confirmed' => 'confirmée avec succès',
            'active' => 'marquée en cours d\'utilisation',
            'completed' => 'marquée comme restituée et terminée',
            'cancelled' => 'annulée',
            'pending' => 'remise en attente',
        ];

        $label = $statusLabels[$validated['status']] ?? 'mise à jour';

        return back()->with('success', "La réservation #PACK-" . str_pad($reservation->id, 5, '0', STR_PAD_LEFT) . " a été {$label}.");
    }
}
