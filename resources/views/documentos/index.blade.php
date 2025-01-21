@extends('layouts.app')

@section('title', 'ROASYSTEMS - Búsqueda de Documentos')

@section('content')
<div id="app">
    <documento-index 
        :client-id="{{ $client->id }}" 
        :client-name="'{{ $client->nombre_de_cliente }}'"
    ></documento-index>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/app.js') }}"></script>
@endpush