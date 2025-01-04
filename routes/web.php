<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\HonorariosController;
use App\Http\Controllers\AudienciaController;
use App\Http\Controllers\BitacoraController;
use App\Models\client;

// RUTA PRINCIPAL
Route::get('/', HomeController::class);
// RUTAS CLIENTES
// Route::get('/client', [ClientController::class, 'index']);
Route::resource('client', ClientController::class);
Route::get('/client/lista/getDataClientes', [ClientController::class, 'getDataClientes']);
// Route::get('/client/{id}/documentos', [ClientController::class, 'documentos'])->name('client.documentos');
// route::get('/client/create',[ClientController::class, 'create']);
// route::post('/client',[ClientController::class, 'store']);
// route::get('/client/{id}/edit',[clientController::class, 'edit']);
// route::get('/client/{id}',[ClientController::class, 'show']);
// route::put('/client/{id}',[ClientController::class, 'update']);
// route::delete('/client/{id}',[ClientController::class, 'destroy']);


// RUTAS PARA EXPEDIENTES
Route::resource('expedientes', ExpedienteController::class);

// RUTAS PARA DOCUMENTOS
Route::resource('documentos', DocumentosController::class);
Route::get('/client/{id}/documentos', [DocumentosController::class, 'show'])->name('client.documentos');
Route::post('/client/{id}/documentos/subir', [DocumentosController::class, 'subirDocumento'])->name('documentos.subir');
Route::get('/documentos/{id}/view', [DocumentosController::class, 'view'])->name('documentos.view');

// RUTAS PARA HONORARIOS
Route::post('/expedientes/{expediente}/honorarios', [HonorariosController::class, 'store'])->name('honorarios.store');
Route::put('/honorarios/{honorario}', [HonorariosController::class, 'update'])->name('honorarios.update');
Route::post('/honorarios/{honorario}/abonos', [HonorariosController::class, 'registrarAbono'])->name('honorarios.abono');

//RUTAS AUDIENCIAS
Route::post('/expedientes/{expediente}/audiencias', [AudienciaController::class, 'store'])->name('audiencias.store');
Route::put('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'update'])->name('audiencias.update');
Route::delete('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'destroy'])->name('audiencias.destroy');
Route::get('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'show'])->name('audiencias.show');

// RUTAS PARA BITACORAS
Route::resource('expedientes.bitacoras', BitacoraController::class);



// // RUTAS SEGUIMIENTO
// Route::get('/follow', [followController::class, 'index']);
// route::get('/follow/create',[followController::class, 'create']);
// // route::get('/seguimientos/edicion',[SeguimientosController::class, 'edicion']);
// route::get('/follow/{id}',[followController::class, 'show']);
// // RUTAS RESIDENTES TEMPORALES
// Route::get('/resident', [ResidentController::class, 'index']);
// route::get('/resident/create',[ResidentController::class, 'create']);
// // route::get('/residentes/edicion', [ResidentesController::class, 'edicion']);
// route::get('/residentes/{id}', [ResidentController::class, 'show']);
// // RUTAS LLAMADA ENTRANTES
// Route::get('/call', [callController::class, 'index']);
// route::get('/call/create', [callController::class, 'create']);
// // route::get('/llamadas/edicion', [LlamadasController::class, 'edicion']);
// route::get('/call/{id}', [callController::class, 'show']);