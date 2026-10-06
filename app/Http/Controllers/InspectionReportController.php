<?php

namespace App\Http\Controllers;

use App\Models\InspectionReport;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InspectionReportController extends Controller
{
    /**
     * Show form to fill an inspection report (check-in or check-out).
     */
    public function create(Request $request, Rental $rental)
    {
        $user = Auth::user();

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        // Determine default type
        $type = $request->query('type');
        if (! $type) {
            if ($rental->status === 'confirmed') {
                $type = 'check_in';
            } elseif ($rental->status === 'active') {
                $type = 'check_out';
            } else {
                $type = 'check_in';
            }
        }

        // Check if report already exists for this type
        $existing = $rental->inspectionReports()->where('type', $type)->first();
        if ($existing) {
            return redirect()->route('inspections.show', $existing)
                ->with('info', 'L\'état des lieux ' . ($type === 'check_in' ? 'de départ' : 'de retour') . ' a déjà été créé. Vous pouvez le consulter ou le modifier.');
        }

        $rental->load(['equipment', 'user']);

        return view('pages.front.inspections.create', compact('rental', 'type'));
    }

    /**
     * Store newly created inspection report.
     */
    public function store(Request $request, Rental $rental)
    {
        $user = Auth::user();

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:check_in,check_out'],
            'battery_level_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'physical_condition' => ['required', 'in:excellent,good,fair,damaged'],
            'has_all_cables' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'validate_now' => ['nullable', 'boolean'],
        ]);

        // Upload photos
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('inspections', 'public');
                $photoPaths[] = $path;
            }
        }

        $isValidated = $request->boolean('validate_now');

        $report = InspectionReport::create([
            'rental_id' => $rental->id,
            'type' => $validated['type'],
            'battery_level_percent' => $validated['battery_level_percent'] ?? null,
            'physical_condition' => $validated['physical_condition'],
            'has_all_cables' => (bool) $validated['has_all_cables'],
            'notes' => $validated['notes'] ?? null,
            'photos' => $photoPaths,
            'inspector_id' => $isValidated ? $user->id : null,
            'is_validated' => $isValidated,
            'validated_at' => $isValidated ? now() : null,
        ]);

        // Progress rental lifecycle if validated
        if ($isValidated) {
            $this->applyLifecycleTransition($rental, $validated['type']);
        }

        return redirect()->route('inspections.show', $report)
            ->with('success', 'L\'état des lieux ' . ($report->type === 'check_in' ? 'de départ' : 'de retour') . ' a été enregistré avec succès !');
    }

    /**
     * Display the inspection report details.
     */
    public function show(InspectionReport $inspection)
    {
        $user = Auth::user();
        $rental = $inspection->rental;

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        $inspection->load(['rental.equipment', 'rental.user', 'inspector']);

        // If this is checkout, load check-in for side-by-side comparison
        $checkInReport = null;
        if ($inspection->type === 'check_out') {
            $checkInReport = $rental->checkInReport;
        }

        return view('pages.front.inspections.show', compact('inspection', 'rental', 'checkInReport'));
    }

    /**
     * Show form to edit an inspection report.
     */
    public function edit(InspectionReport $inspection)
    {
        $user = Auth::user();
        $rental = $inspection->rental;

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        return view('pages.front.inspections.edit', compact('inspection', 'rental'));
    }

    /**
     * Update the inspection report.
     */
    public function update(Request $request, InspectionReport $inspection)
    {
        $user = Auth::user();
        $rental = $inspection->rental;

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'battery_level_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'physical_condition' => ['required', 'in:excellent,good,fair,damaged'],
            'has_all_cables' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $photoPaths = $inspection->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('inspections', 'public');
                $photoPaths[] = $path;
            }
        }

        $inspection->update([
            'battery_level_percent' => $validated['battery_level_percent'] ?? null,
            'physical_condition' => $validated['physical_condition'],
            'has_all_cables' => (bool) $validated['has_all_cables'],
            'notes' => $validated['notes'] ?? null,
            'photos' => $photoPaths,
        ]);

        return redirect()->route('inspections.show', $inspection)
            ->with('success', 'L\'état des lieux a été mis à jour.');
    }

    /**
     * Validate and digitally seal the inspection report.
     */
    public function validateReport(Request $request, InspectionReport $inspection)
    {
        $user = Auth::user();
        $rental = $inspection->rental;

        if (! $rental->canBeManagedBy($user)) {
            abort(403, 'Accès non autorisé.');
        }

        $inspection->update([
            'is_validated' => true,
            'validated_at' => now(),
            'inspector_id' => $user->id,
        ]);

        $this->applyLifecycleTransition($rental, $inspection->type);

        return back()->with('success', 'L\'état des lieux a été validé et signé avec succès. Le statut de la location a été mis à jour !');
    }

    /**
     * Helper to advance rental lifecycle based on validated inspection.
     */
    protected function applyLifecycleTransition(Rental $rental, string $type): void
    {
        if ($type === 'check_in' && $rental->status === 'confirmed') {
            $rental->update(['status' => 'active']);
            // Mark equipment unavailable during active rental
            $rental->equipment->update(['is_available' => false]);
        } elseif ($type === 'check_out' && $rental->status === 'active') {
            $rental->update(['status' => 'completed']);
            // Restore equipment availability
            $rental->equipment->update(['is_available' => true]);
        }
    }

    /**
     * Remove the inspection report.
     */
    public function destroy(InspectionReport $inspection)
    {
        $user = Auth::user();
        $rental = $inspection->rental;

        if (! $user->isAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        // Delete photo files
        if ($inspection->photos && is_array($inspection->photos)) {
            foreach ($inspection->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $inspection->delete();

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Le rapport d\'inspection a été supprimé.');
    }
}
