@extends('layouts.app')

@section('title', 'ROASYSTEMS - Detalles del Documento')

@section('content')
    <div class="container">
        <h1>Detalles del Documento</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Documento #{{ $documento->id }}</h5>
                <p><strong>Cliente:</strong> {{ $documento->client->nombre_de_cliente }}</p>
                <p><strong>Identificación:</strong> 
                    @if($documento->identificacion)
                        <a href="{{ asset('storage/' . $documento->identificacion) }}" target="_blank">Ver archivo</a>
                    @else
                        No disponible
                    @endif
                </p>
                <p><strong>Pasaporte:</strong> 
                    @if($documento->pasaporte)
                        <a href="{{ asset('storage/' . $documento->pasaporte) }}" target="_blank">Ver archivo</a>
                    @else
                        No disponible
                    @endif
                </p>
                <p><strong>Permiso de Trabajo:</strong> 
                    @if($documento->permiso_de_trabajo)
                        <a href="{{ asset('storage/' . $documento->permiso_de_trabajo) }}" target="_blank">Ver archivo</a>
                    @else
                        No disponible
                    @endif
                </p>
                <p><strong>Historia:</strong> {{ $documento->historia ?? 'No disponible' }}</p>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('documentos.edit', $documento->id) }}" class="btn btn-primary">Editar</a>
            <form action="{{ route('documentos.destroy', $documento->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este documento?')">Eliminar</button>
            </form>
            <a href="{{ route('documentos.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
@endsection