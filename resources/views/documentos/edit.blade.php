@extends('layouts.app')

@section('title', 'ROASYSTEMS - Editar Documento')

@section('content')
    <div class="container">
        <h1>Editar Documento</h1>
        <form action="{{ route('documentos.update', $documento->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="client_id">Cliente</label>
                <select name="client_id" id="client_id" class="form-control" required>
                    @foreach(\App\Models\Client::all() as $client)
                        <option value="{{ $client->id }}" {{ $documento->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->nombre_de_cliente }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="identificacion">Identificación</label>
                <input type="file" name="identificacion" id="identificacion" class="form-control-file">
                @if($documento->identificacion)
                    <p>Archivo actual: {{ basename($documento->identificacion) }}</p>
                @endif
            </div>
            <div class="form-group">
                <label for="pasaporte">Pasaporte</label>
                <input type="file" name="pasaporte" id="pasaporte" class="form-control-file">
                @if($documento->pasaporte)
                    <p>Archivo actual: {{ basename($documento->pasaporte) }}</p>
                @endif
            </div>
            <div class="form-group">
                <label for="permiso_de_trabajo">Permiso de Trabajo</label>
                <input type="file" name="permiso_de_trabajo" id="permiso_de_trabajo" class="form-control-file">
                @if($documento->permiso_de_trabajo)
                    <p>Archivo actual: {{ basename($documento->permiso_de_trabajo) }}</p>
                
                @endif
            </div>
            <div class="form-group">
                <label for="historia">Historia</label>
                <textarea name="historia" id="historia" class="form-control">{{ $documento->historia }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Documento</button>
        </form>
    </div>
@endsection