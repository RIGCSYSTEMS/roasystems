<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\SeguimientosController;
use App\Http\Controllers\ResidentesController;
use App\Http\Controllers\LlamadasController;

// RUTA PRINCIPAL
Route::get('/', HomeController::class);
// RUTAS CLIENTES
Route::get('/clientes',[ClientesController::class, 'index']);
route::get('/clientes/creacion',[ClientesController::class, 'creacion']);
route::get('/clientes/edicion',[ClientesController::class, 'edicion']);
route::get('/clientes/{id}',[ClientesController::class, 'show']);
// RUTAS SEGUIMIENTO
Route::get('/seguimientos', [SeguimientosController::class, 'index']);
route::get('/seguimientos/creacion',[SeguimientosController::class, 'creacion']);
route::get('/seguimientos/edicion',[SeguimientosController::class, 'edicion']);
route::get('/seguimientos/{id}',[SeguimientosController::class, 'show']);
// RUTAS RESIDENTES TEMPORALES
Route::get('/residentes', [ResidentesController::class, 'index']);
route::get('/residentes/creacion',[ResidentesController::class, 'creacion']);
route::get('/residentes/edicion', [ResidentesController::class, 'edicion']);
route::get('/residentes/{id}', [ResidentesController::class, 'show']);
// RUTAS LLAMADA ENTRANTES
Route::get('/llamadas', [LlamadasController::class, 'index']);
route::get('/llamadas/creacion', [LlamadasController::class, 'creacion']);
route::get('/llamadas/edicion', [LlamadasController::class, 'edicion']);
route::get('/llamadas/{id}', [LlamadasController::class, 'show']);
   

// Route::get('/clientes/{id}/{despacho?}', function ($id, $despacho = null) {
    
//     if ($despacho) {
//         return "Cliente DataRoa con ID {$id} del despacho {$despacho}";
//     }
//     return "Cliente DataRoa con ID {$id}";

//     });