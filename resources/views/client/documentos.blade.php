@extends('layouts.app')

@section('title', 'ROASYSTEMS - Documentos del Cliente')

@section('content')
    <div class="container">
        <h1>Documentos de {{ $client->nombre_de_cliente }}</h1>
        <a href="{{ route('client.show', $client->id) }}" class="btn btn-secondary mb-3">Volver al perfil del cliente</a>
        
        @if($documentos->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Documentos</h5>
                    <ul class="list-group list-group-flush">
                        @if($documentos->identificacion)
                            <li class="list-group-item">
                                <strong>Identificación:</strong> 
                                <a href="{{ asset('storage/' . $documentos->identificacion) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->pasaporte)
                            <li class="list-group-item">
                                <strong>Pasaporte:</strong> 
                                <a href="{{ asset('storage/' . $documentos->pasaporte) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->permiso_de_trabajo)
                            <li class="list-group-item">
                                <strong>Permiso de Trabajo:</strong> 
                                <a href="{{ asset('storage/' . $documentos->permiso_de_trabajo) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->hoja_marron)
                            <li class="list-group-item">
                                <strong>Hoja Marrón:</strong> 
                                <a href="{{ asset('storage/' . $documentos->hoja_marron) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->pruebas)
                            <li class="list-group-item">
                                <strong>Pruebas:</strong> 
                                <a href="{{ asset('storage/' . $documentos->pruebas) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->residencia_permanente)
                            <li class="list-group-item">
                                <strong>Residencia Permanente:</strong> 
                                <a href="{{ asset('storage/' . $documentos->residencia_permanente) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->caq)
                            <li class="list-group-item">
                                <strong>CAQ:</strong> 
                                <a href="{{ asset('storage/' . $documentos->caq) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->extras)
                            <li class="list-group-item">
                                <strong>Extras:</strong> 
                                <a href="{{ asset('storage/' . $documentos->extras) }}" target="_blank">Ver archivo</a>
                            </li>
                        @endif
                        @if($documentos->historia)
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Historia</h5>
                                <p>{{ $documentos->historia }}</p>
                            </div>
                        </div>
                    @endif
                    </ul>
                </div>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                Este cliente no tiene documentos asociados.
            </div>
        @endif
    </div>
@endsection