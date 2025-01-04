<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Expediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraController extends Controller
{
    public function index(Expediente $expediente)
    {
        $bitacoras = $expediente->bitacoras()->with('usuario')->latest()->paginate(15);
        return view('bitacoras.index', compact('expediente', 'bitacoras'));
    }

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

    public function show(Expediente $expediente, Bitacora $bitacora)
    {
        return view('bitacoras.show', compact('expediente', 'bitacora'));
    }

    public function edit(Expediente $expediente, Bitacora $bitacora)
    {
        return view('bitacoras.edit', compact('expediente', 'bitacora'));
    }

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

    public function destroy(Expediente $expediente, Bitacora $bitacora)
    {
        $bitacora->delete();

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Entrada de bitácora eliminada correctamente');
    }
}