{{-- @extends('layouts.app')

@section('title', 'Subir Documentos')

@section('content')
<div class="container">
    <h1 class="mb-4">Subir Documentos para {{ $client->nombre_de_cliente }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="documentForm" method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        
        <div class="mb-3">
            <label for="client_name" class="form-label">Cliente</label>
            <input type="text" id="client_name" class="form-control" value="{{ $client->nombre_de_cliente }}" readonly>
        </div>

        <div id="documentos-container">
            <div class="documento-form border rounded p-3 mb-3">
                <div class="mb-3">
                    <label for="documentos[0][nombre_de_documento]" class="form-label">Tipo de Documento</label>
                    <select name="documentos[0][nombre_de_documento]" class="form-select" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="IDENTIFICACION">Identificación</option>
                        <option value="PASAPORTE">Pasaporte</option>
                        <option value="PERMISO DE TRABAJO">Permiso de Trabajo</option>
                        <option value="HOJA MARRON">Hoja Marrón</option>
                        <option value="PRUEBAS">Pruebas</option>
                        <option value="HISTORIA">Historia</option>
                        <option value="RESIDENCIA PERMANENTE">Residencia Permanente</option>
                        <option value="CAQ">CAQ</option>
                        <option value="EXTRAS">Extras</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="documentos[0][tipo_de_documento]" class="form-label">Formato del Documento</label>
                    <select name="documentos[0][tipo_de_documento]" class="form-select" required>
                        <option value="">Seleccione un formato</option>
                        <option value="PDF">PDF</option>
                        <option value="IMAGEN">Imagen</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="documentos[0][archivo]" class="form-label">Archivo</label>
                    <input type="file" name="documentos[0][archivo]" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="mb-3">
                    <label for="documentos[0][observaciones]" class="form-label">Observaciones</label>
                    <textarea name="documentos[0][observaciones]" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-secondary" id="agregar-documento">
                <i class="fas fa-plus"></i> Adjuntar Otro Documento
            </button>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Subir Documentos
            </button>
            <a href="{{ route('client.documentos', $client->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Documentos del Cliente
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let documentoCount = 1;

    document.getElementById('agregar-documento').addEventListener('click', function() {
        const container = document.getElementById('documentos-container');
        const nuevoDocumento = document.createElement('div');
        nuevoDocumento.className = 'documento-form border rounded p-3 mb-3';
        nuevoDocumento.innerHTML = `
            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-danger btn-sm remove-documento">
                    <i class="fas fa-times"></i> Eliminar
                </button>
            </div>
            <div class="mb-3">
                <label for="documentos[${documentoCount}][nombre_de_documento]" class="form-label">Tipo de Documento</label>
                <select name="documentos[${documentoCount}][nombre_de_documento]" class="form-select" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="IDENTIFICACION">Identificación</option>
                    <option value="PASAPORTE">Pasaporte</option>
                    <option value="PERMISO DE TRABAJO">Permiso de Trabajo</option>
                    <option value="HOJA MARRON">Hoja Marrón</option>
                    <option value="PRUEBAS">Pruebas</option>
                    <option value="HISTORIA">Historia</option>
                    <option value="RESIDENCIA PERMANENTE">Residencia Permanente</option>
                    <option value="CAQ">CAQ</option>
                    <option value="EXTRAS">Extras</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="documentos[${documentoCount}][tipo_de_documento]" class="form-label">Formato del Documento</label>
                <select name="documentos[${documentoCount}][tipo_de_documento]" class="form-select" required>
                    <option value="">Seleccione un formato</option>
                    <option value="PDF">PDF</option>
                    <option value="IMAGEN">Imagen</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="documentos[${documentoCount}][archivo]" class="form-label">Archivo</label>
                <input type="file" name="documentos[${documentoCount}][archivo]" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <div class="mb-3">
                <label for="documentos[${documentoCount}][observaciones]" class="form-label">Observaciones</label>
                <textarea name="documentos[${documentoCount}][observaciones]" class="form-control" rows="3"></textarea>
            </div>
        `;

        container.appendChild(nuevoDocumento);
        documentoCount++;
    });

    document.getElementById('documentos-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-documento') || e.target.closest('.remove-documento')) {
            const documentoForm = e.target.closest('.documento-form');
            if (documentoForm) {
                documentoForm.remove();
            }
        }
    });

    document.getElementById('documentForm').addEventListener('submit', function(e) {
        const archivos = document.querySelectorAll('input[type="file"]');
        let archivoSeleccionado = false;
        
        archivos.forEach(archivo => {
            if (archivo.files.length > 0) {
                archivoSeleccionado = true;
            }
        });

        if (!archivoSeleccionado) {
            e.preventDefault();
            alert('Por favor, seleccione al menos un archivo para subir.');
        }
    });
</script>
@endsection --}}