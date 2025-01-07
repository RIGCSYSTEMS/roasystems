@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>
                    @if(isset($expediente))
                        Bitácora del Expediente {{$expediente->numero_de_dossier}}
                    @else
                        Bitácora del Cliente {{$client->nombre_de_cliente}}
                    @endif
                </h1>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @forelse($bitacoras as $bitacora)
                        <div class="mb-4 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">{{$bitacora->categoria}}</h5>
                                    <p class="mb-1">{{$bitacora->descripcion}}</p>
                                    @if(isset($client))
                                        <small class="text-muted">
                                            Expediente: {{$bitacora->expediente->numero_de_dossier}}
                                        </small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">
                                        {{$bitacora->fecha_y_hora_del_evento->format('d/m/Y H:i')}}
                                    </small>
                                    <small class="text-muted d-block">
                                        Por: {{$bitacora->usuario->name}}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted my-4">No hay registros de bitácora disponibles.</p>
                    @endforelse

                    {{ $bitacoras->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection