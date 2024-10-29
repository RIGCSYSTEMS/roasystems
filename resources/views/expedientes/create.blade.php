@extends('layouts.app')

@section('title', 'Crear Expediente')

@section('content')
<div class="container">
    <h1>Crear Nuevo Expediente para {{ $client->nombre_de_cliente }}</h1>
    <form method="POST" action="{{ route('expedientes.store') }}">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <div class="form-group">
            <label for="client_name">Cliente</label>
            <input type="text" id="client_name" class="form-control" value="{{ $client->nombre_de_cliente }}" readonly>
        </div>
        <div class="form-group">
            <label for="fecha_de_apertura">Fecha de Apertura</label>
            <input type="date" name="fecha_de_apertura" id="fecha_de_apertura" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="estatus_del_expediente">Estatus del Expediente</label>
            <input type="text" name="estatus_del_expediente" id="estatus_del_expediente" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="numero_de_dossier">Número de Dossier</label>
            <input type="text" name="numero_de_dossier" id="numero_de_dossier" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="despacho">Despacho</label>
            <input type="text" name="despacho" id="despacho" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="abogado">Abogado</label>
            <input type="text" name="abogado" id="abogado" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="honorarios">Honorarios</label>
            <input type="number" step="0.01" name="honorarios" id="honorarios" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo de Expediente</label>
            <select name="tipo" id="tipo" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                <option value="asilo">Asilo</option>
                <option value="appel">Appel</option>
                <option value="permanente">Permanente</option>
                <option value="erar">ERAR</option>
                <option value="apadrinamiento">Apadrinamiento</option>
                <option value="humanitaria">Humanitaria</option>
                <option value="temporal">Temporal</option>
            </select>
        </div>
        <div id="campos-adicionales" style="display: none;">
            <!-- Campos adicionales para cada tipo de expediente -->
        </div>
        <button type="submit" class="btn btn-primary">Crear Expediente</button>
    </form>
</div>

<script>
document.getElementById('tipo').addEventListener('change', function() {
    var camposAdicionales = document.getElementById('campos-adicionales');
    if (this.value) {
        camposAdicionales.style.display = 'block';
        camposAdicionales.innerHTML = `
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="fecha_de_recepcion">Fecha de Recepción</label>
                <input type="date" name="fecha_de_recepcion" id="fecha_de_recepcion" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="tiempo">Tiempo</label>
                <input type="text" name="tiempo" id="tiempo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="persona_responsible">Persona Responsable</label>
                <input type="text" name="persona_responsible" id="persona_responsible" class="form-control" required>
            </div>
        `;
    } else {
        camposAdicionales.style.display = 'none';
    }
});
</script>
@endsection