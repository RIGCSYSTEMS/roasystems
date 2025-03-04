<?php

namespace App\Http\Controllers;

use App\Models\Honorario;
use App\Models\Abono;
use App\Models\Expediente;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Extra;
use Illuminate\Support\Facades\DB;
class HonorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('honorarios.index');
    }

    public function show($expediente_id)
    {
        $honorario = Honorario::with(['abonos', 'extras'])
            ->where('expediente_id', $expediente_id)
            ->firstOrFail();

        return view('honorarios.show', compact('honorario'));
    }

    // Método para obtener honorarios por expediente (para API)
    public function getHonorariosByExpediente($expediente_id)
    {
        $honorarios = Honorario::where('expediente_id', $expediente_id)->get();
        return response()->json($honorarios);
    }
// Método para crear un nuevo honorario
public function store(Request $request)
{
    $request->validate([
        'expediente_id' => 'required|exists:expedientes,id',
        'monto_total_expediente' => 'required|numeric|min:0',
        'monto_adicional' => 'nullable|numeric|min:0',
        'monto_total_a_pagar' => 'required|numeric|min:0',
        'total_abonos' => 'nullable|numeric|min:0',
        'saldo_pendiente' => 'required|numeric|min:0',
        'estado' => 'nullable|in:pendiente,pagado,cancelado',
        'descripcion' => 'nullable|string',
        'usuario_id' => 'nullable|exists:users,id'
    ]);

    $honorario = Honorario::create($request->all());
    return response()->json($honorario);
}

// Método para actualizar un honorario
public function update(Request $request, $honorario_id)
{
    $honorario = Honorario::findOrFail($honorario_id);
    
    $request->validate([
        'monto_total_expediente' => 'nullable|numeric|min:0',
        'monto_adicional' => 'nullable|numeric|min:0',
        'monto_total_a_pagar' => 'nullable|numeric|min:0',
        'total_abonos' => 'nullable|numeric|min:0',
        'saldo_pendiente' => 'nullable|numeric|min:0',
        'estado' => 'nullable|in:pendiente,pagado,cancelado',
        'descripcion' => 'nullable|string'
    ]);

    $honorario->update($request->all());
    return response()->json($honorario);
}

// Método para obtener abonos de un honorario
public function getAbonos($honorario_id)
{
    $abonos = Abono::where('honorario_id', $honorario_id)->get();
    return response()->json($abonos);
}

// Método para crear un nuevo abono
public function storeAbono(Request $request)
{
    $request->validate([
        'honorario_id' => 'required|exists:honorarios,id',
        'monto' => 'required|numeric|min:0',
        'fecha' => 'required|date',
        'gst_rate' => 'nullable|numeric|min:0',
        'qst_rate' => 'nullable|numeric|min:0',
        'usuario_id' => 'nullable|exists:users,id'
    ]);

    $abono = Abono::create([
        'honorario_id' => $request->honorario_id,
        'monto' => $request->monto,
        'fecha' => $request->fecha,
        'gst_rate' => $request->gst_rate ?? 5,
        'qst_rate' => $request->qst_rate ?? 9.975,
        'impuestos' => ($request->monto * ($request->gst_rate ?? 5) / 100) + 
                      ($request->monto * ($request->qst_rate ?? 9.975) / 100),
        'usuario_id' => $request->usuario_id ?? Auth::id()
    ]);

    // Actualizar el honorario
    $honorario = Honorario::findOrFail($request->honorario_id);
    $honorario->actualizarTotales();

    return response()->json($abono);
}

