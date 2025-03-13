<?php
// Archivo: app/Http/Controllers/BitacoraController.php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\BitacoraHistorialEstado;
use App\Models\Expediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BitacoraController extends Controller
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
        $query = Bitacora::with(['categoria', 'usuario', 'actualizaciones', 'historialEstados']);
        
        // Filtrar por expediente si se proporciona
        if ($request->has('expediente_id')) {
            $query->where('expediente_id', $request->expediente_id);
        }
        
        // Filtrar por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        // Filtrar por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Filtrar por próximos a vencer
        if ($request->has('proximos_a_vencer') && $request->proximos_a_vencer) {
            $query->where('estado', '!=', 'completado')
                  ->whereNotNull('fecha_limite');
                  
            $bitacoras = $query->get()->filter(function ($bitacora) {
                return $bitacora->estaProximaAVencer();
            });
            
            return response()->json($bitacoras);
        }
        
        // Filtrar por vencidos
        if ($request->has('vencidos') && $request->vencidos) {
            $query->where('estado', '!=', 'completado')
                  ->whereNotNull('fecha_limite');
                  
            $bitacoras = $query->get()->filter(function ($bitacora) {
                return $bitacora->estaVencida();
            });
            
            return response()->json($bitacoras);
        }
        
        // Filtrar por reactivados
        if ($request->has('reactivados') && $request->reactivados) {
            $query->whereNotNull('fecha_reactivacion');
        }
        
        $bitacoras = $query->orderBy('created_at', 'desc')->get();
        return response()->json($bitacoras);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expediente_id' => 'required|exists:expedientes,id',
            'tipo' => 'required|in:normal,seguimiento',
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:bitacora_categorias,id',
            'descripcion' => 'required|string',
            'tiempo_dedicado' => 'nullable|integer|min:1',
            'estado' => 'required|in:completado,en_proceso,pendiente',
            'fecha_limite' => 'nullable|date|required_if:tipo,seguimiento',
            'prioridad_fecha' => 'nullable|in:normal,importante,critica|required_if:tipo,seguimiento',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verificar que el expediente existe
        $expediente = Expediente::findOrFail($request->expediente_id);
        
        // Preparar datos para la bitácora
        $bitacoraData = $request->all();
        $bitacoraData['user_id'] = Auth::id();
        
        // Si es una bitácora normal, siempre se marca como completada
        if ($request->tipo === 'normal') {
            $bitacoraData['estado'] = 'completado';
            $bitacoraData['fecha_completado'] = now();
        } elseif ($request->estado === 'completado') {
            $bitacoraData['fecha_completado'] = now();
        }
        
        // Crear la bitácora
        $bitacora = Bitacora::create($bitacoraData);
        
        // Registrar en el historial de estados
        BitacoraHistorialEstado::create([
            'bitacora_id' => $bitacora->id,
            'user_id' => Auth::id(),
            'accion' => 'Creación de bitácora',
            'fecha' => now(),
        ]);
        
        // Si se marcó como completada, registrar también ese estado
        if ($bitacora->estado === 'completado') {
            BitacoraHistorialEstado::create([
                'bitacora_id' => $bitacora->id,
                'user_id' => Auth::id(),
                'accion' => 'Marcada como completada',
                'fecha' => now(),
            ]);
        }
        
        return response()->json($bitacora, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bitacora $bitacora)
    {
        $bitacora->load(['categoria', 'usuario', 'actualizaciones', 'historialEstados']);
        return response()->json($bitacora);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bitacora $bitacora)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'string|max:255',
            'categoria_id' => 'exists:bitacora_categorias,id',
            'descripcion' => 'string',
            'tiempo_dedicado' => 'nullable|integer|min:1',
            'estado' => 'in:completado,en_proceso,pendiente',
            'fecha_limite' => 'nullable|date',
            'prioridad_fecha' => 'nullable|in:normal,importante,critica',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Guardar el estado anterior para comparar
        $estadoAnterior = $bitacora->estado;
        
        // Actualizar la bitácora
        $bitacora->update($request->all());
        
        // Si el estado cambió, registrar en el historial
        if ($request->has('estado') && $estadoAnterior !== $request->estado) {
            // Si se marcó como completada, registrar la fecha
            if ($request->estado === 'completado' && !$bitacora->fecha_completado) {
                $bitacora->fecha_completado = now();
                $bitacora->save();
                
                BitacoraHistorialEstado::create([
                    'bitacora_id' => $bitacora->id,
                    'user_id' => Auth::id(),
                    'accion' => 'Marcada como completada',
                    'fecha' => now(),
                ]);
            } else {
                BitacoraHistorialEstado::create([
                    'bitacora_id' => $bitacora->id,
                    'user_id' => Auth::id(),
                    'accion' => "Estado cambiado de \"$estadoAnterior\" a \"{$request->estado}\"",
                    'fecha' => now(),
                ]);
            }
        }
        
        return response()->json($bitacora);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bitacora $bitacora)
    {
        $bitacora->delete();
        return response()->json(null, 204);
    }
    
    /**
     * Marcar una bitácora como completada.
     */
    public function completar(Bitacora $bitacora)
    {
        if ($bitacora->estado === 'completado') {
            return response()->json(['message' => 'La bitácora ya está completada'], 422);
        }
        
        $bitacora->estado = 'completado';
        $bitacora->fecha_completado = now();
        $bitacora->save();
        
        BitacoraHistorialEstado::create([
            'bitacora_id' => $bitacora->id,
            'user_id' => Auth::id(),
            'accion' => 'Marcada como completada',
            'fecha' => now(),
        ]);
        
        return response()->json($bitacora);
    }
    
    /**
     * Reactivar una bitácora completada.
     */
    public function reactivar(Bitacora $bitacora)
    {
        if ($bitacora->estado !== 'completado') {
            return response()->json(['message' => 'Solo se pueden reactivar bitácoras completadas'], 422);
        }
        
        $fechaCompletadoAnterior = $bitacora->fecha_completado->format('d/m/Y, H:i');
        
        $bitacora->estado = 'en_proceso';
        $bitacora->fecha_reactivacion = now();
        $bitacora->fecha_completado = null;
        $bitacora->save();
        
        BitacoraHistorialEstado::create([
            'bitacora_id' => $bitacora->id,
            'user_id' => Auth::id(),
            'accion' => "Reactivada (completada anteriormente el $fechaCompletadoAnterior)",
            'fecha' => now(),
        ]);
        
        return response()->json($bitacora);
    }
    /**
 * Obtener bitácoras por cliente.
 */
public function bitacorasPorCliente(Request $request)
{
    Log::info('Solicitud de bitácoras para cliente:', ['client_id' => $request->client_id]);

    $validator = Validator::make($request->all(), [
        'client_id' => 'required|exists:clients,id',
    ]);

    if ($validator->fails()) {
        Log::error('Validación fallida:', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Obtener todos los expedientes del cliente
    $expedientes = Expediente::where('client_id', $request->client_id)->pluck('id');
    Log::info('Expedientes encontrados:', ['expedientes' => $expedientes]);
    
    if ($expedientes->isEmpty()) {
        Log::info('No se encontraron expedientes para el cliente');
        return response()->json([]);
    }
    
    // Obtener las bitácoras de todos esos expedientes
    $bitacoras = Bitacora::whereIn('expediente_id', $expedientes)
        ->with(['categoria', 'usuario', 'expediente'])
        ->orderBy('created_at', 'desc')
        ->limit($request->limite ?? 10)
        ->get();

    Log::info('Bitácoras encontradas:', ['count' => $bitacoras->count()]);
    
    return response()->json($bitacoras);
}
}