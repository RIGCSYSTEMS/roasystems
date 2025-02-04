<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Expediente;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SearchExpedientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
public function index()
    {
        $expedientes = Expediente::orderBy('id', 'desc')->paginate(30);
        return view('search.searchExpedient', compact('expedientes'));
    }

    public function getDataExpedient(Request $request)
    {
        $show_all = $request->input('show_all', 0);
        $search = $request->input('search.value');

        $query = Expediente::select('id', 'client_id', 'tipo_expediente_id', 'estatus_del_expediente', 'prioridad', 'numero_de_dossier', 'despacho');

        if (!$show_all && !$search) {
            $query->whereRaw('1 = 0');
        }

        return datatables()->of($query)
            ->filter(function ($query) use ($search) {
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('client_id', 'like', "%{$search}%")
                          ->orWhere('tipo_expediente_id', 'like', "%{$search}%")
                          ->orWhere('estatus_del_expediente', 'like', "%{$search}%")
                          ->orWhere('prioridad', 'like', "%{$search}%")
                          ->orWhere('numero_de_dossier', 'like', "%{$search}%")
                          ->orWhere('despacho', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('acciones', function(Expediente $expediente) {
                return view('expedientes.actions', compact('expedientes'))->render();
            })
            ->rawColumns(['acciones'])
            ->toJson();
    }

    public function create()
    {
        
        return view('expedientes.create');
    }

    public function destroy($clientId)
    {
        try {
            $client = Expediente::findOrFail($clientId);
            $client->delete();
    
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expediente eliminado correctamente',
                    'redirect' => route('searchExpedient.index')
                ]);
            }
    
            return redirect()->route('searchExpedient.index')
                ->with('success', 'Cliente eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el cliente'
                ], 200); // Cambiamos a 200 para evitar el error de Internal Server Error
            }
    
            return redirect()->back()
                ->with('error', 'Error al eliminar el cliente');
        }
    }
}