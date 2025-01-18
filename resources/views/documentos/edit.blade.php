{{-- @extends('layouts.app')

@section('title', 'ROASYSTEMS - Editar Documento')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Documento</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('documentos.update', $documento->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="client_id" class="form-label">Cliente</label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ $documento->client_id == $client->id ? 'selected' : '' }}>
                                {{ $client->nombre_de_cliente }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nombre_de_documento" class="form-label">Tipo de Documento</label>
                    <select name="nombre_de_documento" id="nombre_de_documento" class="form-select" required>
                        <option value="IDENTIFICACION" {{ $documento->nombre_de_documento == 'IDENTIFICACION' ? 'selected' : '' }}>Identificación</option>
                        <option value="PASAPORTE" {{ $documento->nombre_de_documento == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                        <option value="PERMISO DE TRABAJO" {{ $documento->nombre_de_documento == 'PERMISO DE TRABAJO' ? 'selected' : '' }}>Permiso de Trabajo</option>
                        <option value="HOJA MARRON" {{ $documento->nombre_de_documento == 'HOJA MARRON' ? 'selected' : '' }}>Hoja Marrón</option>
                        <option value="PRUEBAS" {{ $documento->nombre_de_documento == 'PRUEBAS' ? 'selected' : '' }}>Pruebas</option>
                        <option value="HISTORIA" {{ $documento->nombre_de_documento == 'HISTORIA' ? 'selected' : '' }}>Historia</option>
                        <option value="RESIDENCIA PERMANENTE" {{ $documento->nombre_de_documento == 'RESIDENCIA PERMANENTE' ? 'selected' : '' }}>Residencia Permanente</option>
                        <option value="CAQ" {{ $documento->nombre_de_documento == 'CAQ' ? 'selected' : '' }}>CAQ</option>
                        <option value="EXTRAS" {{ $documento->nombre_de_documento == 'EXTRAS' ? 'selected' : '' }}>Extras</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo_de_documento" class="form-label">Formato del Documento</label>
                    <select name="tipo_de_documento" id="tipo_de_documento" class="form-select" required>
                        <option value="PDF" {{ $documento->tipo_de_documento == 'PDF' ? 'selected' : '' }}>PDF</option>
                        <option value="IMAGEN" {{ $documento->tipo_de_documento == 'IMAGEN' ? 'selected' : '' }}>Imagen</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="archivo" class="form-label">Archivo</label>
                    <input type="file" name="archivo" id="archivo" class="form-control">
                    @if($documento->imagen_url)
                        <div class="mt-2">
                            <p>Archivo actual: <a href="{{ asset($documento->imagen_url) }}" target="_blank">Ver archivo</a></p>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">OBSERVACIONES</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="4">{{ $documento->observaciones }}</textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('client.documentos', $documento->client_id) }}" class="btn btn-secondary me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aquí puedes agregar cualquier JavaScript necesario para la funcionalidad de la página
</script>
@endpush --}}