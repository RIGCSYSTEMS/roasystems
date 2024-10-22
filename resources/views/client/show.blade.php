@extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')




    <a href="/client" id="volverListaClientes" class="btn btn-secondary">Volver a la lista de clientes</a>

    <h1>CLIENTE: {{$client->nombre_de_cliente}}</h1>
    <p>
        <b>Otros NOMBRES DE CLIENTE:</b> {{$client->otros_nombres_de_cliente}}
    </p>
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
    {{-- <p>
        <b>Despacho:</b> {{$client->despacho}}
    </p> --}}
    {{-- <p>
        <b>Tipo de expediente:</b> {{$client->tipo_de_expediente}}
    </p> --}}
    <p>
        <b>Lenguaje:</b> {{$client->lenguaje}}
    </p>
    {{-- <p>
        <b>Honorarios:</b> {{$client->honorarios}}
    </p>
    <p>
        <b>Fecha de apertura:</b> {{$client->fecha_de_apertura}}
    </p> --}}
    {{-- <p>
        <b>Estatus:</b> {{$client->estatus}}
    </p> --}}
    <p>
        <b>Observaciones:</b> {{$client->observaciones}}
    </p>
    {{-- <p>
        <b>Numero de expediente:</b> {{$client->numero_de_expediente}}
    </p> --}}
    <p>
        <b>Permiso de trabajo:</b> {{$client->permiso_de_trabajo}}
    </p>
    <p>
        <b>IUC:</b> {{$client->iuc}}
    </p>
    {{-- <p>
        <b>Ubicacion del despacho:</b> {{$client->ubicacion_del_despacho}}
    </p> --}}
    {{-- <p>
        <b>Fecha de nacimiento:</b> {{$client->fecha_de_nacimiento}}
    </p>
    <p>
        <b>Fecha de cierre:</b> {{$client->fecha_de_cierre}}
    </p>
    <p>
        <b>Cierre del numero de caja:</b> {{$client->cierre_del_numero_de_caja}}
    </p> --}}

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