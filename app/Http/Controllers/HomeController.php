<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
    return view('home', [
        'authUser' => [
            'name' => $user->name,
            'avatar' => $user->avatar, // Asegúrate de tener este campo en tu modelo de usuario
        ]
        ]);
        // Obtener los botones para el dashboard
        // $buttons = [
        //     ['name' => 'ASILO', 'icon' => 'images/asilo.png', 'url' => '/client'],
        //     ['name' => 'APPEL', 'icon' => '🏠', 'url' => '/client'],
        //     ['name' => 'RESIDENCIA PERMANENTE', 'icon' => 'images/card.png', 'url' => '/resident'],
        //     ['name' => 'ERAR', 'icon' => '🚫', 'url' => '/client'],
        //     ['name' => 'APADRINAMIENTO', 'icon' => '👥', 'url' => '/client'],
        //     ['name' => 'HUMANITARIAS', 'icon' => 'images/humanitario.png', 'url' => '/client'],
        //     ['name' => 'RESIDENCIA TEMPORAL', 'icon' => 'images/visa.png', 'url' => '/resident'],
        // ];
        // return view('home');
    }
}
