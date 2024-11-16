@extends('layouts.app')

@section('title', 'ROASYSTEMS - Búsqueda de Documentos')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Búsqueda de Documentos</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('documentos.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="client_search" class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="client_search" name="client_search" value="{{ request('client_search') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="document_type" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="document_type" name="document_type">
                            <option value="">Todos</option>
                            <option value="IDENTIFICACION" {{ request('document_type') == 'IDENTIFICACION' ? 'selected' : '' }}>Identificación</option>
                            <option value="PASAPORTE" {{ request('document_type') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="PERMISO DE TRABAJO" {{ request('document_type') == 'PERMISO DE TRABAJO' ? 'selected' : '' }}>Permiso de Trabajo</option>
                            <option value="HOJA MARRON" {{ request('document_type') == 'HOJA MARRON' ? 'selected' : '' }}>Hoja Marrón</option>
                            <option value="PRUEBAS" {{ request('document_type') == 'PRUEBAS' ? 'selected' : '' }}>Pruebas</option>
                            <option value="HISTORIA" {{ request('document_type') == 'HISTORIA' ? 'selected' : '' }}>Historia</option>
                            <option value="RESIDENCIA PERMANENTE" {{ request('document_type') == 'RESIDENCIA PERMANENTE' ? 'selected' : '' }}>Residencia Permanente</option>
                            <option value="CAQ" {{ request('document_type') == 'CAQ' ? 'selected' : '' }}>CAQ</option>
                            <option value="EXTRAS" {{ request('document_type') == 'EXTRAS' ? 'selected' : '' }}>Extras</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Buscar</button>
                        <a href="{{ route('documentos.index') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Tipo de Documento</th>
                            <th>Formato</th>
                            <th>Fecha de Subida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentos as $documento)
                            <tr>
                                <td>{{ $documento->client->nombre_de_cliente }}</td>
                                <td>{{ $documento->nombre_de_documento }}</td>
                                <td>
                                    <span class="badge bg-{{ $documento->tipo_de_documento == 'PDF' ? 'danger' : 'primary' }}">
                                        {{ $documento->tipo_de_documento }}
                                    </span>
                                </td>
                                <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('documentos.show', $documento->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron documentos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $documentos->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Aquí puedes agregar cualquier JavaScript necesario para la funcionalidad de la página
</script>
@endpush