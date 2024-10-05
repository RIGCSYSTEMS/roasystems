@extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>CLIENTES ROA</h1>
            </div>
            <div class="col-md-4">
                <a href="{{url('/')}}/client/create" class="text-primary float-right btn btn-primary" style="text-decoration: none; color:aliceblue!important">Crear un nuevo cliente</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
                <table id="clientes" class="table table-bordered table-striped dataTable dtr-inline" role="grid"
                    aria-describedby="example1_info">
                    <thead>
                        <tr role="row">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
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
@endsection

<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        table = $('#clientes').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{url('/')}}/client/lista/getDataClientes",
                type: 'GET',
                data: function(d) {
                    console.log($("#fechas").val())
                    d.fechas = $("#fechas").val();
                },
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'telefono',
                    name: 'telefono'
                },
                {
                    data: 'correo',
                    name: 'correo'
                },
                {
                    data: 'fecha_apertura',
                    name: 'fecha_apertura'
                },
                {
                    data: 'fecha_cierre',
                    name: 'fecha_cierre'
                },
                {
                    data: 'estatus',
                    name: 'estatus'
                },
                {
                    data: 'acciones',
                    name: 'acciones'
                },
            ],
            responsive: true,
            autoWidth: false,
            ordering: false,
           
        });

    })
</script>
