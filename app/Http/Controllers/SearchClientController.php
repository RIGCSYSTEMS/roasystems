<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Expediente;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class SearchClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
public function index()
    {
        $clients = Client::orderBy('id', 'desc')->paginate(30);
        return view('search.searchClient', compact('clients'));
    }

    public function getDataClientes(Request $request)
    {
        $show_all = $request->input('show_all', 0);
        $search = $request->input('search.value');

        $query = Client::select('id', 'nombre_de_cliente', 'telefono', 'email', 'direccion', 'pais', 'estatus');

        if (!$show_all && !$search) {
            $query->whereRaw('1 = 0');
        }

        return datatables()->of($query)
            ->filter(function ($query) use ($search) {
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('nombre_de_cliente', 'like', "%{$search}%")
                          ->orWhere('telefono', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('direccion', 'like', "%{$search}%")
                          ->orWhere('pais', 'like', "%{$search}%")
                          ->orWhere('estatus', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('acciones', function(Client $cliente) {
                return view('client.actions', compact('cliente'))->render();
            })
            ->rawColumns(['acciones'])
            ->toJson();
    }

    public function create()
    {
        
        return view('client.create');
    }

    public function destroy($clientId)
    {
        try {
            $client = Client::findOrFail($clientId);
            $client->delete();
    
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente eliminado correctamente',
                    'redirect' => route('searchClient.index')
                ]);
            }
    
            return redirect()->route('searchClient.index')
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