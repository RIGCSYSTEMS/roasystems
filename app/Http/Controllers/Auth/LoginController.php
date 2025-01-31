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
        $redirectUrl = $this->getRedirectUrl($user);

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    private function getRedirectUrl($user)
    {
        if ($user->role === 'CLIENTE') {
            $clienteExistente = User::where('email', $user->email)->where('role', 'CLIENTE')->first();
            if (!$clienteExistente) {
                return '/no-autorizado';
            }
            return '/client/' . $user->id;
        }

        switch ($user->role) {
            case 'ADMIN':
            case 'DIRECTOR':
            case 'ABOGADO':
                return '/';
            default:
                return '/acceso-denegado';
        }
    }
}