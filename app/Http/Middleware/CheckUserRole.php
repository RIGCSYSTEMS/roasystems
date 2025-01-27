<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     /**
     * Maneja una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles (uno o más roles permitidos)
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Verifica si el usuario está autenticado
        if (!Auth::check()) {
            return redirect('login'); // Redirige al login si no está autenticado
        }

        // Obtén el usuario autenticado
        $user = Auth::user();

        // Verifica si el rol del usuario está en la lista de roles permitidos
        if (!in_array($user->role, $roles)) {

            if (in_array($user->role, ['ADMIN', 'DIRECTOR', 'ABOGADO'])) {
                return $next($request);
            }
            return redirect('no-autorizado'); // Redirige si no tiene acceso
        }

         // Permite la solicitud si cumple los requisitos
    }

}





   // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }