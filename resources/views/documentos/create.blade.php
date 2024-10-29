@extends('layouts.app')

@section('title', 'Subir Documentos')

@section('content')
<div class="container">
    <h1>Subir Documentos para {{ $client->nombre_de_cliente }}</h1>
    <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <div class="form-group">
            <label for="client_name">Cliente</label>
            <input type="text" id="client_name" class="form-control" value="{{ $client->nombre_de_cliente }}" readonly>
        </div>
        <div class="form-group">
            <label for="identificacion">Identificación</label>
            <input type="file" name="identificacion" id="identificacion" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="pasaporte">Pasaporte</label>
            <input type="file" name="pasaporte" id="pasaporte" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="permiso_de_trabajo">Permiso de Trabajo</label>
            <input type="file" name="permiso_de_trabajo" id="permiso_de_trabajo" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="hoja_marron">Hoja Marrón</label>
            <input type="file" name="hoja_marron" id="hoja_marron" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="pruebas">Pruebas</label>
            <input type="file" name="pruebas" id="pruebas" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="historia">Historia</label>
            <input type="file" name="historia" id="historia" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="residencia_permanente">Residencia Permanente</label>
            <input type="file" name="residencia_permanente" id="residencia_permanente" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="caq">CAQ</label>
            <input type="file" name="caq" id="caq" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="form-group">
            <label for="extras">Extras</label>
            <input type="file" name="extras" id="extras" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <button type="submit" class="btn btn-primary">Subir Documentos</button>
    </form>
</div>
@endsection