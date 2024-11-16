{{-- @extends('layouts.app')

@section('title', 'ROASYSTEMS - Documentos del Cliente')

@section('content')
<div class="container">
    <h1>Documentos de {{ $client->nombre_de_cliente }}</h1>
    <a href="{{ route('client.show', $client->id) }}" class="btn btn-secondary mb-3">Volver al perfil del cliente</a>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Documentos</h5>
            
            @if($documentos->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo de Documento</th>
                                <th>Formato</th>
                                <th>Acciones</th>
                                <th>Fecha de Subida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td>{{ $documento->nombre_de_documento }}</td>
                                    <td>
                                        <span class="badge {{ $documento->tipo_de_documento === 'PDF' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $documento->tipo_de_documento }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($documento->imagen_url)
                                            <a href="{{ asset($documento->imagen_url) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-primary">
                                                Ver archivo
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Este cliente no tiene documentos asociados.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection --}}