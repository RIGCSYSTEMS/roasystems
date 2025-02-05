@extends('layouts.app')

@section('content')
<div class="container">
    <div id="app">
        @if(isset($expediente))
            <expediente-index :expediente="{{ json_encode($expediente) }}"></expediente-index>
        @else
            <div class="alert alert-danger">
                No se ha especificado un expediente para mostrar.
            </div>
        @endif
    </div>
</div>
@endsection