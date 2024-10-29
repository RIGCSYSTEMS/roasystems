@extends('layouts.app')

@section('title', 'ROASYSTEMS - Editar Expediente')

@section('content')
    <div class="container">
        <h1>Editar Expediente</h1>
        <form action="{{ route('expedientes.update', $expediente->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="client_id">Cliente</label>
                <select name="client_id" id="client_id" class="form-control" required>
                    @foreach(\App\Models\Client::all() as $client)
                        <option value="{{ $client->id }}" {{ $expediente->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->nombre_de_cliente }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_de_apertura">Fecha de Apertura</label>
                <input type="date" name="fecha_de_apertura" id="fecha_de_apertura" class="form-control" value="{{ $expediente->fecha_de_apertura }}" required>
            </div>
            <div class="form-group">
                <label for="estatus_del_expediente">Estatus del Expediente</label>
                <input type="text" name="estatus_del_expediente" id="estatus_del_expediente" class="form-control" value="{{ $expediente->estatus_del_expediente }}" required>
            </div>
            <div class="form-group">
                <label for="fecha_de_cierre">Fecha de Cierre</label>
                <input type="date" name="fecha_de_cierre" id="fecha_de_cierre" class="form-control" value="{{ $expediente->fecha_de_cierre }}">
            </div>
            <div class="form-group">
                <label for="numero_de_dossier">Número de Dossier</label>
                <input type="text" name="numero_de_dossier" id="numero_de_dossier" class="form-control" value="{{ $expediente->numero_de_dossier }}" required>
            </div>
            <div class="form-group">
                <label for="despacho">Despacho</label>
                <input type="text" name="despacho" id="despacho" class="form-control" value="{{ $expediente->despacho }}" required>
            </div>
            <div class="form-group">
                <label for="abogado">Abogado</label>
                <input type="text" name="abogado" id="abogado" class="form-control" value="{{ $expediente->abogado }}" required>
            </div>
            <div class="form-group">
                <label for="honorarios">Honorarios</label>
                <input type="number" step="0.01" name="honorarios" id="honorarios" class="form-control" value="{{ $expediente->honorarios }}" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Expediente</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="asilo" {{ $expediente->tipo == 'asilo' ? 'selected' : '' }}>Asilo</option>
                    <option value="appel" {{ $expediente->tipo == 'appel' ? 'selected' : '' }}>Appel</option>
                    <option value="permanente" {{ $expediente->tipo == 'permanente' ? 'selected' : '' }}>Permanente</option>
                    <option value="erar" {{ $expediente->tipo == 'erar' ? 'selected' : '' }}>ERAR</option>
                    <option value="apadrinamiento" {{ $expediente->tipo == 'apadrinamiento' ? 'selected' : '' }}>Apadrinamiento</option>
                    <option value="humanitaria" {{ $expediente->tipo == 'humanitaria' ? 'selected' : '' }}>Humanitaria</option>
                    <option value="temporal" {{ $expediente->tipo == 'temporal' ? 'selected' : '' }}>Temporal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Expediente</button>
        </form>
    </div>
@endsection