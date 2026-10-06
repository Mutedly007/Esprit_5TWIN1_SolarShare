<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRenter
{
    /**
     * Handle an incoming request.
     * Ensure only users with the renter role ('rent', 'both', or 'admin') can reserve equipment.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isRenter()) {
            abort(403, 'Accès réservé aux emprunteurs (rôle Rent ou Both). Votre rôle actuel de prêteur ne vous permet pas de réserver des équipements.');
        }

        return $next($request);
    }
}