// Método para crear un nuevo extra
public function storeExtra(Request $request)
{
    $request->validate([
        'honorario_id' => 'required|exists:honorarios,id',
        'concepto' => 'required|string',
        'monto' => 'required|numeric|min:0',
        'fecha' => 'required|date',
        'gst_rate' => 'nullable|numeric|min:0',
        'qst_rate' => 'nullable|numeric|min:0',
        'usuario_id' => 'nullable|exists:users,id'
    ]);

    $extra = Extra::create([
        'honorario_id' => $request->honorario_id,
        'concepto' => $request->concepto,
        'monto' => $request->monto,
        'fecha' => $request->fecha,
        'gst_rate' => $request->gst_rate ?? 5,
        'qst_rate' => $request->qst_rate ?? 9.975,
        'impuestos' => ($request->monto * ($request->gst_rate ?? 5) / 100) + 
                      ($request->monto * ($request->qst_rate ?? 9.975) / 100),
        'usuario_id' => $request->usuario_id ?? Auth::id()
    ]);

    // Actualizar el honorario
    $honorario = Honorario::findOrFail($request->honorario_id);
    $honorario->monto_adicional += $request->monto;
    $honorario->monto_total_a_pagar = $honorario->monto_total_expediente + $honorario->monto_adicional;
    $honorario->saldo_pendiente = $honorario->monto_total_a_pagar - $honorario->total_abonos;
    $honorario->save();

    return response()->json($extra);
}

// Método para actualizar un abono
public function updateAbono(Request $request, $abono_id)
{
    $abono = Abono::findOrFail($abono_id);
    
    $request->validate([
        'monto' => 'nullable|numeric|min:0',
        'fecha' => 'nullable|date',
        'gst_rate' => 'nullable|numeric|min:0',
        'qst_rate' => 'nullable|numeric|min:0'
    ]);

    if ($request->has('monto') || $request->has('gst_rate') || $request->has('qst_rate')) {
        $monto = $request->monto ?? $abono->monto;
        $gst_rate = $request->gst_rate ?? $abono->gst_rate;
        $qst_rate = $request->qst_rate ?? $abono->qst_rate;
        
        $impuestos = ($monto * $gst_rate / 100) + ($monto * $qst_rate / 100);
        
        $abono->update([
            'monto' => $monto,
            'gst_rate' => $gst_rate,
            'qst_rate' => $qst_rate,
            'impuestos' => $impuestos,
            'fecha' => $request->fecha ?? $abono->fecha
        ]);
    } else {
        $abono->update($request->only(['fecha']));
    }

    // Actualizar el honorario
    $honorario = $abono->honorario;
    $honorario->actualizarTotales();

    return response()->json($abono);
}

// Método para obtener extras de un honorario
public function getExtras($honorario_id)
{
    $extras = Extra::where('honorario_id', $honorario_id)->get();
    return response()->json($extras);
}

    public function getHonorarioData(Honorario $honorario)
    {
        $honorario->load(['abonos', 'extras']);
        
        return response()->json([
            'honorario' => $honorario,
            'abonos' => $honorario->abonos,
            'extras' => $honorario->extras,
        ]);
    }
    // En HonorarioController.php
public function destroyAbono(Abono $abono)
{
    $honorario = $abono->honorario;
    $abono->delete();
    
    // Actualizar totales del honorario
    $honorario->total_abonos = $honorario->abonos()->sum('monto');
    $honorario->saldo_pendiente = $honorario->monto_total_a_pagar - $honorario->total_abonos;
    $honorario->estado = $honorario->saldo_pendiente <= 0 ? 'pagado' : 'pendiente';
    $honorario->save();
    
    return response()->json(['message' => 'Abono eliminado correctamente']);
}

public function destroyExtra(Extra $extra)
{
    $honorario = $extra->honorario;
    $extra->delete();
    
    // Actualizar totales del honorario
    $honorario->monto_adicional = $honorario->extras()->sum('monto');
    $honorario->monto_total_a_pagar = $honorario->monto_total_expediente + $honorario->monto_adicional;
    $honorario->saldo_pendiente = $honorario->monto_total_a_pagar - $honorario->total_abonos;
    $honorario->save();
    
    return response()->json(['message' => 'Extra eliminado correctamente']);
}
}

