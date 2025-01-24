<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\HonorarioController;
use App\Http\Controllers\AudienciaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\TipoDocumentoController;

// Ruta principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de autenticación
Auth::routes();

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas para clientes
    Route::resource('client', ClientController::class);
    Route::get('/client/lista/getDataClientes', [ClientController::class, 'getDataClientes'])->name('client.getDataClientes');
    Route::post('/client/{client}/familia', [ClientController::class, 'addFamiliar'])->name('client.addFamiliar');
    Route::delete('/client/{client}/familia/{index}', [ClientController::class, 'removeFamiliar'])->name('client.removeFamiliar');

    // Rutas para expedientes
    Route::resource('expedientes', ExpedienteController::class);

    // Rutas para documentos
    Route::get('/client/{clientId}/documentos', [DocumentoController::class, 'index'])->name('client.documentos');
    Route::get('/client/{clientId}/documentos/list', [DocumentoController::class, 'getDocumentos'])->name('documentos.list');
    Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
    Route::put('/documentos/{id}', [DocumentoController::class, 'update'])->name('documentos.update');
    Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
    Route::post('/documentos/{id}/validar', [DocumentoController::class, 'validarDocumento'])->name('documentos.validar');
    Route::get('/tipos-documentos', [TipoDocumentoController::class, 'index'])->name('tipos-documentos.index');
    Route::get('/documentos/{id}/descargar', [DocumentoController::class, 'descargar'])->name('documentos.descargar');
    Route::get('/documentos/{id}/visualizar', [DocumentoController::class, 'visualizar'])->name('documentos.visualizar');
    Route::put('/documentos/{id}/estado', [DocumentoController::class, 'actualizarEstado']);
    Route::get('/user/role', function () {
        return response()->json(['role' => Auth::user()->role]);
    })->middleware('auth');
    

    // Rutas para honorarios
    Route::post('/expedientes/{expediente}/honorarios', [HonorarioController::class, 'store'])->name('honorarios.store');
    Route::put('/honorarios/{honorario}', [HonorarioController::class, 'update'])->name('honorarios.update');
    Route::post('/honorarios/{honorario}/abonos', [HonorarioController::class, 'registrarAbono'])->name('honorarios.abono');
    Route::get('/honorarios/{client}', [HonorarioController::class, 'show'])->name('honorarios.show');

    // Rutas para audiencias
    Route::post('/expedientes/{expediente}/audiencias', [AudienciaController::class, 'store'])->name('audiencias.store');
    Route::put('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'update'])->name('audiencias.update');
    Route::delete('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'destroy'])->name('audiencias.destroy');
    Route::get('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'show'])->name('audiencias.show');

    // Rutas para bitácoras
    Route::resource('expedientes.bitacoras', BitacoraController::class);
    Route::get('/bitacoras/{client}', [BitacoraController::class, 'index'])->name('bitacoras.index');
    Route::get('/client/{client}/bitacoras', [BitacoraController::class, 'index'])->name('client.bitacoras.index');
});

// Ruta de inicio después de autenticación
Route::get('/home', [HomeController::class, 'index'])->name('home');