<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Bienvenido a DATAROA";

});

Route::get('/clientes', function () {
    return "Bienvenido a la página de clientes";

    });

route::get('/clientes/{id}', function ($id) {
    return "Bienvenido a la página de clientes con ID {$id}";

    });

route::get('/clientes/creacion', function () {
    return "Bienvenido a la página de creación de clientes ROA";

    });

    route::get('/clientes/edicion', function () {
        return "Bienvenido a la página de edicion de clientes ROA";
    
        });



