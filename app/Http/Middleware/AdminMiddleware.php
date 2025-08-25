<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
      
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                // requêtes API
                return response()->json(['message' => 'Non authentifié'], 401);
            }
            // requêtes web
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        if (!Auth::user()->isAdmin()) {
            if ($request->expectsJson()) {
                // requêtes API
                return response()->json(['message' => 'Non autorisé'], 403);
            }
            // requêtes web
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent accéder à cette page.');
        }

        return $next($request);
    }
}