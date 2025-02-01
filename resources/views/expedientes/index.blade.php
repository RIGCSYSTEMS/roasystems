@extends('layouts.app')

@section('content')
<div class="container">
    <div id="app">
        @if(isset($expedienteId))
            <Expediente-index :expediente-id="{{ $expedienteId }}"></Expediente-index>
        @else
            <div class="alert alert-danger">
                No se ha especificado un expediente para mostrar.
            </div>
        @endif
    </div>
</div>
@endsection