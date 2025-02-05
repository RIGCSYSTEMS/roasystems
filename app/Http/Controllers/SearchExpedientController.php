<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Expediente;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use function Termwind\render;

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

        $query = Expediente::select(
            'expedientes.id', 
            'clients.nombre_de_cliente as cliente_asociado', 
            'tipos_expedientes.nombre as tipo_expediente',
            'expedientes.estatus_del_expediente',
            'expedientes.prioridad',
            'expedientes.numero_de_dossier',
            'expedientes.despacho');
            $query->join('clients', 'expedientes.client_id', '=', 'clients.id');
            $query->join('tipos_expedientes', 'expedientes.tipo_expediente_id', '=', 'tipos_expedientes.id');

        if (!$show_all && !$search) {
            $query->whereRaw('1 = 0');
        }

        return datatables()->of($query)
            ->filter(function ($query) use ($search) {
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('clients.nombre_de_cliente', 'like', "%{$search}%")
                          ->orWhere('tipos_expedientes.nombre', 'like', "%{$search}%")
                          ->orWhere('expedientes.estatus_del_expediente', 'like', "%{$search}%")
                          ->orWhere('expedientes.prioridad', 'like', "%{$search}%")
                          ->orWhere('expedientes.numero_de_dossier', 'like', "%{$search}%")
                          ->orWhere('expedientes.despacho', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('acciones', function(Expediente $expediente) {
                
                return view('expedientes.actions', ['expediente' => $expediente])->render();
                
               
            })
            ->rawColumns(['acciones'])
            ->toJson();
    }

    public function create()
    {
        
        return view('expedientes.create');
    }

    public function destroy($expedienteId)
    {
        try {
            $client = Expediente::findOrFail($expedienteId);
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
                ], 500); // Cambiamos a 500 para evitar el error de Internal Server Error
            }
    
            return redirect()->back()
                ->with('error', 'Error al eliminar el cliente');
        }
    }
}