<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
class ExpedienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//
    }

    public function create(Request $request)
    {
//
    }

    public function store(Request $request)
    {
//
    }

    // public function show(Expediente $expediente)
    public function show($id)
    {
        $expediente = Expediente::with([
            'client',
            'tipoExpediente',
            'bitacoras',
            'audiencias',
            'honorarios'
        ])->findOrFail($id);

        return view('expedientes.index', [
            'expedienteId' => $id,
            'expediente' => $expediente
        ]);
    }

    

    public function edit(Expediente $expediente)
    {
//
    }

    public function update(Request $request, Expediente $expediente)
    {
        // Verificar si el usuario tiene permiso para editar
        if ($expediente->estatus_del_expediente === 'Cerrado' && 
            !in_array(Auth::user()->role, ['DIRECTOR', 'ADMIN'])) {
            return response()->json(['error' => 'No tienes permiso para editar este expediente'], 403);
        }
    
    // Validación de los datos del request
    $validatedData = $request->validate([
        'numero_de_dossier' => 'required|string',
        'prioridad' => 'required|string',
        'fecha_de_apertura' => 'required|date',
        'fecha_de_cierre' => 'nullable|date',
        'despacho' => 'nullable|string',
        'abogado' => 'nullable|string',
        'plazo_fda' => 'nullable|date',
        'boite' => 'nullable|string',
        'observaciones' => 'nullable|string',
    ]);
        
    // Actualizar el expediente
    $expediente->update($validatedData);
    
    return response()->json($expediente, 200);
}

    public function destroy(Expediente $expediente)
    {
//
    }
    public function updateStatus(Request $request, Expediente $expediente)
{
    // Verifica permisos
    if (!in_array(Auth::user()->role, ['DIRECTOR', 'ADMIN']) && $expediente->estatus_del_expediente === 'Cerrado') {
        return response()->json(['error' => 'No tienes permiso para cambiar el estado de este expediente'], 403);
    }

    $request->validate([
        'estado' => 'required|in:Abierto,Cerrado,Cancelado',
    ]);

    $expediente->estatus_del_expediente = $request->estado;
    $expediente->save();

    return response()->json($expediente);
}
    
}