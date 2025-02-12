@extends('layouts.app')

@section('content')
    <div id="app">
        @if(isset($expediente))
            <expediente-index :expediente="{{ json_encode($expediente) }}"
            :expediente-id="{{ $expediente->id }}" 
            :expediente-name="'{{ $expediente->numero_de_dossier }}'"
            user-role="{{ auth()->user()->role }}"                   
            ></expediente-index>
        @else
            <div class="alert alert-danger">
                No se ha especificado un expediente para mostrar.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="{{ mix('js/app.js') }}"></script>
@endpush