<?php
// Archivo: app/Http/Controllers/BitacoraActualizacionController.php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\BitacoraActualizacion;
use App\Models\BitacoraHistorialEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BitacoraActualizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bitacora_id' => 'required|exists:bitacoras,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $actualizaciones = BitacoraActualizacion::where('bitacora_id', $request->bitacora_id)
            ->with(['categoria', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($actualizaciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bitacora_id' => 'required|exists:bitacoras,id',
            'categoria_id' => 'required|exists:bitacora_categorias,id',
            'descripcion' => 'required|string',
            'tiempo_dedicado' => 'nullable|integer|min:1',
            'estado' => 'required|in:completado,en_proceso,pendiente,comentario',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtener la bitácora
        $bitacora = Bitacora::findOrFail($request->bitacora_id);
        
        // Determinar si es un comentario
        $esComentario = $request->estado === 'comentario' || $bitacora->fecha_completado !== null;
        
        // Crear la actualización
        $actualizacion = BitacoraActualizacion::create([
            'bitacora_id' => $request->bitacora_id,
            'user_id' => Auth::id(),
            'categoria_id' => $request->categoria_id,
            'descripcion' => $request->descripcion,
            'tiempo_dedicado' => $request->tiempo_dedicado,
            'estado' => $esComentario ? 'comentario' : $request->estado,
            'es_comentario' => $esComentario,
        ]);
        
        // Si no es un comentario, actualizar el estado de la bitácora principal
        if (!$esComentario) {
            $this->actualizarEstadoBitacoraPrincipal($bitacora);
        }
        
        return response()->json($actualizacion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BitacoraActualizacion $actualizacion)
    {
        $actualizacion->load(['categoria', 'usuario']);
        return response()->json($actualizacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BitacoraActualizacion $actualizacion)
    {
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'exists:bitacora_categorias,id',
            'descripcion' => 'string',
            'tiempo_dedicado' => 'nullable|integer|min:1',
            'estado' => 'in:completado,en_proceso,pendiente,comentario',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtener la bitácora
        $bitacora = $actualizacion->bitacora;
        
        // Determinar si es un comentario
        $esComentario = $request->estado === 'comentario' || $bitacora->fecha_completado !== null;
        
        // Actualizar los datos
        $actualizacion->update([
            'categoria_id' => $request->categoria_id ?? $actualizacion->categoria_id,
            'descripcion' => $request->descripcion ?? $actualizacion->descripcion,
            'tiempo_dedicado' => $request->tiempo_dedicado ?? $actualizacion->tiempo_dedicado,
            'estado' => $esComentario ? 'comentario' : ($request->estado ?? $actualizacion->estado),
            'es_comentario' => $esComentario,
        ]);
        
        // Si no es un comentario, actualizar el estado de la bitácora principal
        if (!$esComentario) {
            $this->actualizarEstadoBitacoraPrincipal($bitacora);
        }
        
        return response()->json($actualizacion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BitacoraActualizacion $actualizacion)
    {
        $bitacora = $actualizacion->bitacora;
        $actualizacion->delete();
        
        // Actualizar el estado de la bitácora principal
        $this->actualizarEstadoBitacoraPrincipal($bitacora);
        
        return response()->json(null, 204);
    }
    
    /**
     * Actualizar el estado de la bitácora principal basado en sus actualizaciones.
     */
    private function actualizarEstadoBitacoraPrincipal(Bitacora $bitacora)
    {
        // Solo actualizamos el estado si la bitácora no está completada
        if ($bitacora->fecha_completado) {
            return;
        }
        
        // Obtener todas las actualizaciones que no son comentarios
        $actualizaciones = $bitacora->actualizaciones()
            ->where('es_comentario', false)
            ->get();
            
        if ($actualizaciones->isEmpty()) {
            return;
        }
        
        // Verificar si todas están completadas
        $todasCompletadas = $actualizaciones->every(function ($actualizacion) {
            return $actualizacion->estado === 'completado';
        });
        
        if ($todasCompletadas) {
            // Marcar la bitácora como completada
            $bitacora->estado = 'completado';
            $bitacora->fecha_completado = now();
            $bitacora->save();
            
            // Registrar en el historial
            BitacoraHistorialEstado::create([
                'bitacora_id' => $bitacora->id,
                'user_id' => Auth::id(),
                'accion' => 'Marcada como completada automáticamente (todas las sub-tareas completadas)',
                'fecha' => now(),
            ]);
        } else {
            // Verificar si hay alguna en proceso
            $algunaEnProceso = $actualizaciones->contains(function ($actualizacion) {
                return $actualizacion->estado === 'en_proceso';
            });
            
            $nuevoEstado = $algunaEnProceso ? 'en_proceso' : 'pendiente';
            
            // Solo actualizar si el estado cambió
            if ($bitacora->estado !== $nuevoEstado) {
                $estadoAnterior = $bitacora->estado;
                $bitacora->estado = $nuevoEstado;
                $bitacora->save();
                
                // Registrar en el historial
                BitacoraHistorialEstado::create([
                    'bitacora_id' => $bitacora->id,
                    'user_id' => Auth::id(),
                    'accion' => "Estado cambiado de \"$estadoAnterior\" a \"$nuevoEstado\" (basado en actualizaciones)",
                    'fecha' => now(),
                ]);
            }
        }
    }
}