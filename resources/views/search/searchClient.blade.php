@extends('layouts.app')

@section('title', 'ROASYSTEMS - Clientes')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="clientes-title">
                <span class="clientes-text pe-3 pe-sm-3 pe-md-auto">CLIENTES</span>
                <span class="roa-text">ROA</span>
            </h1>
        </div>
        <div class="col-md-4 text-center text-sm-center text-md-end my-4 my-md-auto">
            <a href="{{ route('client.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Crear un nuevo cliente
            </a>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggleClients">
                <label class="form-check-label" for="toggleClients">Mostrar todos los clientes</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive position-relative">
                <div id="logo-background" class="logo-background"></div>
                <table id="searchClient" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>País</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('css/client_index.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    let showAll = false;
    let table;

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#searchClient')) {
            $('#searchClient').DataTable().destroy();
        }
        
        table = $('#searchClient').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('searchClient.getDataClientes') }}",
                data: function (d) {
                    d.show_all = showAll ? 1 : 0;
                },
            },
            columns: [
                {data: 'id', name: 'id'},
                {
                    data: 'nombre_de_cliente', 
                    name: 'nombre_de_cliente',
                    render: function(data, type, row) {
                        return '<a href="/client/' + row.id + '">' + data + '</a>';
                    }
                },
                {data: 'telefono', name: 'telefono'},
                {data: 'email', name: 'email'},
                {data: 'direccion', name: 'direccion'},
                {data: 'pais', name: 'pais'},
                {data: 'estatus', name: 'estatus'},
                {data: 'acciones', name: 'acciones', orderable: false, searchable: false}
            ],
            language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
        },

            drawCallback: function(settings) {
                if (settings.aoData.length === 0) {
                    $('.dataTables_scrollBody').css('height', '300px');
                }
            }
        });

        $('#clientes tbody').on('click', 'tr', function(e) {
            if (!$(e.target).is('a') && !$(e.target).is('button')) {
                var data = table.row(this).data();
                if (data) {
                    window.location.href = '/client/' + data.id;
                }
            }
        });
    }

    initDataTable();

    $('#toggleClients').on('change', function() {
        showAll = this.checked;
        table.ajax.reload();
    });
});
function confirmarEliminarCliente(clienteId) {
    if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
        $.ajax({
            url: `/client/${clienteId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Recargar la tabla
                    var table = $('#searchClient').DataTable();
                    table.ajax.reload();
                    
                    // Mostrar mensaje de éxito
                    alert('Cliente eliminado correctamente');
                } else {
                    alert(response.message || 'Error al eliminar el cliente');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Recargar la tabla de todos modos
                var table = $('#searchClient').DataTable();
                table.ajax.reload();
            },
            complete: function() {
                // Esto se ejecutará sin importar si la petición fue exitosa o no
                var table = $('#searchClient').DataTable();
                table.ajax.reload();
            }
        });
    }
}
function eliminarCliente(clienteId) {
    $.ajax({
        url: '/client/' + clienteId,
        type: 'DELETE',
        data: {
            "_token": "{{ csrf_token() }}",
        },
        success: function(result) {
            // Recargar la tabla
            $('#searchClient').DataTable().ajax.reload();
            // Mostrar mensaje de éxito
            alert('Cliente eliminado correctamente');
        },
        error: function(xhr) {
            // Mostrar mensaje de error
            alert('Error al eliminar el cliente');
        }
    });
}
</script>
@endpush