<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $equipmentCount = $user->equipment()->count();
        $rentalsCount = $user->rentals()->count();
        $lentRentalsCount = $user->lentRentals()->count();

        return view('pages.front.profile.edit', compact(
            'user',
            'equipmentCount',
            'rentalsCount',
            'lentRentalsCount'
        ));
    }

    /**
     * Update the user's personal profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'solar_role' => ['nullable', 'in:rent,lend,both'],
        ]);

        $user->name = trim($validated['name']);
        $user->email = trim($validated['email']);

        // Only allow changing solar_role if user is not a system administrator
        if (! $user->isAdmin() && ! empty($validated['solar_role'])) {
            $user->solar_role = $validated['solar_role'];
        }

        $user->save();

        return back()->with('status', 'profile-updated')
            ->with('success', 'Vos informations personnelles ont été modifiées avec succès.');
    }

    /**
     * Upload or update the user's profile avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp,gif',
                'max:2048', // 2 MB max
            ],
        ], [
            'avatar.required' => 'Veuillez sélectionner un fichier image.',
            'avatar.image' => 'Le fichier doit être une image valide.',
            'avatar.mimes' => 'Formats acceptés : JPEG, PNG, JPG, WebP ou GIF.',
            'avatar.max' => 'La taille maximale de la photo est de 2 Mo.',
        ]);

        $user = $request->user();

        // Delete previous avatar file if stored on public disk
        if (! empty($user->avatar) && ! str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store uploaded avatar in storage/app/public/avatars
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return back()->with('status', 'avatar-updated')
            ->with('success', 'Votre photo de profil a été mise à jour.');
    }

    /**
     * Delete the current user's profile avatar.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! empty($user->avatar)) {
            if (! str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
            $user->save();
        }

        return back()->with('status', 'avatar-deleted')
            ->with('success', 'Votre photo de profil a été retirée.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();
        $rules = [];

        // Only require current password if the user has previously explicitly set one!
        // Social login users (Google/GitHub) who haven't set a password do not need an old password.
        if ($user->hasSetPassword()) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $rules['password'] = [
            'required',
            'confirmed',
            Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
        ];

        $messages = [
            'current_password.required' => 'Veuillez renseigner votre mot de passe actuel.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Veuillez renseigner un nouveau mot de passe.',
            'password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
        ];

        $validated = $request->validate($rules, $messages);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ])->save();

        return back()->with('status', 'password-updated')
            ->with('success', 'Votre mot de passe a été configuré avec succès.');
    }

    /**
     * Delete the user account permanently.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Safety check: protect the last admin account
        if ($user->isAdmin() && User::where('role', 'admin')->orWhere('solar_role', 'admin')->count() <= 1) {
            return back()->with('error', 'Action impossible : vous êtes le seul administrateur du système.');
        }

        // Require password confirmation ONLY if user has set a custom password
        if ($user->hasSetPassword()) {
            $request->validate([
                'password' => ['required', 'current_password'],
            ], [
                'password.required' => 'Veuillez entrer votre mot de passe pour confirmer la suppression.',
                'password.current_password' => 'Mot de passe incorrect. Suppression annulée.',
            ]);
        } else {
            // For OAuth users who signed up with Google/GitHub without setting a password yet
            $request->validate([
                'confirm_deletion' => ['required', 'accepted'],
            ], [
                'confirm_deletion.accepted' => 'Veuillez cocher la case pour confirmer la suppression définitive de votre compte.',
            ]);
        }

        // Clean up avatar from disk if stored locally
        if (! empty($user->avatar) && ! str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Votre compte et vos données ont été définitivement supprimés.');
    }
}

