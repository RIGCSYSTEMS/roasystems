@extends('layouts.app')

@section('title', 'ROASYSTEMS - Documentos')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-8">
                <h1 class="documentos-title">
                    <span class="documentos-text">DOCUMENTOS</span>
                    <span class="roa-text">ROA</span>
                </h1>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('documentos.create') }}" class="btn btn-primary">Subir nuevo documento</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="documentos" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Identificación</th>
                                <th>Pasaporte</th>
                                <th>Permiso de Trabajo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td>{{ $documento->id }}</td>
                                    <td>{{ $documento->client->nombre_de_cliente }}</td>
                                    <td>{{ $documento->identificacion ? 'Sí' : 'No' }}</td>
                                    <td>{{ $documento->pasaporte ? 'Sí' : 'No' }}</td>
                                    <td>{{ $documento->permiso_de_trabajo ? 'Sí' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('documentos.show', $documento->id) }}" class="btn btn-info btn-sm">Ver</a>
                                        <a href="{{ route('documentos.edit', $documento->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                        <form action="{{ route('documentos.destroy', $documento->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de que desea eliminar este documento?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#documentos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
    });
</script>
@endpush