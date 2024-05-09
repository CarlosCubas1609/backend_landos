<?php

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\OrdenController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\TipoDocumentoController;
use App\Http\Controllers\Api\TipoPagoController;
use App\Http\Controllers\Api\TipoServicioController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehiculoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ruta', function() {
    
});

Route::prefix('/users')->group(function () {
    Route::get('/listar', [UserController::class, 'listar']);
    Route::get('/getUser/{id}', [UserController::class, 'getUser']);
    Route::get('/listarPermisos/{id}', [UserController::class, 'listarPermisos']);
    Route::post('/store', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'destroy']);
});

Route::prefix('/roles')->group(function () {
    Route::get('/listar', [RoleController::class, 'listar']);
    Route::post('/store', [RoleController::class, 'store']);
    Route::put('/update/{id}', [RoleController::class, 'update']);
    Route::delete('/delete/{id}', [RoleController::class, 'destroy']);
});

Route::prefix('/permissions')->group(function () {
    Route::get('/listar/{id}', [PermissionController::class, 'listar']);
    Route::post('/store', [PermissionController::class, 'store']);
    Route::put('/update/{id}', [PermissionController::class, 'update']);
    Route::delete('/delete/{id}', [PermissionController::class, 'destroy']);
});

Route::prefix('/clientes')->group(function () {
    Route::get('/listar', [ClienteController::class, 'listar']);
    Route::post('/store', [ClienteController::class, 'store']);
    Route::put('/update/{id}', [ClienteController::class, 'update']);
    Route::delete('/delete/{id}', [ClienteController::class, 'destroy']);
});

Route::prefix('/generos')->group(function () {
    Route::get('/listar', [GeneroController::class, 'listar']);
});

Route::prefix('/tipo_documentos')->group(function () {
    Route::get('/listar', [TipoDocumentoController::class, 'listar']);
});

Route::prefix('/tipospago')->group(function () {
    Route::get('/listar', [TipoPagoController::class, 'listar']);
});

Route::prefix('/vehiculos')->group(function () {
    Route::get('/listar/{id}', [VehiculoController::class, 'listar']);
    Route::get('/vehiculo/{placa}', [VehiculoController::class, 'vehiculo']);
    Route::post('/store', [VehiculoController::class, 'store']);
    Route::put('/update/{id}', [VehiculoController::class, 'update']);
    Route::delete('/delete/{id}', [VehiculoController::class, 'destroy']);
});

Route::prefix('/servicios')->group(function () {
    Route::get('/listar', [ServicioController::class, 'listar']);
    Route::post('/store', [ServicioController::class, 'store']);
    Route::put('/update/{id}', [ServicioController::class, 'update']);
    Route::delete('/delete/{id}', [ServicioController::class, 'destroy']);
});

Route::prefix('/tiposservicio')->group(function () {
    Route::get('/listar', [TipoServicioController::class, 'listar']);
});

Route::prefix('/reportes')->group(function () {
    Route::get('/cajaDiaria/{fecha}', [ReporteController::class, 'cajaDiaria']);
});

Route::prefix('/ordenes')->group(function () {
    Route::get('/listar', [OrdenController::class, 'listar']);
    Route::post('/store', [OrdenController::class, 'store']);
    Route::put('/update/{id}', [OrdenController::class, 'update']);
    Route::delete('/delete/{id}', [OrdenController::class, 'destroy']);
    Route::post('/storePago', [OrdenController::class, 'storePago']);
});

