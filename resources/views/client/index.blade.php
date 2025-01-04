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
                <table id="clientes" class="table table-bordered table-striped w-100">
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
<style>
    .clientes-title {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .clientes-text {
        color: #000000;
    }
    .roa-text {
        color: #ffffff;
    }
    .logo-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('{{ asset('images/logo.png') }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        opacity: 0.1;
        pointer-events: none;
        z-index: 0;
    }
    .table-responsive {
        position: relative;
        z-index: 1;
        min-height: 400px;
    }
    #clientes {
        position: relative;
        z-index: 2;
    }
    .dataTables_wrapper {
        position: relative;
        min-height: 400px;
    }
    .dataTables_wrapper .dataTables_filter {
        float: none;
        text-align: center;
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 300px;
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
    .form-check-label {
        padding-left: 0.5em;
        font-size: 1.1em;
    }
    .dataTables_processing {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        z-index: 9999 !important;
        border: none !important;
        background: rgba(255, 255, 255, 0.9) !important;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;
        padding: 15px !important;
        border-radius: 5px !important;
    }
    </style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    let showAll = false;
    let table;

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#clientes')) {
            $('#clientes').DataTable().destroy();
        }
        
        table = $('#clientes').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('client.getDataClientes') }}",
                data: function (d) {
                    d.show_all = showAll ? 1 : 0;
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nombre_de_cliente', name: 'nombre_de_cliente'},
                {data: 'telefono', name: 'telefono'},
                {data: 'email', name: 'email'},
                {data: 'direccion', name: 'direccion'},
                {data: 'pais', name: 'pais'},
                {data: 'estatus', name: 'estatus'},
                {data: 'acciones', name: 'acciones', orderable: false, searchable: false}
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            drawCallback: function(settings) {
                if (settings.aoData.length === 0) {
                    $('.dataTables_scrollBody').css('height', '300px');
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
</script>
@endpush