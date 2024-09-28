@extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')

    <h1>Creacion de clientes</h1>
    

    <form action="/client"method="post">

        @csrf

        <label>
            Nombre de cliente:
        <input type="text" name="nombre_de_cliente">
    </label>
<br>
<br>
        <label for="otros_nombres_de_cliente">Otros nombres de cliente:</label>
        <input type="text" name="otros_nombres_de_cliente" id="otros_nombres_de_cliente">
        <br>
        <br>     
        <label for="direccion">Direccion:</label>
        <input type="text" name="direccion" id="direccion">
        <br>
        <br>        
        <label for="telefono">Telefono:</label>
        <input type="text" name="telefono" id="telefono">
        <br>
        <br>        
        <label for="email">Correo:</label>
        <input type="text" name="email" id="email">
        <br>
        <br>        
        <label for="profesion">Profesion:</label>
        <input type="text" name="profesion" id="profesion">
        <br>
        <br>        
        <label for="pais">Pais de origen:</label>
        <input type="text" name="pais" id="pais">
        <br>
        <br>        
        <label for="despacho">Despacho:</label>
        <input type="text" name="despacho" id="despacho">
        <br>
        <br>        
        <label for="tipo_de_expediente">Tipo de expediente:</label>
        <input type="text" name="tipo_de_expediente" id="tipo_de_expediente">
        <br>
        <br>        
        <label for="lenguaje">Lenguaje:</label>
        <input type="text" name="lenguaje" id="lenguaje">
        <br>
        <br>        
        <label for="honorarios">Honorarios:</label>
        <input type="text" name="honorarios" id="honorarios">
        <br>
        <br>        
        <label for="fecha_de_apertura">Fecha de apertura:</label>
        <input type="text" name="fecha_de_apertura" id="fecha_de_apertura">
        <br>
        <br>        
        <label for="estatus">Estatus:</label>
        <input type="text" name="estatus" id="estatus">
        <br>
        <br>        
        <label for="observaciones">Observaciones:</label>
        <input type="text" name="observaciones" id="observaciones">
        <br>
        <br>        
        <label for="numero_de_expediente">Numero de expediente:</label>
        <input type="text" name="numero_de_expediente" id="numero_de_expediente">
        <br>
        <br>        
        <label for="permiso_de_trabajo">Permiso de trabajo:</label>
        <input type="text" name="permiso_de_trabajo" id="permiso_de_trabajo">
        <br>
        <br>
        <label for="iuc">IUC:</label>
        <input type="text" name="iuc" id="iuc">
        <br>
        <br>
        <label for="ubicacion_del_despacho">Ubicacion del despacho:</label>
        <input type="text" name="ubicacion_del_despacho" id="ubicacion_del_despacho">
        <br>
        <br>
        <label for="fecha_de_cierre">Fecha de cierre:</label>
        <input type="text" name="fecha_de_cierre" id="fecha_de_cierre">
        <br>
        <br>
        <label for="cierre_del_numero_de_caja">Cierre de Numero de caja:</label>
        <input type="text" name="cierre_del_numero_de_caja" id="cierre_del_numero_de_caja">
        <br>
        <br>
    <button type="submit">
        Crear cliente
    </button>
    <a href="/client">Volver a la lista de clientes</a>        



    </form>












@endsection

















































{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DATASYSTEMS/CLIENTES</title>
</head>
<body>
    <H1>DATASYSTEMS ------- CREACION DE CLIENTES </H1>
</body>
</html> --}}