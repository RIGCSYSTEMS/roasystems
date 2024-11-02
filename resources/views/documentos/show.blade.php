@extends('layouts.app')

@section('title', 'ROASYSTEMS - Documentos del Cliente')

@section('content')
    <div class="container">
        <h1>Documentos de {{ $client->nombre_de_cliente }}</h1>
        <a href="{{ route('client.show', $client->id) }}" class="btn btn-secondary mb-3">Volver al perfil del cliente</a>
        
        @if($documents->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Documentos</h5>
                    <ul class="list-group list-group-flush">
                        @if($documents->identificacion)
                            <li class="list-group-item">
                                <strong>Identificación:</strong> 
                                <a href="{{ asset('storage/' . $documents->identificacion) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->pasaporte)
                            <li class="list-group-item">
                                <strong>Pasaporte:</strong> 
                                <a href="{{ asset('storage/' . $documents->pasaporte) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->permiso_de_trabajo)
                            <li class="list-group-item">
                                <strong>Permiso de Trabajo:</strong> 
                                <a href="{{ asset('storage/' . $documents->permiso_de_trabajo) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->hoja_marron)
                            <li class="list-group-item">
                                <strong>Hoja Marrón:</strong> 
                                <a href="{{ asset('storage/' . $documents->hoja_marron) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->pruebas)
                            <li class="list-group-item">
                                <strong>Pruebas:</strong> 
                                <a href="{{ asset('storage/' . $documents->pruebas) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->residencia_permanente)
                            <li class="list-group-item">
                                <strong>Residencia Permanente:</strong> 
                                <a href="{{ asset('storage/' . $documents->residencia_permanente) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->caq)
                            <li class="list-group-item">
                                <strong>CAQ:</strong> 
                                <a href="{{ asset('storage/' . $documents->caq) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documents->extras)
                            <li class="list-group-item">
                                <strong>Extras:</strong> 
                                <a href="{{ asset('storage/' . $documents->extras) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            @if($documents->historia)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Historia</h5>
                        <p>{{ $documents->historia }}</p>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-info" role="alert">
                Este cliente no tiene documentos asociados.
            </div>
        @endif
    </div>
@endsection