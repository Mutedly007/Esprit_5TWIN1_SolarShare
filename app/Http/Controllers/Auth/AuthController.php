<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Show the combined login/register page.
     */
    public function show(Request $request, string $tab = 'login')
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('pages.front.Auth.auth', [
            'initialTab' => $tab === 'register' ? 'register' : 'login',
        ]);
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'solar_role' => ['nullable', 'in:rent,lend,both'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'solar_role' => $validated['solar_role'] ?? null,
            'password'   => Hash::make($validated['password']),
            'password_set_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Welcome to SolarShare, ' . $user->name . '!');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    /**
     * Redirect the user to the OAuth provider's authorization page.
     */
    public function redirectToProvider(string $provider)
    {
        if (! in_array($provider, ['google', 'github'], true)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     */
    public function handleProviderCallback(Request $request, string $provider)
    {
        if (! in_array($provider, ['google', 'github'], true)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors(['email' => 'We could not sign you in with ' . ucfirst($provider) . '. Please try again.']);
        }

        if (empty($socialUser->getEmail())) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your ' . ucfirst($provider) . ' account does not share an email address, so we could not complete the sign-in.']);
        }

        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (! $user) {
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->forceFill([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ])->save();
            }
        }

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'SolarShare Member',
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'password_set_at' => null,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        // Any account without a role goes to onboarding: pick a role first
        if (empty($user->solar_role)) {
            return redirect()
                ->route('onboarding.role')
                ->with('success', 'Bienvenue sur SolarShare, ' . $user->name . ' ! Encore une étape.');
        }

        // If the social user hasn't set their password yet, prompt them to set it
        if (! $user->hasSetPassword()) {
            return redirect()
                ->route('onboarding.password')
                ->with('info', 'Bienvenue sur SolarShare, ' . $user->name . ' ! Définissez votre mot de passe.');
        }

        return redirect()->intended(route('home'))->with('success', 'Bienvenue, ' . $user->name . ' !');
    }

    /**
     * Show the role-selection step for freshly created social-login accounts.
     */
    public function showRoleSelection(Request $request)
    {
        $user = $request->user();

        // Already has a role (or registered via the normal form)?
        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->solar_role) {
            if (! $user->hasSetPassword()) {
                return redirect()->route('onboarding.password');
            }
            return redirect()->route('home');
        }

        return view('pages.front.Auth.onboarding', ['user' => $user]);
    }

    /**
     * Persist the role chosen during the social-signup onboarding step.
     */
    public function storeRoleSelection(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'solar_role' => ['required', 'in:rent,lend,both'],
        ]);

        $user->forceFill(['solar_role' => $validated['solar_role']])->save();

        if (! $user->hasSetPassword()) {
            return redirect()->route('onboarding.password')
                ->with('success', 'Rôle enregistré ! Définissez maintenant votre mot de passe personnel.');
        }

        return redirect()->route('home')->with('success', 'Félicitations, ' . $user->name . ' ! Votre profil est prêt.');
    }

    /**
     * Show the password creation step for OAuth/social users.
     */
    public function showPasswordSelection(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasSetPassword()) {
            return redirect()->route('home');
        }

        return view('pages.front.Auth.onboarding_password', ['user' => $user]);
    }

    /**
     * Store the chosen password for OAuth/social users.
     */
    public function storePasswordSelection(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'password.required' => 'Veuillez saisir un mot de passe.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ])->save();

        return redirect()->route('home')
            ->with('success', 'Félicitations, ' . $user->name . ' ! Votre mot de passe a été configuré avec succès.');
    }

    /**
     * Allow user to optionally skip setting password right away.
     */
    public function skipPasswordSelection(Request $request)
    {
        return redirect()->route('home')
            ->with('info', 'Vous pourrez configurer votre mot de passe à tout moment depuis votre profil.');
    }
}
