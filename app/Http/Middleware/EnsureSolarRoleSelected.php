<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSolarRoleSelected
{
    /**
     * Logged-in users without a solar_role are locked to the onboarding page
     * until they pick Rent / Lend / Both. Everything else bounces back there.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isAdmin() && empty($user->solar_role)) {
            // Routes the user must still be able to reach while un-onboarded.
            $allowed = [
                'onboarding.role',
                'onboarding.role.store',
                'logout',
                'verification.notice',
                'verification.verify',
                'verification.send',
            ];

            if (! in_array($request->route()->getName(), $allowed, true)) {
                return redirect()
                    ->route('onboarding.role')
                    ->with('success', 'One last step: pick how you\'ll use SolarShare.');
            }
        }

        return $next($request);
    }
}
