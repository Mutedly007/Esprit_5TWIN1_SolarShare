<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionHubRequest;
use App\Http\Requests\StoreOperatingSlotRequest;
use App\Http\Requests\UpdateCollectionHubRequest;
use App\Http\Requests\UpdateOperatingSlotRequest;
use App\Models\CollectionHub;
use App\Models\OperatingSlot;
use App\Services\GeminiHubScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * AI Schedule Assistant: Generate proposed operating slots from natural language description.
     */
    public function aiGenerateSlots(Request $request, CollectionHub $hub, GeminiHubScheduleService $geminiService)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'description.required' => 'Veuillez saisir une description des horaires (ex: "Ouvert du lundi au vendredi de 9h à 18h...").',
            'description.min' => 'La description doit comporter au moins 5 caractères.',
        ]);

        try {
            $proposedSlots = $geminiService->generateSlots($validated['description']);

            if (empty($proposedSlots)) {
                return response()->json([
                    'success' => false,
                    'message' => "L'assistant IA n'a pas pu identifier de créneaux horaires dans votre description. Veuillez préciser les jours et heures.",
                ], 422);
            }

            // Fetch existing active slots for this hub to check for overlaps
            $existingSlots = $hub->operatingSlots()->where('is_active', true)->get();

            $evaluatedSlots = [];
            $hasWarnings = false;
            $validDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

            foreach ($proposedSlots as $index => $slot) {
                $day = strtolower($slot['day_of_week'] ?? '');
                $open = $slot['open_time'] ?? '';
                $close = $slot['close_time'] ?? '';
                $notes = $slot['notes'] ?? 'Généré par IA';

                $isValid = true;
                $warning = null;

                // 1. Check valid day
                if (!in_array($day, $validDays, true)) {
                    $isValid = false;
                    $warning = "Jour de la semaine invalide : {$day}.";
                }

                // 2. Check time format (HH:MM)
                elseif (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $open) || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $close)) {
                    $isValid = false;
                    $warning = "Format d'heure invalide (attendu HH:MM).";
                }

                // 3. Check closing time is strictly after opening time
                elseif (strcmp($close, $open) <= 0) {
                    $isValid = false;
                    $warning = "L'heure de fermeture ({$close}) doit être postérieure à l'ouverture ({$open}).";
                }

                // 4. Overlap check with existing hub slots
                if ($isValid) {
                    $overlapWithExisting = $existingSlots->first(function ($existing) use ($day, $open, $close) {
                        if ($existing->day_of_week !== $day) {
                            return false;
                        }
                        return !(strcmp($close, $existing->open_time) <= 0 || strcmp($open, $existing->close_time) >= 0);
                    });

                    if ($overlapWithExisting) {
                        $isValid = false;
                        $warning = "Chevauchement détecté avec le créneau existant du {$overlapWithExisting->formatted_day} ({$overlapWithExisting->formatted_hours}).";
                        $hasWarnings = true;
                    }
                }

                // 5. Overlap check with previously accepted proposed slots on the same day
                if ($isValid) {
                    foreach ($evaluatedSlots as $prevSlot) {
                        if ($prevSlot['is_valid'] && $prevSlot['day_of_week'] === $day) {
                            $overlapsPrev = !(strcmp($close, $prevSlot['open_time']) <= 0 || strcmp($open, $prevSlot['close_time']) >= 0);
                            if ($overlapsPrev) {
                                $isValid = false;
                                $warning = "Chevauchement interne avec le créneau proposé {$prevSlot['open_time']} - {$prevSlot['close_time']}.";
                                $hasWarnings = true;
                                break;
                            }
                        }
                    }
                }

                $evaluatedSlots[] = [
                    'id' => 'prop_' . $index,
                    'day_of_week' => $day,
                    'open_time' => $open,
                    'close_time' => $close,
                    'notes' => $notes,
                    'is_valid' => $isValid,
                    'warning' => $warning,
                ];
            }

            return response()->json([
                'success' => true,
                'total_proposed' => count($evaluatedSlots),
                'valid_count' => count(array_filter($evaluatedSlots, fn($s) => $s['is_valid'])),
                'has_warnings' => $hasWarnings,
                'slots' => $evaluatedSlots,
                'hub_id' => $hub->id,
                'hub_name' => $hub->name,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AI Schedule Assistant: Confirm and persist validated slots in bulk.
     */
    public function aiApplySlots(Request $request, CollectionHub $hub)
    {
        $validated = $request->validate([
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.day_of_week' => ['required', 'string', 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'],
            'slots.*.open_time' => ['required', 'date_format:H:i'],
            'slots.*.close_time' => ['required', 'date_format:H:i', 'after:slots.*.open_time'],
            'slots.*.notes' => ['nullable', 'string', 'max:255'],
        ], [
            'slots.required' => 'Aucune plage horaire sélectionnée pour l\'enregistrement.',
            'slots.min' => 'Vous devez valider au moins une plage horaire.',
            'slots.*.close_time.after' => 'L\'heure de fermeture doit être après l\'heure d\'ouverture.',
        ]);

        $createdCount = 0;

        DB::transaction(function () use ($hub, $validated, &$createdCount) {
            foreach ($validated['slots'] as $slotData) {
                $hub->operatingSlots()->create([
                    'day_of_week' => strtolower($slotData['day_of_week']),
                    'open_time' => $slotData['open_time'],
                    'close_time' => $slotData['close_time'],
                    'notes' => $slotData['notes'] ?: 'Généré par Assistant IA',
                    'is_active' => true,
                ]);
                $createdCount++;
            }
        });

        return redirect()->route('admin.hubs.show', $hub)
            ->with('success', "✨ {$createdCount} plage(s) horaire(s) générée(s) par l'Assistant IA ont été enregistrées avec succès pour « {$hub->name} » !");
    }
}
