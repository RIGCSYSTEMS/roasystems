<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Expediente;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index()
    {
        $clients = Client::orderBy('id', 'desc')->paginate(30);
        return view('client.index', compact('clients'));
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

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre_de_cliente' => 'required|string',
                'familia' => 'nullable|array',
                'fecha_de_nacimiento' => 'required|date',
                'genero' => 'required|in:masculino,femenino,otro',
                'estado_civil' => 'required|in:soltero,casado,divorciado,viudo,otro',
                'pais' => 'required|string',
                'llegada_a_canada' => 'nullable|date',
                'punto_de_acceso' => 'required|in:aeropuerto,terrestre,maritimo,otro',
                'pasaporte' => 'nullable|string',
                'estatus' => 'required|string',
                'direccion' => 'nullable|string',
                'telefono' => 'nullable|string',
                'email' => 'nullable|email',
                'profesion' => 'nullable|string',
                'lenguaje' => 'nullable|string',
                'permiso_de_trabajo' => 'nullable|string',
                'iuc' => 'nullable|string',
                'observaciones' => 'nullable|string',
            ]);

            // Asegurarse de que las fechas estén en UTC
            if (isset($validatedData['fecha_de_nacimiento'])) {
                $validatedData['fecha_de_nacimiento'] = Carbon::parse($validatedData['fecha_de_nacimiento'])->utc();
            }
            if (isset($validatedData['llegada_a_canada'])) {
                $validatedData['llegada_a_canada'] = Carbon::parse($validatedData['llegada_a_canada'])->utc();
            }

            // Convertir el array de familia a JSON
            if (isset($validatedData['familia'])) {
                $validatedData['familia'] = json_encode($validatedData['familia']);
            }

            $client = Client::create($validatedData);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Cliente creado exitosamente',
                    'id' => $client->id,
                    'redirect' => route('client.edit', $client->id)
                ], 201);
            }

            return redirect()
                ->route('client.edit', $client->id)
                ->with('success', 'El cliente fue guardado correctamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Error al crear el cliente',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error al crear el cliente: ' . $e->getMessage()]);
        }
    }

    public function show($clientId)
    {
        $client = Client::with([
            'expedientes.tipoExpediente',
            'bitacoras' => function($query) {
                $query->latest()->take(5);
            }
        ])->findOrFail($clientId);

        $clientData = $client->toArray();

        // Manejo correcto de las fechas
        $clientData['fecha_de_nacimiento'] = $client->fecha_de_nacimiento ? date('Y-m-d', strtotime($client->fecha_de_nacimiento)) : null;
        $clientData['llegada_a_canada'] = $client->llegada_a_canada ? date('Y-m-d', strtotime($client->llegada_a_canada)) : null;

        // Asegúrate de que 'familia' siempre sea un array
        if (is_string($clientData['familia'])) {
            $clientData['familia'] = json_decode($clientData['familia'], true) ?? [];
        } elseif (!is_array($clientData['familia'])) {
            $clientData['familia'] = [];
        }
    
        $clientData['expedientes'] = $client->expedientes->map(function ($expediente) {
            return [
                'id' => $expediente->id,
                'numero_de_dossier' => $expediente->numero_de_dossier,
                'tipo_expediente' => $expediente->tipoExpediente->nombre,
                'estatus_del_expediente' => $expediente->estatus_del_expediente,
                'fecha_de_apertura' => $expediente->fecha_de_apertura,
                'prioridad' => $expediente->prioridad,
                'progreso' => $expediente->progreso,
            ];
        });
        
        $clientData['bitacoras'] = $client->bitacoras->map(function ($bitacora) {
            return [
                'id' => $bitacora->id,
                'categoria' => $bitacora->categoria,
                'descripcion' => $bitacora->descripcion,
                'fecha_y_hora_del_evento' => $bitacora->fecha_y_hora_del_evento,
                'tiempo_empleado' => $bitacora->tiempo_empleado,
            ];
        });

        return view('client.show', compact('clientData'));
    }

    public function edit($clientId)
    {
        $client = Client::findOrFail($clientId);
        return view('client.edit', compact('client'));
    }

    public function update(Request $request, $clientId)
    {
        try {
            $client = Client::findOrFail($clientId);

            $validatedData = $request->validate([
                'nombre_de_cliente' => 'required|string',
                'otros_nombres_de_cliente' => 'nullable|string',
                'fecha_de_nacimiento' => 'required|date',
                'telefono' => 'required|string',
                'email' => 'required|email',
                'direccion' => 'required|string',
                'profesion' => 'nullable|string',
                'pais' => 'required|string',
                'lenguaje' => 'required|string',
                'estatus' => 'required|string',
                'iuc' => 'nullable|string',
                'familia' => 'nullable|array', // Validación actualizada para familia
                'observaciones' => 'nullable|string',
                'llegada_a_canada' => 'required|date',
            ]);

            // Asegúrate de que las fechas se guarden en UTC
            $validatedData['fecha_de_nacimiento'] = Carbon::parse($validatedData['fecha_de_nacimiento'])->utc();
            $validatedData['llegada_a_canada'] = Carbon::parse($validatedData['llegada_a_canada'])->utc();

            // Asegúrate de que 'familia' siempre sea un array
            if (is_string($validatedData['familia'])) {
                $validatedData['familia'] = json_decode($validatedData['familia'], true) ?? [];
            } elseif (!is_array($validatedData['familia'])) {
                $validatedData['familia'] = [];
            }

            $client->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Cliente actualizado correctamente',
                'client' => $client->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);
        $client->delete();
        return redirect()->route('client.index')->with('success', 'Cliente eliminado correctamente');
    }

    public function addFamiliar(Request $request, Client $client)
    {
        $familiar = $request->validate([
            'nombre' => 'required|string',
            'parentesco' => 'required|string',
        ]);
    
        $familia = is_array($client->familia) ? $client->familia : [];
        $familia[] = $familiar;
        
        $client->familia = $familia;
        $client->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Familiar agregado con éxito'
        ]);
    }
    
    public function removeFamiliar(Client $client, $index)
    {
        $familia = is_array($client->familia) ? $client->familia : [];
        
        if (isset($familia[$index])) {
            array_splice($familia, $index, 1);
            $client->familia = $familia;
            $client->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Familiar eliminado con éxito'
            ]);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'Familiar no encontrado'
        ], 404);
    }
}