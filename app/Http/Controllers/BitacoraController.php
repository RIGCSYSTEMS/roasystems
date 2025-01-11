<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Expediente;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraController extends Controller
{
    // Lista las bitácoras de un expediente específico
    public function index(Expediente $expediente = null, Client $client = null)
    {
        if ($expediente) {
            // Si se proporciona un expediente, muestra sus bitácoras
            $bitacoras = $expediente->bitacoras()->with('usuario')->latest()->paginate(15);
            return view('bitacoras.index', compact('expediente', 'bitacoras'));
        } elseif ($client) {
            // Si se proporciona un cliente, muestra todas sus bitácoras a través de sus expedientes
            $bitacoras = Bitacora::whereIn('expediente_id', $client->expedientes->pluck('id'))
                ->with(['usuario', 'expediente'])
                ->latest()
                ->paginate(15);
            return view('bitacoras.index', compact('client', 'bitacoras'));
        }
    }

    // Almacena una nueva bitácora
    public function store(Request $request, Expediente $expediente)
    {
        $validatedData = $request->validate([
            'categoria' => 'required|string',
            'descripcion' => 'required|string',
            'tiempo_empleado' => 'nullable|integer',
            'fecha_y_hora_del_evento' => 'required|date',
        ]);

        $validatedData['usuario_id'] = Auth::id();
        $validatedData['expediente_id'] = $expediente->id;

        $bitacora = Bitacora::create($validatedData);

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Entrada de bitácora creada correctamente');
    }

    // Muestra una bitácora específica
    public function show(Expediente $expediente, Bitacora $bitacora)
    {
        return view('bitacoras.show', compact('expediente', 'bitacora'));
    }

    // Muestra el formulario de edición
    public function edit(Expediente $expediente, Bitacora $bitacora)
    {
        return view('bitacoras.edit', compact('expediente', 'bitacora'));
    }

    // Actualiza una bitácora
    public function update(Request $request, Expediente $expediente, Bitacora $bitacora)
    {
        $validatedData = $request->validate([
            'categoria' => 'required|string',
            'descripcion' => 'required|string',
            'tiempo_empleado' => 'nullable|integer',
            'fecha_y_hora_del_evento' => 'required|date',
        ]);

        $bitacora->update($validatedData);

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Entrada de bitácora actualizada correctamente');
    }

    // Elimina una bitácora
    public function destroy(Expediente $expediente, Bitacora $bitacora)
    {
        $bitacora->delete();

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Entrada de bitácora eliminada correctamente');
    }
}