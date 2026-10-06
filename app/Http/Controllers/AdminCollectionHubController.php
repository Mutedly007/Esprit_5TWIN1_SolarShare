<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionHubRequest;
use App\Http\Requests\StoreOperatingSlotRequest;
use App\Http\Requests\UpdateCollectionHubRequest;
use App\Http\Requests\UpdateOperatingSlotRequest;
use App\Models\CollectionHub;
use App\Models\OperatingSlot;
use Illuminate\Http\Request;

class AdminCollectionHubController extends Controller
{
    /**
     * Display a listing of collection hubs for administrators.
     */
    public function index(Request $request)
    {
        $city = $request->query('city');
        $search = $request->query('q');
        $status = $request->query('status');

        $query = CollectionHub::withCount('operatingSlots')->latest();

        if ($city) {
            $query->where('city', $city);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === 'active');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }

        $hubs = $query->paginate(10)->withQueryString();

        $cities = CollectionHub::select('city')->distinct()->orderBy('city')->pluck('city');

        $stats = [
            'total_hubs' => CollectionHub::count(),
            'active_hubs' => CollectionHub::where('is_active', true)->count(),
            'total_capacity' => CollectionHub::where('is_active', true)->sum('max_capacity'),
            'total_slots' => OperatingSlot::count(),
        ];

        return view('pages.admin.hubs.index', compact('hubs', 'cities', 'stats', 'city', 'search', 'status'));
    }

    /**
     * Show the form for creating a new collection hub.
     */
    public function create()
    {
        return view('pages.admin.hubs.create');
    }

    /**
     * Store a newly created collection hub in storage.
     */
    public function store(StoreCollectionHubRequest $request)
    {
        $hub = CollectionHub::create($request->validated());

        return redirect()->route('admin.hubs.show', $hub)
            ->with('success', "Le point relais « {$hub->name} » a été créé avec succès. Vous pouvez maintenant configurer ses plages horaires.");
    }

    /**
     * Display the specified collection hub with its operating slots.
     */
    public function show(CollectionHub $hub)
    {
        $hub->load(['operatingSlots' => function ($q) {
            $q->orderByRaw("CASE 
                WHEN day_of_week = 'lundi' THEN 1 
                WHEN day_of_week = 'mardi' THEN 2 
                WHEN day_of_week = 'mercredi' THEN 3 
                WHEN day_of_week = 'jeudi' THEN 4 
                WHEN day_of_week = 'vendredi' THEN 5 
                WHEN day_of_week = 'samedi' THEN 6 
                WHEN day_of_week = 'dimanche' THEN 7 
                ELSE 8 END");
        }]);

        $daysOfWeek = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        return view('pages.admin.hubs.show', compact('hub', 'daysOfWeek'));
    }

    /**
     * Show the form for editing the specified collection hub.
     */
    public function edit(CollectionHub $hub)
    {
        return view('pages.admin.hubs.edit', compact('hub'));
    }

    /**
     * Update the specified collection hub in storage.
     */
    public function update(UpdateCollectionHubRequest $request, CollectionHub $hub)
    {
        $hub->update($request->validated());

        return redirect()->route('admin.hubs.show', $hub)
            ->with('success', "Le point relais « {$hub->name} » a été mis à jour avec succès.");
    }

    /**
     * Remove the specified collection hub from storage.
     */
    public function destroy(CollectionHub $hub)
    {
        $name = $hub->name;
        $hub->delete();

        return redirect()->route('admin.hubs.index')
            ->with('success', "Le point relais « {$name} » et ses créneaux associés ont été supprimés.");
    }

    /**
     * Toggle the active status of a collection hub.
     */
    public function toggle(CollectionHub $hub)
    {
        $hub->is_active = ! $hub->is_active;
        $hub->save();

        $statusText = $hub->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Le point relais « {$hub->name} » a été {$statusText}.");
    }

    /**
     * Store a new operating slot for a hub.
     */
    public function storeSlot(StoreOperatingSlotRequest $request, CollectionHub $hub)
    {
        $hub->operatingSlots()->create($request->validated());

        return back()->with('success', 'La nouvelle plage horaire a été ajoutée avec succès.');
    }

    /**
     * Update an operating slot.
     */
    public function updateSlot(UpdateOperatingSlotRequest $request, OperatingSlot $slot)
    {
        $slot->update($request->validated());

        return back()->with('success', 'La plage horaire a été mise à jour avec succès.');
    }

    /**
     * Delete an operating slot.
     */
    public function destroySlot(OperatingSlot $slot)
    {
        $slot->delete();

        return back()->with('success', 'La plage horaire a été supprimée avec succès.');
    }

    /**
     * Toggle an operating slot status.
     */
    public function toggleSlot(OperatingSlot $slot)
    {
        $slot->is_active = ! $slot->is_active;
        $slot->save();

        return back()->with('success', 'Le statut du créneau horaire a été modifié.');
    }
}
