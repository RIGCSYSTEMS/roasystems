@extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')
{{-- <style>
        .center {
        text-align: center;
        margin-top: 20%;
        color: #4cff16;
    }
     </style>
    <div class="center">
        <h1>ROASYSTEMS ------- CLIENTES ROA</h1>   
    </div> --}}
    
    
    <h1>CLIENTES ROA</h1>

    <a href="/client/create">
        Crear un nuevo cliente
    </a>



    <ul>    
        @foreach ($clients as $client)
            <li>
                <a href="/client/{{$client->id}}">
                    {{ $client->nombre_de_cliente }}

                </a>



            </li>
        @endforeach
    </ul>



@endsection