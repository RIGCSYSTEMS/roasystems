@extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')




    <a href="/client" id="volverListaClientes" class="btn btn-secondary">Volver a la lista de clientes</a>

    <h1>CLIENTE: {{$client->nombre_de_cliente}}</h1>
    <p>
        <b>Otros NOMBRES DE CLIENTE:</b> {{$client->otros_nombres_de_cliente}}
    </p>
    <P>
        <B>Estatus del cliente:</B> {{$client->estatus}}
    </P>
    <p>
        <b>Direccion:</b> {{$client->direccion}}
    </p>
    <p>
        <b>Telefono:</b> {{$client->telefono}}
    </p>
    <p>
        <b>Correo:</b> {{$client->email}}
    </p>
    <p>
        <b>Pais de origen:</b> {{$client->pais}}
        
    </p>
    <p>
        <b>profesion:</b> {{$client->profesion}}
    </p>
    <p>
        <b>Lenguaje:</b> {{$client->lenguaje}}
    </p>
    <p>
        <b>Permiso de trabajo:</b> {{$client->permiso_de_trabajo}}
    </p>
    <p>
        <b>Observaciones:</b> {{$client->observaciones}}
    </p>
    <p>
        <b>IUC:</b> {{$client->iuc}}
    </p>

    <div class="row mt-4">
        <div class="col-md-6">
            <a href="{{ route('expedientes.create', ['client_id' => $client->id]) }}" class="btn btn-primary w-100">Crear Expediente</a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('documentos.create', ['client_id' => $client->id]) }}" class="btn btn-info w-100">Subir Documentos</a>
        </div>
        <div class="col-md-6 mt-2">
            <a href="{{ route('documentos.show', $client->id) }}" class="btn btn-success w-100">Ver Documentos</a>
        </div>
    </div>

    <a href="/client/{{$client->id}}/edit">Editar</a>

    <form action="/client/{{$client->id}}" method="post">
        @csrf
        @method('delete')
        <button type="submit">Eliminar Cliente</button>

@endsection

























{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DATASYSTEMS/CLIENTES</title>
</head>
<body>
    <H1>DATASYSTEMS ------- CLIENTE {{$id}} </H1>
</body>
</html> --}}