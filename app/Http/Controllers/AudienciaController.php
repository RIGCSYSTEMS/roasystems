<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAudienciasRequest;
use App\Models\Audiencia;
use App\Models\Expediente;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AudienciaController extends Controller
{
    public function store(StoreAudienciasRequest $request, Expediente $expediente)
    {
try {
        $validatedData = $request->validated();

        $audiencia = $expediente->audiencias()->create($validatedData);

        Bitacora::create([
            'usuario_id' => Auth::id(),
            'expediente_id' => $expediente->id,
            'categoria' => 'Audiencia',
            'descripcion' => "Se programó una audiencia para el " . $audiencia->fecha_hora->format('d/m/Y H:i'),
            'fecha_y_hora_del_evento' => now(),
        ]);

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Audiencia programada correctamente');
    } catch (\Exception $e) {
        Log::error('Error al crear audiencia: ' . $e->getMessage());

        return redirect()->route('expedientes.show', $expediente)
            ->with('error', 'Hubo un problema al programar la audiencia. Por favor, inténtelo de nuevo.');
    }
}


    public function update(Request $request, Expediente $expediente, Audiencia $audiencia)
    {
        $validatedData = $request->validate([
            'fecha_hora' => 'required|date',
            'tipo_audiencia' => 'required|string',
            'descripcion' => 'nullable|string',
            'lugar' => 'required|string',
            'estado' => 'required|in:Programada,Realizada,Cancelada,Pendiente',
            'responsable' => 'nullable|string',
            'notas_internas' => 'nullable|string',
            'resultado' => 'nullable|string',
        ]);

        $audiencia->update($validatedData);

        // Registrar en bitácora
        Bitacora::create([
            'usuario_id' => Auth::id(),
            'expediente_id' => $expediente->id,
            'categoria' => 'Audiencia',
            'descripcion' => "Se actualizó la información de la audiencia del " . $audiencia->fecha_hora->format('d/m/Y H:i'),
            'fecha_y_hora_del_evento' => now(),
        ]);

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Audiencia actualizada correctamente');
    }

    public function destroy(Expediente $expediente, Audiencia $audiencia)
    {
        // Registrar en bitácora antes de eliminar
        Bitacora::create([
            'usuario_id' => Auth::id(),
            'expediente_id' => $expediente->id,
            'categoria' => 'Audiencia',
            'descripcion' => "Se eliminó la audiencia programada para el " . $audiencia->fecha_hora->format('d/m/Y H:i'),
            'fecha_y_hora_del_evento' => now(),
        ]);

        $audiencia->delete();

        return redirect()->route('expedientes.show', $expediente)
            ->with('success', 'Audiencia eliminada correctamente');
    }
    public function show(Expediente $expediente, Audiencia $audiencia)
{
    return view('audiencias.show', compact('expediente', 'audiencia'));
}
}