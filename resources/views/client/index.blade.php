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
<script src="{{ asset('js/dataTables-config.js') }}"></script>
@endpush