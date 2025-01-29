<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckClientAccess
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'CLIENTE') {
            // Obtén el ID del cliente de la ruta
            $routeClientId = $request->route('client') ?? $request->route('clientId');

            // Compara con el ID del cliente autenticado
            if ($routeClientId && $routeClientId != Auth::id()) {
                return redirect()->route('acceso-denegado');
            }
        }

        return $next($request);
    }
}