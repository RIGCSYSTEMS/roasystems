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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expediente_id' => 'required|exists:expedientes,id',
            'monto_inicial' => 'required|numeric|min:0',
            'fecha_apertura' => 'required|date',
        ]);

        $honorario = Honorario::create($validated);

        return response()->json($honorario);
    }

    public function storeAbono(Request $request, Honorario $honorario)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'gst_rate' => 'required|numeric|min:0',
            'qst_rate' => 'required|numeric|min:0',
        ]);

        $impuestos = ($validated['monto'] * $validated['gst_rate'] / 100) +
                     ($validated['monto'] * $validated['qst_rate'] / 100);

        $abono = $honorario->abonos()->create([
            ...$validated,
            'impuestos' => $impuestos,
        ]);

        return response()->json($abono);
    }

    public function storeExtra(Request $request, Honorario $honorario)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'gst_rate' => 'required|numeric|min:0',
            'qst_rate' => 'required|numeric|min:0',
        ]);

        $impuestos = ($validated['monto'] * $validated['gst_rate'] / 100) +
                     ($validated['monto'] * $validated['qst_rate'] / 100);

        $extra = $honorario->extras()->create([
            ...$validated,
            'impuestos' => $impuestos,
        ]);

        return response()->json($extra);
    }

    public function updateAbono(Request $request, Honorario $honorario, Abono $abono)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'gst_rate' => 'required|numeric|min:0',
            'qst_rate' => 'required|numeric|min:0',
        ]);

        $impuestos = ($validated['monto'] * $validated['gst_rate'] / 100) +
                     ($validated['monto'] * $validated['qst_rate'] / 100);

        $abono->update([
            ...$validated,
            'impuestos' => $impuestos,
        ]);

        return response()->json($abono);
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
}

