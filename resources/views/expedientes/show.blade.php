@extends('layouts.app')

@section('title', 'ROASYSTEMS - Detalles del Expediente')

@section('content')
    <div class="container">
        <h1>Detalles del Expediente</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Expediente #{{ $expediente->id }}</h5>
                <p><strong>Cliente:</strong> {{ $expediente->client->nombre_de_cliente }}</p>
                <p><strong>Fecha de Apertura:</strong> {{ $expediente->fecha_de_apertura }}</p>
                <p><strong>Estatus del Expediente:</strong> {{ $expediente->estatus_del_expediente }}</p>
                <p><strong>Fecha de Cierre:</strong> {{ $expediente->fecha_de_cierre ?? 'No cerrado' }}</p>
                <p><strong>Número de Dossier:</strong> {{ $expediente->numero_de_dossier }}</p>
                <p><strong>Despacho:</strong> {{ $expediente->despacho }}</p>
                <p><strong>Abogado:</strong> {{ $expediente->abogado }}</p>
                <p><strong>Honorarios:</strong> ${{ number_format($expediente->honorarios, 2) }}</p>
                <p><strong>Tipo de Expediente:</strong> {{ ucfirst($expediente->tipo) }}</p>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('expedientes.edit', $expediente->id) }}" class="btn btn-primary">Editar</a>
            <form action="{{ route('expedientes.destroy', $expediente->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este expediente?')">Eliminar</button>
            </form>
            <a href="{{ route('expedientes.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
@endsection