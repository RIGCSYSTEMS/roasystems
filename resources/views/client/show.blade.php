@extends('layouts.app')

@section('title', 'ROASYSTEMS - Perfil del Cliente')

@section('content')

<h1>Perfil del Cliente</h1>

<div id="app">
    <vista-cliente :client-data="{{ json_encode($clientData) }}">
    </vista-cliente>
</div>


@endsection

