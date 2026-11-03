<?php

namespace App\Http\Controllers;

use App\Models\EnergyKit;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnergyKitController extends Controller
{
    /**
     * Display a public listing of Energy Kits.
     */
    public function index(Request $request)
    {
        $targetUse = $request->query('target_use');
        $search = $request->query('q');

        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        $query = EnergyKit::with(['equipment.category', 'user'])
            ->withCount('equipment');

        // Non-admin visitors only see active kits; Admins see all kits (with inactive badge)
        if (! $isAdmin) {
            $query->where('is_active', true);
        }

        if ($targetUse && in_array($targetUse, ['camping', 'chantier', 'urgence', 'vanlife', 'evenement'])) {
            $query->where('target_use', $targetUse);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $kits = $query->latest()->paginate(9)->withQueryString();

        // Count per target_use for quick filter tabs
        $counts = [
            'all' => $isAdmin ? EnergyKit::count() : EnergyKit::where('is_active', true)->count(),
            'camping' => ($isAdmin ? EnergyKit::where('target_use', 'camping') : EnergyKit::where('is_active', true)->where('target_use', 'camping'))->count(),
            'vanlife' => ($isAdmin ? EnergyKit::where('target_use', 'vanlife') : EnergyKit::where('is_active', true)->where('target_use', 'vanlife'))->count(),
            'chantier' => ($isAdmin ? EnergyKit::where('target_use', 'chantier') : EnergyKit::where('is_active', true)->where('target_use', 'chantier'))->count(),
            'urgence' => ($isAdmin ? EnergyKit::where('target_use', 'urgence') : EnergyKit::where('is_active', true)->where('target_use', 'urgence'))->count(),
            'evenement' => ($isAdmin ? EnergyKit::where('target_use', 'evenement') : EnergyKit::where('is_active', true)->where('target_use', 'evenement'))->count(),
        ];

        return view('pages.front.kits.index', compact('kits', 'counts', 'targetUse', 'search'));
    }

    /**
     * Show the detailed page of an Energy Kit.
     */
    public function show(EnergyKit $energyKit)
    {
        $energyKit->load(['equipment.category', 'equipment.user', 'user']);

        return view('pages.front.kits.show', compact('energyKit'));
    }

    /**
     * Show the form for creating a new Energy Kit.
     */
    public function create()
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs (rôle Lend ou Both) et aux administrateurs.');
        }

        $query = Equipment::where('status', 'approved')->orderBy('name');
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        $equipments = $query->get();

        return view('pages.front.kits.create', compact('equipments'));
    }

    /**
     * Store a newly created Energy Kit.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs (rôle Lend ou Both) et aux administrateurs.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'target_use' => ['required', 'in:camping,chantier,urgence,vanlife,evenement'],
            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:90'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.equipment_id' => ['required', 'exists:equipment,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'items.*.options' => ['nullable', 'string', 'max:255'],
        ], [
            'items.required' => __('messages.kits.err_min_one_eq'),
            'items.min' => __('messages.kits.err_min_one_eq'),
        ]);

        // Restriction : un prêteur ne peut composer un pack qu'avec ses propres équipements approuvés
        if (!auth()->user()->isAdmin()) {
            $ownedEquipmentIds = Equipment::where('user_id', auth()->id())
                ->where('status', 'approved')
                ->pluck('id')
                ->toArray();

            foreach ($validated['items'] as $item) {
                if (!in_array($item['equipment_id'], $ownedEquipmentIds)) {
                    return back()->withInput()->withErrors([
                        'items' => __('messages.kits.err_own_approved_only')
                    ]);
                }
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('kits', 'public');
        }

        $kit = EnergyKit::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_use' => $validated['target_use'],
            'discount_percentage' => $validated['discount_percentage'],
            'is_active' => $request->boolean('is_active', true),
            'image' => $imagePath,
        ]);

        // Attach equipment via pivot with quantities and options
        $syncData = [];
        foreach ($validated['items'] as $item) {
            $syncData[$item['equipment_id']] = [
                'quantity' => $item['quantity'],
                'options' => $item['options'] ?? null,
            ];
        }
        $kit->equipment()->sync($syncData);

        return redirect()->route('energy-kits.show', $kit)
            ->with('success', __('messages.kits.flash_created', ['title' => $kit->title]));
    }

    /**
     * Show form for editing an Energy Kit.
     */
    public function edit(EnergyKit $energyKit)
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs et aux administrateurs.');
        }

        if (!auth()->user()->isAdmin() && auth()->id() !== $energyKit->user_id) {
            abort(403, 'Vous ne pouvez modifier que vos propres packs solaires.');
        }

        $energyKit->load(['equipment']);
        $query = Equipment::where('status', 'approved')->orderBy('name');
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        $equipments = $query->get();

        return view('pages.front.kits.edit', compact('energyKit', 'equipments'));
    }

    /**
     * Update the Energy Kit and its equipment associations.
     */
    public function update(Request $request, EnergyKit $energyKit)
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs et aux administrateurs.');
        }

        if (!auth()->user()->isAdmin() && auth()->id() !== $energyKit->user_id) {
            abort(403, 'Vous ne pouvez modifier que vos propres packs solaires.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'target_use' => ['required', 'in:camping,chantier,urgence,vanlife,evenement'],
            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:90'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.equipment_id' => ['required', 'exists:equipment,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'items.*.options' => ['nullable', 'string', 'max:255'],
        ]);

        if (!auth()->user()->isAdmin()) {
            $ownedEquipmentIds = Equipment::where('user_id', auth()->id())
                ->where('status', 'approved')
                ->pluck('id')
                ->toArray();

            foreach ($validated['items'] as $item) {
                if (!in_array($item['equipment_id'], $ownedEquipmentIds)) {
                    return back()->withInput()->withErrors([
                        'items' => __('messages.kits.err_own_approved_only')
                    ]);
                }
            }
        }

        $imagePath = $energyKit->image;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('kits', 'public');
        }

        $energyKit->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'target_use' => $validated['target_use'],
            'discount_percentage' => $validated['discount_percentage'],
            'is_active' => $request->boolean('is_active', true),
            'image' => $imagePath,
        ]);

        // Sync equipment
        $syncData = [];
        foreach ($validated['items'] as $item) {
            $syncData[$item['equipment_id']] = [
                'quantity' => $item['quantity'],
                'options' => $item['options'] ?? null,
            ];
        }
        $energyKit->equipment()->sync($syncData);

        return redirect()->route('energy-kits.show', $energyKit)
            ->with('success', __('messages.kits.flash_updated'));
    }

    /**
     * Attach an equipment to an existing Energy Kit.
     */
    public function attachEquipment(Request $request, EnergyKit $energyKit)
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs et aux administrateurs.');
        }

        if (!auth()->user()->isAdmin() && auth()->id() !== $energyKit->user_id) {
            abort(403, 'Vous ne pouvez modifier que vos propres packs solaires.');
        }

        $validated = $request->validate([
            'equipment_id' => ['required', 'exists:equipment,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'options' => ['nullable', 'string', 'max:255'],
        ]);

        if (!auth()->user()->isAdmin()) {
            $isOwned = Equipment::where('id', $validated['equipment_id'])
                ->where('user_id', auth()->id())
                ->where('status', 'approved')
                ->exists();

            if (!$isOwned) {
                return back()->withErrors(['items' => __('messages.kits.err_own_approved_only')]);
            }
        }

        $energyKit->equipment()->syncWithoutDetaching([
            $validated['equipment_id'] => [
                'quantity' => $validated['quantity'],
                'options' => $validated['options'] ?? null,
            ]
        ]);

        return back()->with('success', __('messages.kits.flash_attached'));
    }

    /**
     * Detach an equipment from an Energy Kit.
     */
    public function detachEquipment(EnergyKit $energyKit, Equipment $equipment)
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs et aux administrateurs.');
        }

        if (!auth()->user()->isAdmin() && auth()->id() !== $energyKit->user_id) {
            abort(403, 'Action non autorisée.');
        }

        $energyKit->equipment()->detach($equipment->id);

        return back()->with('success', __('messages.kits.flash_detached'));
    }

    /**
     * Remove the Energy Kit.
     */
    public function destroy(EnergyKit $energyKit)
    {
        if (!auth()->user()->isLender()) {
            abort(403, 'Accès réservé aux prêteurs et aux administrateurs.');
        }

        if (!auth()->user()->isAdmin() && auth()->id() !== $energyKit->user_id) {
            abort(403, 'Vous ne pouvez supprimer que vos propres packs solaires.');
        }

        if ($energyKit->image && Storage::disk('public')->exists($energyKit->image)) {
            Storage::disk('public')->delete($energyKit->image);
        }

        $energyKit->delete();

        return redirect()->route('energy-kits.index')
            ->with('success', __('messages.kits.flash_deleted'));
    }

    /**
     * Reserve an Energy Kit (Booking).
     */
    public function reserve(Request $request, EnergyKit $energyKit)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', __('messages.kits.err_login_to_reserve'));
        }

        if (!auth()->user()->isRenter()) {
            abort(403, 'Accès réservé aux emprunteurs (rôle Rent ou Both). Votre rôle actuel de prêteur ne vous permet pas de réserver des packs solaires.');
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'notes'      => ['nullable', 'string', 'max:1000'],
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $daysCount = max(1, $startDate->diffInDays($endDate) + 1);

        $dailyRate = (float) $energyKit->discounted_daily_rate;
        $totalPrice = round($daysCount * $dailyRate, 2);
        $depositAmount = (float) $energyKit->total_deposit;

        \App\Models\EnergyKitReservation::create([
            'energy_kit_id'  => $energyKit->id,
            'user_id'        => auth()->id(),
            'start_date'     => $validated['start_date'],
            'end_date'       => $validated['end_date'],
            'days_count'     => $daysCount,
            'daily_rate'     => $dailyRate,
            'total_price'    => $totalPrice,
            'deposit_amount' => $depositAmount,
            'status'         => 'pending',
            'notes'          => $validated['notes'] ?? null,
        ]);

        return redirect()->route('energy-kits.show', $energyKit)
            ->with('success', __('messages.kits.flash_reserved', [
                'title' => $energyKit->title,
                'days' => $daysCount,
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'total' => number_format($totalPrice, 2),
            ]));
    }

    /**
     * Toggle active status.
     */
    public function toggle(EnergyKit $energyKit)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $energyKit->user_id) {
            abort(403, 'Action non autorisée.');
        }

        $energyKit->update(['is_active' => !$energyKit->is_active]);

        return back()->with('success', __('messages.kits.flash_toggled', [
            'status' => $energyKit->is_active ? __('messages.kits.active_online') : __('messages.kits.inactive_hidden'),
        ]));
    }
}
