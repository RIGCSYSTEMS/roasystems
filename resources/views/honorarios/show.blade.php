@extends('layouts.app')

@section('content')
<div class="container">
    <div id="app">
        <honorarios-component 
            :honorario-id="{{ $honorario->id }}"
            :initial-data="{{ json_encode([
                'montoInicial' => $honorario->monto_inicial,
                'fechaApertura' => $honorario->fecha_apertura->format('Y-m-d'),
                'abonos' => $honorario->abonos,
                'extras' => $honorario->extras,
            ]) }}"
        ></honorarios-component>
    </div>
</div>
@endsection