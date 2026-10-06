<?php

namespace App\Http\Controllers;

use App\Models\CollectionHub;
use Illuminate\Http\Request;

class CollectionHubController extends Controller
{
    /**
     * Display a public listing of active collection hubs.
     */
    public function index(Request $request)
    {
        $city = $request->query('city');
        $search = $request->query('q');
        $onlyOpenToday = $request->boolean('open_today');

        $query = CollectionHub::with(['operatingSlots' => function ($q) {
            $q->where('is_active', true)
              ->orderByRaw("CASE 
                WHEN day_of_week = 'lundi' THEN 1 
                WHEN day_of_week = 'mardi' THEN 2 
                WHEN day_of_week = 'mercredi' THEN 3 
                WHEN day_of_week = 'jeudi' THEN 4 
                WHEN day_of_week = 'vendredi' THEN 5 
                WHEN day_of_week = 'samedi' THEN 6 
                WHEN day_of_week = 'dimanche' THEN 7 
                ELSE 8 END");
        }])
        ->active()
        ->latest();

        if ($city) {
            $query->where('city', $city);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $hubs = $query->paginate(9)->withQueryString();

        if ($onlyOpenToday) {
            // Filter collection in memory if requested
            $filteredItems = $hubs->getCollection()->filter(fn ($hub) => $hub->isOpenToday());
            $hubs->setCollection($filteredItems);
        }

        $cities = CollectionHub::active()->select('city')->distinct()->orderBy('city')->pluck('city');

        $stats = [
            'total_hubs' => CollectionHub::active()->count(),
            'total_capacity' => CollectionHub::active()->sum('max_capacity'),
            'cities_count' => $cities->count(),
        ];

        return view('pages.front.hubs.index', compact('hubs', 'cities', 'stats', 'city', 'search', 'onlyOpenToday'));
    }

    /**
     * Display the detailed public page of a collection hub.
     */
    public function show(CollectionHub $hub)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        if (! $hub->is_active && ! $isAdmin) {
            abort(404, 'Ce point relais n\'est pas disponible actuellement.');
        }

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

        // Other nearby hubs in the same city
        $nearbyHubs = CollectionHub::active()
            ->where('id', '!=', $hub->id)
            ->where('city', $hub->city)
            ->take(3)
            ->get();

        return view('pages.front.hubs.show', compact('hub', 'nearbyHubs'));
    }
}
