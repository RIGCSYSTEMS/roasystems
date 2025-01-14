<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Expediente;

class ClientController extends Controller
{
    protected $attributes = [];
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
        $tipos = ['asilo', 'appel', 'permanente', 'erar', 'apadrinamiento', 'humanitaria', 'temporal'];
        return view('client.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $client = new Client();
        $client->nombre_de_cliente = $request->nombre_de_cliente;
        $client->otros_nombres_de_cliente = $request->otros_nombres_de_cliente;
        $client->direccion = $request->direccion;
        $client->telefono = $request->telefono;
        $client->email = $request->email;
        $client->profesion = $request->profesion;
        $client->pais = $request->pais;
        $client->lenguaje = $request->lenguaje;
        $client->estatus = $request->estatus;
        $client->observaciones = $request->observaciones;
        $client->iuc = $request->iuc;
        $client->save();

        return redirect("client/".$client->id."/edit")->with('guardadoCorrectamente', 'El cliente fue guardado correctamente');
    }

    public function show($clientId)
    {
        $client = Client::with([
            'expedientes.tipoExpediente',
            'bitacoras' => function($query) {
                $query->latest()->take(5);
            }
        ])->findOrFail($clientId);
    
        $clientData = [
            'id' => $client->id,
            'nombre_de_cliente' => $client->nombre_de_cliente,
            'otros_nombres_de_cliente' => $client->otros_nombres_de_cliente,
            'direccion' => $client->direccion,
            'telefono' => $client->telefono,
            'email' => $client->email,
            'profesion' => $client->profesion,
            'pais' => $client->pais,
            'lenguaje' => $client->lenguaje,
            'estatus' => $client->estatus,
            'observaciones' => $client->observaciones,
            'iuc' => $client->iuc,
            'fecha_de_nacimiento' => $client->fecha_de_nacimiento,
            'genero' => $client->genero,
            'estado_civil' => $client->estado_civil,
            'llegada_a_canada' => $client->llegada_a_canada,
            'punto_de_acceso' => $client->punto_de_acceso,
            'pasaporte' => $client->pasaporte,
            'permiso_de_trabajo' => $client->permiso_de_trabajo,
            'familia' => $client->familia,
            'expedientes' => $client->expedientes->map(function ($expediente) {
                return [
                    'id' => $expediente->id,
                    'numero_de_dossier' => $expediente->numero_de_dossier,
                    'tipo_expediente' => $expediente->tipoExpediente->nombre,
                    'estatus_del_expediente' => $expediente->estatus_del_expediente,
                    'fecha_de_apertura' => $expediente->fecha_de_apertura,
                    'prioridad' => $expediente->prioridad,
                    'progreso' => $expediente->progreso,
                ];
            }),
            'bitacoras' => $client->bitacoras->map(function ($bitacora) {
                return [
                    'id' => $bitacora->id,
                    'categoria' => $bitacora->categoria,
                    'descripcion' => $bitacora->descripcion,
                    'fecha_y_hora_del_evento' => $bitacora->fecha_y_hora_del_evento,
                    'tiempo_empleado' => $bitacora->tiempo_empleado,
                ];
            }),
        ];
    
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
                'familia' => 'nullable|string',
                'fecha_de_nacimiento' => 'required|date',
                'genero' => 'required|string',
                'estado_civil' => 'required|string',
                'llegada_a_canada' => 'required|date',
                'punto_de_acceso' => 'required|string',
                'pasaporte' => 'nullable|string',
                'estatus' => 'required|string',
                'permiso_de_trabajo' => 'nullable|string',
                'direccion' => 'required|string',
                'telefono' => 'required|string',
                'email' => 'required|email',
                'profesion' => 'nullable|string',
                'pais' => 'required|string',
                'lenguaje' => 'required|string',
                'estatus' => 'required|string',
                'observaciones' => 'nullable|string',
                'iuc' => 'nullable|string',


            ]);
    
            $client->update($validatedData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Cliente actualizado correctamente',
                'client' => $client->fresh()
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);
        $client->delete();
        return redirect('/client');
    }

    public function getFamiliaAttribute($value)
{
    return $value ? json_decode($value, true) : [];
}

public function setFamiliaAttribute($value)
{
    $this->attributes['familia'] = json_encode($value);
}

public function addFamiliar(Request $request, Client $client)
{
    $familiar = $request->validate([
        'nombre' => 'required|string',
        'apellido' => 'required|string',
        'parentesco' => 'required|string',
    ]);

    $familia = $client->familia;
    $familia[] = $familiar;
    $client->familia = $familia;
    $client->save();

    return redirect()->back()->with('success', 'Familiar agregado con éxito');
}

}