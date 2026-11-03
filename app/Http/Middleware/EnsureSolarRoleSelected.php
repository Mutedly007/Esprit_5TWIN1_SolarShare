<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSolarRoleSelected
{
    /**
     * Handle incoming requests:
     * 1. If user is unverified, lock them to the email verification notice.
     * 2. If user hasn't selected a role, lock them to the onboarding role page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // 1. Mandatory Email Verification: unverified users must verify email before accessing account
            if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
                $allowedUnverified = [
                    'verification.notice',
                    'verification.verify',
                    'verification.send',
                    'logout',
                ];

                if (! in_array($request->route()?->getName(), $allowedUnverified, true)) {
                    return redirect()->route('verification.notice');
                }

                return $next($request);
            }

            // 2. Role Onboarding: users without a solar_role are locked to the onboarding page
            if (! $user->isAdmin() && empty($user->solar_role)) {
                $allowed = [
                    'onboarding.role',
                    'onboarding.role.store',
                    'logout',
                    'verification.notice',
                    'verification.verify',
                    'verification.send',
                ];

                if (! in_array($request->route()?->getName(), $allowed, true)) {
                    return redirect()
                        ->route('onboarding.role')
                        ->with('success', 'One last step: pick how you\'ll use SolarShare.');
                }
            }
        }

        return $next($request);
    }
}

