@extends('layouts.app')

@section('title', 'ROASYSTEMS - Perfil del Cliente')

@section('content')
<div class="container-fluid mt-4">
    <!-- Encabezado con botones de acción -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Consultar perfil</h2>
            <div>
                <a href="/client" class="btn btn-success">
                    Listado de clientes
                </a>
            </div>
        </div>
    </div>

    <!-- Información Principal del Cliente -->
    <div class="row">
        <div class="col-md-8">
            <!-- Tarjeta Principal -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h3 class="mb-1">{{$client->nombre_de_cliente}}</h3>
                            <p class="text-muted mb-0">{{$client->pais}}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-eye"></i> Ver todo
                            </button>
                            <a href="/client/{{$client->id}}/edit" class="btn btn-dark btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-clock"></i> Seguimiento
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-calendar-plus"></i> Agregar evento
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-folder"></i> Expedientes
                            </button>
                        </div>
                    </div>

                    <!-- Información de contacto -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Información de contacto</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        {{$client->email}}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        {{$client->telefono}}
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        {{$client->direccion}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado y Fechas -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">Activo desde</h6>
                                    <p class="mb-0">{{$client->created_at->format('F d, Y')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">Llegada a Canadá</h6>
                                    <p class="mb-0">{{$client->llegada_a_canada ? $client->llegada_a_canada->format('F d, Y') : 'No registrado'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Personal -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="d-flex align-items-center mb-4">
                                <i class="fas fa-user-circle me-2"></i>
                                Información personal
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Nombres:</strong> {{$client->nombre_de_cliente}}</p>
                                    <p class="mb-1"><strong>Nacionalidad:</strong> {{$client->pais}}</p>
                                    <p class="mb-1"><strong>Fecha de nacimiento:</strong> {{$client->fecha_de_nacimiento ? $client->fecha_de_nacimiento->format('Y-m-d') : 'No registrado'}}</p>
                                    <p class="mb-1"><strong>Género:</strong> {{$client->genero}}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Pasaporte:</strong> {{$client->pasaporte}}</p>
                                    <p class="mb-1"><strong>Estado civil:</strong> {{$client->estado_civil}}</p>
                                    <p class="mb-1"><strong>Profesión:</strong> {{$client->profesion}}</p>
                                    <p class="mb-1"><strong>Estatus en Canadá:</strong> {{$client->estatus}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Familia -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="d-flex align-items-center mb-4">
                                <i class="fas fa-users me-2"></i>
                                Familia
                            </h5>
                            @if(!empty($client->familia))
                                @php
                                    $familiares = json_decode($client->familia, true);
                                @endphp
                                @if(is_array($familiares) && count($familiares) > 0)
                                    @foreach($familiares as $familiar)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <p class="mb-0">{{$familiar['nombre'] ?? ''}} {{$familiar['apellido'] ?? ''}}</p>
                                                <small class="text-muted">{{$familiar['parentesco'] ?? ''}}</small>
                                            </div>
                                            <a href="#" class="btn btn-link btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0">No hay familiares registrados</p>
                                @endif
                            @else
                                <p class="text-muted mb-0">No hay familiares registrados</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Expedientes -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Expedientes</h5>
                        <a href="{{ route('expedientes.create', ['client_id' => $client->id]) }}" class="btn btn-success btn-sm">
                            Agregar
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($client->expedientes as $expediente)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{$expediente->numero_de_dossier}}</h6>
                                        <small class="text-muted">{{$expediente->tipoExpediente->nombre ?? 'No especificado'}}</small>
                                    </div>
                                    <span class="badge bg-{{ $expediente->estatus_del_expediente == 'Abierto' ? 'success' : 'secondary' }} rounded-pill">
                                        {{$expediente->estatus_del_expediente}}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted mb-0">No hay expedientes registrados</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Actividades Recientes -->
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Actividades recientes</h5>
                        <a href="{{ route('client.bitacoras.index', $client->id) }}" class="btn btn-success btn-sm">
                            Agregar
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($client->bitacoras()->latest()->take(5)->get() as $bitacora)
                            <div class="list-group-item">
                                <p class="mb-1">{{$bitacora->descripcion}}</p>
                                <small class="text-muted">{{$bitacora->created_at->format('M d, Y')}}</small>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted mb-0">No hay actividades registradas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
}
.btn-success {
    background-color: #004d40;
    border-color: #004d40;
}
.btn-success:hover {
    background-color: #00695c;
    border-color: #00695c;
}
.list-group-item {
    border-left: none;
    border-right: none;
}
.list-group-item:first-child {
    border-top: none;
}
.list-group-item:last-child {
    border-bottom: none;
}
.badge {
    font-weight: 500;
}
</style>
@endpush