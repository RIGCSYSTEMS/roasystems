<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DocumentoExpedienteController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\HonorarioController;
use App\Http\Controllers\AudienciaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\SearchClientController;
use App\Http\Controllers\SearchExpedientController;
use App\Http\Controllers\TipoDocumentoExpedienteController;


// Ruta principal
Route::get('/', [HomeController::class, 'index'])->name('home');

    // acceso denegado
    Route::get('/acceso-denegado', function () {
        return view('NoAccess.index');
    })->name('acceso-denegado');




// Rutas de autenticación
Auth::routes();
Route::get('prueba', function(){
    return"hola";
});


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas accesibles para todos los usuarios autenticados

    Route::get('/user/role', function () {
        return response()->json(['role' => Auth::user()->role]);
    });




    // Rutas para clientes y personal del despacho
    Route::middleware(['roleGlobal', 'NoAccess'])->group(function () {
        
        //clientes
        Route::resource('client', ClientController::class);
        Route::post('/client/{client}/familia', [ClientController::class, 'addFamiliar'])->name('client.addFamiliar');
        Route::delete('/client/{client}/familia/{index}', [ClientController::class, 'removeFamiliar'])->name('client.removeFamiliar');

        //documentos cliente
        Route::resource('documentos', DocumentoController::class);
        Route::post('/documentos/{id}/validar', [DocumentoController::class, 'validarDocumento'])->name('documentos.validar');
        Route::put('/documentos/{id}/estado', [DocumentoController::class, 'actualizarEstado'])->name('documentos.actualizarEstado');
        Route::get('/tipos-documentos', [TipoDocumentoController::class, 'index'])->name('tipos-documentos.index');
        Route::get('/documentos/{id}/descargar', [DocumentoController::class, 'descargar'])->name('documentos.descargar');
        Route::get('/documentos/{id}/visualizar', [DocumentoController::class, 'visualizar'])->name('documentos.visualizar');
        Route::get('/client/{clientId}/documentos', [DocumentoController::class, 'index'])->name('client.documentos');
        Route::get('/client/{clientId}/documentos/list', [DocumentoController::class, 'getDocumentos'])->name('documentos.list');
        Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
        Route::put('/documentos/{id}', [DocumentoController::class, 'update'])->name('documentos.update');
        Route::delete('/documentos/{id}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');
        Route::put('/documentos/{id}/estado', [DocumentoController::class, 'actualizarEstado']);

        //documentos expedientes
        Route::resource('documentosexp', DocumentoExpedienteController::class);
        Route::post('/documentosexp/{id}/validar', [DocumentoExpedienteController::class, 'validarDocumento'])->name('documentosexp.validar');
        Route::put('/documentosexp/{id}/estado', [DocumentoExpedienteController::class, 'actualizarEstado'])->name('documentosexp.actualizarEstado');
        Route::get('/documentosexp/{id}/descargar', [DocumentoExpedienteController::class, 'descargar'])->name('documentosexp.descargar');
        Route::get('/documentosexp/{id}/visualizar', [DocumentoExpedienteController::class, 'visualizar'])->name('documentosexp.visualizar');
        Route::get('/expedientes/{expedienteId}/documentos', [DocumentoExpedienteController::class, 'index'])->name('expedientesexp.documentos');
        Route::get('/expedientes/{expedienteId}/documentos/list', [DocumentoExpedienteController::class, 'getDocumentos'])->name('documentosexp.list');
        Route::post('/documentosexp', [DocumentoExpedienteController::class, 'store'])->name('documentosexp.store');
        Route::put('/documentosexp/{id}', [DocumentoExpedienteController::class, 'update'])->name('documentosexp.update');
        Route::delete('/documentosexp/{id}', [DocumentoExpedienteController::class, 'destroy'])->name('documentosexp.destroy');
        Route::put('/documentosexp/{id}/estado', [DocumentoExpedienteController::class, 'actualizarEstado']);
        Route::get('/tipos-documentos-exp', [TipoDocumentoExpedienteController::class, 'index'])->name('tipos-documentos-exp.index');
 
        
    });

    // Rutas solo para abogados, directores y administradores
    Route::middleware(['role'])->group(function () {

        //busquedas
        Route::get('searchClient',[SearchClientController::class, 'index'])->name('searchClient.index');
        Route::get('/searchClient/lista/getDataClientes', [SearchClientController::class, 'getDataClientes'])->name('searchClient.getDataClientes');
        Route::delete('/client/{clientId}', [SearchClientController::class, 'destroy'])->name('client.destroy');

        Route::get('searchExpedient',[SearchExpedientController::class, 'index'])->name('searchExpedient.index');
        Route::get('/searchExpedient/lista/getDataExpedient', [SearchExpedientController::class, 'getDataExpedient'])->name('searchExpedient.getDataExpedient');
        Route::delete('/expedientes/{expedientesId}', [SearchExpedientController::class, 'destroy'])->name('expedient.destroy');

        




        Route::resource('expedientes', ExpedienteController::class);
        Route::get('/expedientes/{id}', [ExpedienteController::class, 'show'])->name('expedientes.show');
        Route::put('/expedientes/{expediente}/estado', [ExpedienteController::class, 'updateStatus'])->name('expedientes.updateStatus');


// Rutas para honorarios
Route::get('/expedientes/{expediente}/honorarios', [HonorarioController::class, 'getHonorariosByExpediente']);
Route::post('/honorarios', [HonorarioController::class, 'store']);
Route::put('/honorarios/{honorario}', [HonorarioController::class, 'update']);

// Rutas para abonos
Route::get('/honorarios/{honorario}/abonos', [HonorarioController::class, 'getAbonos']);
Route::post('/honorarios/abonos', [HonorarioController::class, 'storeAbono']);
Route::put('/honorarios/abonos/{abono}', [HonorarioController::class, 'updateAbono']);

// Rutas para extras
Route::get('/honorarios/{honorario}/extras', [HonorarioController::class, 'getExtras']);
Route::post('/honorarios/extras', [HonorarioController::class, 'storeExtra']);
// Agregar estas rutas en web.php
Route::delete('/honorarios/abonos/{abono}', [HonorarioController::class, 'destroyAbono']);
Route::delete('/honorarios/extras/{extra}', [HonorarioController::class, 'destroyExtra']);


        Route::get('/honorarios', [HonorarioController::class, 'index'])->name('honorarios.index');
Route::get('/honorarios/{expediente_id}', [HonorarioController::class, 'show'])->name('honorarios.show');
Route::post('/honorarios', [HonorarioController::class, 'store'])->name('honorarios.store');
Route::get('/honorarios/{honorario}/data', [HonorarioController::class, 'getHonorarioData'])->name('honorarios.data');
Route::post('/honorarios/{honorario}/abonos', [HonorarioController::class, 'storeAbono'])->name('honorarios.abonos.store');
Route::post('/honorarios/{honorario}/extras', [HonorarioController::class, 'storeExtra'])->name('honorarios.extras.store');
Route::put('/honorarios/{honorario}/abonos/{abono}', [HonorarioController::class, 'updateAbono'])->name('honorarios.abonos.update');

        Route::post('/expedientes/{expediente}/honorarios', [HonorarioController::class, 'store'])->name('honorarios.store');
        Route::put('/honorarios/{honorario}', [HonorarioController::class, 'update'])->name('honorarios.update');
        Route::post('/honorarios/{honorario}/abonos', [HonorarioController::class, 'registrarAbono'])->name('honorarios.abono');
        Route::get('/honorarios/{client}', [HonorarioController::class, 'show'])->name('honorarios.show');

        Route::post('/expedientes/{expediente}/audiencias', [AudienciaController::class, 'store'])->name('audiencias.store');
        Route::put('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'update'])->name('audiencias.update');
        Route::delete('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'destroy'])->name('audiencias.destroy');
        Route::get('/expedientes/{expediente}/audiencias/{audiencia}', [AudienciaController::class, 'show'])->name('audiencias.show');

        Route::resource('expedientes.bitacoras', BitacoraController::class);
        Route::get('/bitacoras/{client}', [BitacoraController::class, 'index'])->name('bitacoras.index');
        Route::get('/client/{client}/bitacoras', [BitacoraController::class, 'index'])->name('client.bitacoras.index');
    });
});

// Ruta de inicio después de autenticación
// Route::get('/home', [HomeController::class, 'index'])->name('home');



// Route::middleware('role:ADMIN')->group(function () {
//     Route::get('/admin', function () {
//         return view('search.searchClient');
//     });
// });

