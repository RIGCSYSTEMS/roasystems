<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        Log::info("Middleware roles: " . $role);
        Log::info("Usuario autenticado: " . Auth::check());
        Log::info("Rol del usuario: " . Auth::user()->role);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;
        
        // Convertir la cadena de roles en un array
        $allowedRoles = is_array($role) ? $role : explode(',', $role);
        
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}

