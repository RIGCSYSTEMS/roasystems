@extends('layouts.app')

@section('title', 'ROASYSTEMS - Documentos del Cliente')

@section('content')
<div class="container">
    <h1 class="mb-4">Documentos de {{ $client->nombre_de_cliente }}</h1>
    <a href="{{ route('client.show', $client->id) }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver al perfil del cliente
    </a>
    
    <!-- Formulario para subir nuevos documentos -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Subir nuevo documento</h5>
            <form action="{{ route('documentos.subir', $client->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <select name="nombre_de_documento" class="form-select" required>
                            <option value="">Seleccione el tipo de documento</option>
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
                    <div class="col-md-3 mb-3">
                        <select name="tipo_de_documento" class="form-select" required>
                            <option value="">Seleccione el formato</option>
                            <option value="PDF">PDF</option>
                            <option value="IMAGEN">Imagen</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="file" name="archivo" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary w-100">Subir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($documentos->count() > 0)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Documentos existentes</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tipo de Documento</th>
                                <th>Formato</th>
                                <th>Fecha de subida</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td>
                                        @if($documento->tipo_de_documento == 'PDF')
                                            <i class="far fa-file-pdf text-danger"></i>
                                        @else
                                            <i class="far fa-file-image text-primary"></i>
                                        @endif
                                        {{ $documento->nombre_de_documento }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $documento->tipo_de_documento == 'PDF' ? 'danger' : 'primary' }}">
                                            {{ $documento->tipo_de_documento }}
                                        </span>
                                    </td>
                                    <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="verDocumento('{{ $documento->imagen_url }}', '{{ $documento->tipo_de_documento }}')">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                        <a href="{{ route('documentos.edit', $documento->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('documentos.destroy', $documento->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este documento?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i> Este cliente no tiene documentos asociados.
        </div>
    @endif

    <!-- Modal para visualizar documentos -->
    <div class="modal fade" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentoModalLabel">Ver Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="documentoModalBody">
                    <!-- El contenido del documento se cargará aquí -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.verDocumento = function(url, tipo) {
        const modalBody = document.getElementById('documentoModalBody');
        modalBody.innerHTML = '';

        if (tipo === 'PDF') {
            modalBody.innerHTML = `<embed src="${url}" type="application/pdf" width="100%" height="600px" />`;
        } else {
            modalBody.innerHTML = `<img src="${url}" class="img-fluid" alt="Documento" />`;
        }

        const modal = new bootstrap.Modal(document.getElementById('documentoModal'));
        modal.show();
    }
});
</script>
@endsection