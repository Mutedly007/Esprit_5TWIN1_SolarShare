<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    /**
     * Display a listing of the user's rentals (Renter Space / Front).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');

        $query = Rental::with(['equipment.category', 'equipment.user', 'inspectionReports'])
            ->where('user_id', $user->id)
            ->latest();

        if ($status && in_array($status, ['pending', 'confirmed', 'active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $rentals = $query->paginate(9)->withQueryString();

        // Count per status for quick filter badges
        $counts = [
            'all' => Rental::where('user_id', $user->id)->count(),
            'pending' => Rental::where('user_id', $user->id)->where('status', 'pending')->count(),
            'confirmed' => Rental::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'active' => Rental::where('user_id', $user->id)->where('status', 'active')->count(),
            'completed' => Rental::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Rental::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        return view('pages.front.rentals.index', compact('rentals', 'counts', 'status'));
    }

    /**
     * Show the form for creating a new rental.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->isRenter()) {
            abort(403, 'Accès réservé aux emprunteurs (rôle Rent ou Both). Votre rôle actuel de prêteur ne vous permet pas de réserver des équipements.');
        }

        $equipmentId = $request->query('equipment_id');
        $selectedEquipment = null;

        if ($equipmentId) {
            $selectedEquipment = Equipment::with(['category', 'user'])->findOrFail($equipmentId);
        }

        $availableEquipment = Equipment::where('is_available', true)
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('pages.front.rentals.create', compact('selectedEquipment', 'availableEquipment'));
    }

    /**
     * Store a newly created rental in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->isRenter()) {
            abort(403, 'Accès réservé aux emprunteurs (rôle Rent ou Both). Votre rôle actuel de prêteur ne vous permet pas de réserver des équipements.');
        }

        $validated = $request->validate([
            'equipment_id' => ['required', 'exists:equipment,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'renter_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'start_date.after_or_equal' => 'La date de début doit être aujourd\'hui ou une date ultérieure.',
            'end_date.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        // Check if user is trying to rent their own equipment (bypassed in local dev to ease testing)
        if ($equipment->user_id === Auth::id() && ! Auth::user()->isAdmin() && ! app()->environment('local')) {
            return back()->withInput()->with('error', 'Vous ne pouvez pas louer votre propre équipement.');
        }

        // Check for conflicting active / confirmed rentals
        $conflict = Rental::where('equipment_id', $equipment->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Cet équipement est déjà réservé sur cette période. Veuillez choisir d\'autres dates.');
        }

        // Calculate total price
        $totalPrice = Rental::calculatePrice(
            (float) $equipment->daily_rate,
            $validated['start_date'],
            $validated['end_date']
        );

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'renter_notes' => $validated['renter_notes'] ?? null,
        ]);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Votre demande de réservation a été enregistrée avec succès ! Le propriétaire a été notifié.');
    }

    /**
     * Display the specified rental details (Contract & inspections).
     */
    public function show(Rental $rental)
    {
        $user = Auth::user();

        // Check permissions: renter, lender or admin
        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé à cette réservation.');
        }

        $rental->load(['equipment.category', 'equipment.user', 'user', 'inspectionReports.inspector']);

        return view('pages.front.rentals.show', compact('rental'));
    }

    /**
     * Show the form for editing dates of a rental.
     */
    public function edit(Rental $rental)
    {
        $user = Auth::user();

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        if (! $rental->canBeEdited()) {
            return redirect()->route('rentals.show', $rental)
                ->with('error', 'Cette réservation ne peut plus être modifiée car elle est déjà ' . strtolower($rental->status_label) . '.');
        }

        $rental->load(['equipment.category', 'equipment.user']);

        return view('pages.front.rentals.edit', compact('rental'));
    }

    /**
     * Update the dates and recalculate price.
     */
    public function update(Request $request, Rental $rental)
    {
        $user = Auth::user();

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        if (! $rental->canBeEdited()) {
            return redirect()->route('rentals.show', $rental)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'renter_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $equipment = $rental->equipment;

        // Check for conflicts excluding current rental
        $conflict = Rental::where('equipment_id', $equipment->id)
            ->where('id', '!=', $rental->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Ces dates se chevauchent avec une autre réservation confirmée pour ce matériel.');
        }

        $totalPrice = Rental::calculatePrice(
            (float) $equipment->daily_rate,
            $validated['start_date'],
            $validated['end_date']
        );

        $rental->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
            'renter_notes' => $validated['renter_notes'] ?? $rental->renter_notes,
        ]);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Les dates de réservation et le prix total ont été mis à jour avec succès.');
    }

    /**
     * Validate / Confirm a rental request (by lender or admin).
     */
    public function confirm(Request $request, Rental $rental)
    {
        $user = Auth::user();

        if (! $rental->isLender($user) && ! $user->isAdmin()) {
            abort(403, 'Seul le propriétaire de l\'équipement ou un administrateur peut valider cette réservation.');
        }

        if ($rental->status !== 'pending') {
            return back()->with('error', 'Cette réservation n\'est plus en attente.');
        }

        $rental->update(['status' => 'confirmed']);

        return back()->with('success', 'La réservation a été confirmée avec succès ! Le locataire peut désormais procéder au retrait avec l\'état des lieux (check-in).');
    }

    /**
     * Cancel a rental.
     */
    public function cancel(Request $request, Rental $rental)
    {
        $user = Auth::user();

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        if (! $rental->canBeCancelled()) {
            return back()->with('error', 'Cette réservation ne peut plus être annulée dans son état actuel.');
        }

        $reason = $request->input('cancellation_reason', 'Annulée par l\'utilisateur.');

        $rental->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        // If the equipment was marked unavailable, restore it if no other active rental exists
        $hasOtherActive = Rental::where('equipment_id', $rental->equipment_id)
            ->whereIn('status', ['confirmed', 'active'])
            ->exists();

        if (! $hasOtherActive) {
            $rental->equipment->update(['is_available' => true]);
        }

        return back()->with('success', 'La réservation a été annulée.');
    }

    /**
     * Remove the specified rental from storage (Admin or Draft).
     */
    public function destroy(Rental $rental)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && ! ($rental->isRenter($user) && $rental->status === 'cancelled')) {
            abort(403, 'Action non autorisée.');
        }

        $rental->delete();

        return redirect()->route('rentals.index')->with('success', 'La réservation a été supprimée.');
    }
}
