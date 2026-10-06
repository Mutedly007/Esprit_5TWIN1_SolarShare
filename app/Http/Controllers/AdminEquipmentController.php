<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminEquipmentController extends Controller
{
    /**
     * Display a listing of all equipment for the backoffice administrator.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $availability = $request->query('availability');
        $sort = $request->query('sort', 'latest');

        $query = Equipment::with(['category', 'user'])
            ->withCount('rentals');

        // Search by keyword, brand, name or owner info
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if (! empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Status filter (pending, approved, rejected)
        if (! empty($status) && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        // Availability filter
        if ($availability !== null && $availability !== '') {
            if ($availability === '1' || $availability === 'available') {
                $query->where('is_available', true);
            } elseif ($availability === '0' || $availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        // Sorting
        match ($sort) {
            'price_asc' => $query->orderBy('daily_rate', 'asc'),
            'price_desc' => $query->orderBy('daily_rate', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $equipment = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Equipment::count(),
            'pending' => Equipment::where('status', 'pending')->count(),
            'approved' => Equipment::where('status', 'approved')->count(),
            'available' => Equipment::where('is_available', true)->count(),
        ];

        $categories = Category::withCount('equipment')->orderBy('name')->get();

        return view('pages.admin.equipment.index', compact(
            'equipment',
            'stats',
            'categories',
            'search',
            'categoryId',
            'status',
            'availability',
            'sort'
        ));
    }

    /**
     * Display detailed information for a specific equipment in the backoffice.
     */
    public function show(Equipment $equipment): View
    {
        $equipment->load([
            'category',
            'user',
            'rentals' => fn($q) => $q->with('user')->latest(),
            'energyKits',
        ]);

        $rentalStats = [
            'total' => $equipment->rentals->count(),
            'active' => $equipment->rentals->where('status', 'active')->count(),
            'completed' => $equipment->rentals->where('status', 'completed')->count(),
            'revenue' => $equipment->rentals->whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_price'),
        ];

        return view('pages.admin.equipment.show', compact('equipment', 'rentalStats'));
    }

    /**
     * Approve an equipment request (Validé).
     */
    public function approve(Equipment $equipment): RedirectResponse
    {
        $equipment->update([
            'status' => 'approved',
        ]);

        return back()->with('success', "L'équipement « {$equipment->name} » a été validé avec succès.");
    }

    /**
     * Reject an equipment request (Refusé).
     */
    public function reject(Equipment $equipment): RedirectResponse
    {
        $equipment->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', "L'équipement « {$equipment->name} » a été marqué comme refusé.");
    }

    /**
     * Toggle availability status for an equipment.
     */
    public function toggleAvailability(Equipment $equipment): RedirectResponse
    {
        $equipment->update([
            'is_available' => ! $equipment->is_available,
        ]);

        $msg = $equipment->is_available ? 'rendu disponible' : 'marqué comme indisponible';
        return back()->with('success', "L'équipement « {$equipment->name} » est désormais {$msg}.");
    }

    /**
     * Delete an equipment from storage.
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $name = $equipment->name;

        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', "L'équipement « {$name} » a été définitivement supprimé.");
    }
}
