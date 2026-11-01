<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users for the backoffice administrator.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $solarRole = $request->query('solar_role');
        $provider = $request->query('provider');
        $sort = $request->query('sort', 'latest');

        $query = User::withCount(['equipment', 'rentals']);

        // Search by keyword in name or email
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Solar role filter
        if (! empty($solarRole)) {
            if ($solarRole === 'admin') {
                $query->where(function ($q) {
                    $q->where('role', 'admin')
                      ->orWhere('solar_role', 'admin');
                });
            } else {
                $query->where('solar_role', $solarRole);
            }
        }

        // Provider filter (google, github, email)
        if (! empty($provider)) {
            if ($provider === 'email') {
                $query->whereNull('provider');
            } else {
                $query->where('provider', $provider);
            }
        }

        // Sorting
        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'equipment_desc' => $query->orderBy('equipment_count', 'desc'),
            'rentals_desc' => $query->orderBy('rentals_count', 'desc'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'lenders' => User::whereIn('solar_role', ['lend', 'both'])->count(),
            'renters' => User::whereIn('solar_role', ['rent', 'both'])->count(),
            'admins' => User::where('role', 'admin')->orWhere('solar_role', 'admin')->count(),
        ];

        return view('pages.admin.users.index', compact(
            'users',
            'stats',
            'search',
            'solarRole',
            'provider',
            'sort'
        ));
    }

    /**
     * Display detailed profile and activities for a specific user.
     */
    public function show(User $user): View
    {
        $user->load([
            'equipment.category',
            'rentals.equipment',
        ]);

        $user->loadCount(['equipment', 'rentals']);

        $lentRentalsCount = $user->lentRentals()->count();

        return view('pages.admin.users.show', compact('user', 'lentRentalsCount'));
    }

    /**
     * Update a user's role from the admin panel.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'solar_role' => ['required', 'in:rent,lend,both,admin'],
            'role' => ['nullable', 'in:user,admin'],
        ]);

        $solarRole = $validated['solar_role'];
        $role = $validated['role'] ?? ($solarRole === 'admin' ? 'admin' : 'user');

        $user->update([
            'solar_role' => $solarRole,
            'role' => $role,
        ]);

        return back()->with('success', "Le rôle de l'utilisateur « {$user->name} » a été mis à jour avec succès.");
    }

    /**
     * Delete a user account from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte administrateur.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "L'utilisateur « {$name} » a été supprimé.");
    }
}
