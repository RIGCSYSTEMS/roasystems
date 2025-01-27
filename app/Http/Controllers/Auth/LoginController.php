<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
{
        // Verificar si el rol es 'CLIENTE'
        if ($user->role === 'CLIENTE') {
            // Comprobar si el correo del cliente existe en la base de datos
            $clienteExistente = User::where('email', $user->email)->where('role', 'CLIENTE')->first();
    
            if (!$clienteExistente) {
                // Si no existe, redirigir a una página de error o acceso no autorizado
                return redirect('/no-autorizado')->with('error', 'Este cliente no está registrado.');
            }
    
            // Si existe, redirigir al perfil del cliente
            return redirect('/client/' . $user->id);
        }
    

    // Redirige al usuario según su rol
    switch ($user->role) {
        case 'ADMIN':
            return redirect('/');
        case 'DIRECTOR':
            return redirect('/');
        case 'ABOGADO':
            return redirect('/');
        case 'CLIENTE':
            return redirect('/');
        default:
            return redirect('/no-autorizado'); // Si el rol no es válido
    }
}
}
