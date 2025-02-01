@extends('layouts.app')

@section('title', 'ROASYSTEMS - Expedientes')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-8">
                <h1 class="expedientes-title">
                    <span class="expedientes-text">EXPEDIENTES</span>
                    <span class="roa-text">ROA</span>
                </h1>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('expedientes.create') }}" class="btn btn-primary">Crear un nuevo expediente</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="expedientes" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha de Apertura</th>
                                <th>Estatus</th>
                                <th>Número de Dossier</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expedientes as $expediente)
                                <tr>
                                    <td>{{ $expediente->id }}</td>
                                    <td>{{ $expediente->client->nombre_de_cliente }}</td>
                                    <td>{{ $expediente->fecha_de_apertura }}</td>
                                    <td>{{ $expediente->estatus_del_expediente }}</td>
                                    <td>{{ $expediente->numero_de_dossier }}</td>
                                    <td>{{ $expediente->tipo }}</td>
                                    <td>
                                        <a href="{{ route('expedientes.show', $expediente->id) }}" class="btn btn-info btn-sm">Ver</a>
                                        <a href="{{ route('expedientes.edit', $expediente->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                        <form action="{{ route('expedientes.destroy', $expediente->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de que desea eliminar este expediente?')">Eliminar</button>
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
        $('#expedientes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
    });
</script>
@endpush