<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Category;
use App\Models\Equipment;
use App\Services\GeminiEquipmentVisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment belonging to the authenticated lender.
     */
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $selectedCategory = $request->query('category');
        $search = $request->query('search');

        $query = Equipment::with('category')
            ->where('user_id', $userId);

        if (! empty($selectedCategory)) {
            $query->where('category_id', $selectedCategory);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $equipment = $query->latest()->get();

        // Group equipment by category for the visual category-sections view (Section 6)
        $categoriesWithEquipment = Category::with(['equipment' => function ($q) use ($userId) {
            $q->where('user_id', $userId)->latest();
        }])->get();

        $allCategories = Category::orderBy('name')->get();

        return view('lender.equipment.index', compact(
            'equipment',
            'categoriesWithEquipment',
            'allCategories',
            'selectedCategory',
            'search'
        ));
    }

    /**
     * Show the form for creating new equipment.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('lender.equipment.create', compact('categories'));
    }

    /**
     * Analyze an uploaded equipment photo using Gemini Vision AI.
     */
    public function analyzePhoto(Request $request, GeminiEquipmentVisionService $visionService): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ], [
            'image.required' => 'Veuillez sélectionner une image à analyser.',
            'image.image' => 'Le fichier doit être une image valide.',
            'image.mimes' => 'Formats acceptés : JPG, JPEG, PNG, WEBP.',
            'image.max' => 'L\'image ne doit pas dépasser 10 Mo.',
        ]);

        $result = $visionService->analyzePhoto($request->file('image'));

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Une erreur est survenue lors de l\'analyse de la photo.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }

    /**
     * Store newly created equipment in storage.
     * Status is automatically set to 'pending' ("Non traité").
     */
    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending'; // Always "Non traité" initially
        $validated['is_available'] = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        Equipment::create($validated);

        return redirect()
            ->route('equipment.index')
            ->with('success', 'Équipement créé avec succès ! Il apparaît maintenant dans votre espace (Statut : Non traité).');
    }

    /**
     * Display the specified equipment.
     */
    public function show(Equipment $equipment): View
    {
        $this->authorizeOwnership($equipment);

        $equipment->load('category');

        return view('lender.equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function edit(Equipment $equipment): View
    {
        $this->authorizeOwnership($equipment);

        $categories = Category::orderBy('name')->get();

        return view('lender.equipment.edit', compact('equipment', 'categories'));
    }

    /**
     * Update the specified equipment in storage.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $this->authorizeOwnership($equipment);

        $validated = $request->validated();
        $validated['is_available'] = $request->boolean('is_available');

        if ($request->hasFile('image')) {
            // Delete previous image if exists
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($equipment->image);
            }
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment->update($validated);

        return redirect()
            ->route('equipment.index')
            ->with('success', 'Équipement mis à jour avec succès.');
    }

    /**
     * Remove the specified equipment from storage.
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $this->authorizeOwnership($equipment);

        $equipmentName = $equipment->name;

        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()
            ->route('equipment.index')
            ->with('success', "L'équipement « {$equipmentName} » a été supprimé avec succès.");
    }

    /**
     * Ensure the authenticated user owns the equipment.
     */
    protected function authorizeOwnership(Equipment $equipment): void
    {
        if ($equipment->user_id !== auth()->id()) {
            abort(403, 'Accès refusé : cet équipement ne vous appartient pas.');
        }
    }
}
