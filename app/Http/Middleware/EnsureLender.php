<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLender
{
    /**
     * Handle an incoming request.
     * Ensure only users with the lender role ('lend', 'both', or 'admin') can access the lender space.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isLender()) {
            abort(403, 'Accès réservé aux prêteurs (rôle Lend ou Both).');
        }

        return $next($request);
    }
}
