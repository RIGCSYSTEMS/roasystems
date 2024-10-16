@extends('layouts.app')

@section('title', 'ROASYSTEMS - Clientes')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-8">
                <h1>ASILO ROA</h1>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{url('/')}}/client/create" class="btn btn-primary">Crear un nuevo cliente</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
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
                                <th>Fecha de apertura</th>
                                <th>Fecha de cierre</th>
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
<style>
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
        z-index: 1;
    }
    .dataTables_wrapper {
        position: relative;
        min-height: 400px;
    }
    #clientes {
        position: relative;
        z-index: 2;
    }
    .dataTables_wrapper .dataTables_filter {
        float: none;
        text-align: center;
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 300px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#clientes').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            deferLoading: 0,
            ajax: {
                url: "{{url('/')}}/client/lista/getDataClientes",
                type: 'GET',
                data: function(d) {
                    d.fechas = $("#fechas").val();
                    d.search_active = d.search.value.length >= 3 ? 1 : 0;
                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nombre', name: 'nombre_de_cliente' },
                { data: 'telefono', name: 'telefono' },
                { data: 'correo', name: 'email' },
                { data: 'direccion', name: 'direccion' },
                { data: 'pais', name: 'pais' },
                { data: 'fecha_apertura', name: 'fecha_de_apertura' },
                { data: 'fecha_cierre', name: 'fecha_de_cierre' },
                { data: 'estatus', name: 'estatus' },
                { 
                    data: 'acciones', 
                    name: 'acciones', 
                    orderable: false, 
                    searchable: false 
                },
            ],
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                emptyTable: 'Realice una búsqueda para ver los resultados'
            },
            initComplete: function() {
                var api = this.api();
                var typingTimer;
                var doneTypingInterval = 500; // medio segundo

                $('#clientes_filter input')
                    .off('.DT')
                    .on('input.DT', function() {
                        clearTimeout(typingTimer);
                        var input = this;
                        typingTimer = setTimeout(function() {
                            if (input.value.length >= 3) {
                                api.search(input.value).draw();
                                $('#logo-background').fadeOut();
                            } else if (input.value.length === 0) {
                                api.search('').draw();
                                $('#logo-background').fadeIn();
                            }
                        }, doneTypingInterval);
                    });
            },
            drawCallback: function(settings) {
                if (settings.json && settings.json.recordsTotal > 0 && settings.json.search_active) {
                    $('#logo-background').hide();
                } else {
                    $('#logo-background').show();
                }
            }
        });

        $('#clientes_filter input').keypress(function(e) {
            if(e.which == 13) {
                table.search(this.value).draw();
                if (this.value.length > 0) {
                    $('#logo-background').fadeOut();
                } else {
                    $('#logo-background').fadeIn();
                }
            }
        });
    });
</script>
@endpush