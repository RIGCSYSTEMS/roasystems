<?php

namespace App\Http\Controllers;

use App\Models\Honorario;
use App\Models\Abono;
use App\Models\Expediente;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class HonorariosController extends Controller
{
    public function store(Request $request, Expediente $expediente)
    {
        $validatedData = $request->validate([
            'monto_total_expediente' => 'required|numeric|min:0',
            'monto_adicional' => 'nullable|numeric|min:0',
        ]);

        $honorario = $expediente->honorario()->create($validatedData);

        Bitacora::create([
            'usuario_id' => Auth::id(),
            'expediente_id' => $expediente->id,
            'categoria' => 'Honorarios',
            'descripcion' => "Se establecieron los honorarios del expediente por $" . $honorario->monto_total_a_pagar,
            'fecha_y_hora_del_evento' => now(),
        ]);

        return redirect()->route('expedientes.show', $expediente)->with('success', 'Honorarios registrados correctamente');
    }

    public function update(Request $request, Honorario $honorario)
    {
        $validatedData = $request->validate([
            'monto_adicional' => 'required|numeric|min:0',
        ]);

        $montoAnterior = $honorario->monto_adicional;
        $honorario->monto_adicional = $validatedData['monto_adicional'];
        $honorario->save();

        Bitacora::create([
            'usuario_id' => Auth::id(),
            'expediente_id' => $honorario->expediente_id,
            'categoria' => 'Honorarios',
            'descripcion' => "Se actualizó el monto adicional de $" . $montoAnterior . " a $" . $honorario->monto_adicional,
            'fecha_y_hora_del_evento' => now(),
        ]);

        return redirect()->back()->with('success', 'Monto adicional actualizado correctamente');
    }

    public function registrarAbono(Request $request, Honorario $honorario)
    {
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'factura' => 'nullable|string',
            'monto' => 'required|numeric|min:0|max:' . $honorario->saldo_pendiente,
            'metodo_pago' => 'required|string',
        ]);

        $validatedData['usuario_id'] = Auth::id();
        $abono = $honorario->abonos()->create($validatedData);

        Bitacora::create([
            'usuario_id' => Auth::id(),
            'expediente_id' => $honorario->expediente_id,
            'categoria' => 'Honorarios',
            'descripcion' => "Se registró un abono de $" . $abono->monto,
            'fecha_y_hora_del_evento' => now(),
        ]);

        return redirect()->back()->with('success', 'Abono registrado correctamente');
    }

    public function show(Client $client)
    {
        $expedientes = $client->expedientes()->with(['honorarios', 'honorarios.abonos'])->get();
        
        $totalHonorarios = $expedientes->sum(function($expediente) {
            return $expediente->honorarios ? $expediente->honorarios->monto_total_expediente : 0;
        });
        
        $totalPagado = $expedientes->sum(function($expediente) {
            return $expediente->honorarios ? $expediente->honorarios->total_abonos : 0;
        });
        
        $saldoPendiente = $totalHonorarios - $totalPagado;
    
        return view('honorarios.show', compact('client', 'expedientes', 'totalHonorarios', 'totalPagado', 'saldoPendiente'));
    }



}

