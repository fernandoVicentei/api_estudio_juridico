<?php

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

Route::post('/clientes/agregar', [App\Http\Controllers\DatosPersonales::class, 'agregarCliente']);
Route::post('/clientes/actualizar', [App\Http\Controllers\DatosPersonales::class, 'actualizarCliente']);
Route::post('/clientes/eliminar', [App\Http\Controllers\DatosPersonales::class, 'eliminarCliente']);

Route::post('/abogados/agregar', [App\Http\Controllers\DatosPersonales::class, 'agregarAbogado']);
Route::post('/abogados/actualizar', [App\Http\Controllers\DatosPersonales::class, 'actualizarAbogado']);
Route::post('/abogados/eliminar', [App\Http\Controllers\DatosPersonales::class, 'eliminarAbogado']);


Route::post('/tipotramite/agregar', [App\Http\Controllers\TramitesController::class, 'crearTipotramite']);
Route::post('/tipotramite/actualizar', [App\Http\Controllers\TramitesController::class, 'actualizarTipotramite']);
Route::post('/tipotramite/eliminar', [App\Http\Controllers\TramitesController::class, 'eliminarTipotramite']);


Route::post('/login', [App\Http\Controllers\LoginController::class, 'verificarCredencial']);

Route::post('/clientes/retornarclientes', [App\Http\Controllers\DatosPersonales::class, 'retornarClientes']);
Route::post('/abogados/retornarabogados', [App\Http\Controllers\DatosPersonales::class, 'retornarAbogados']);
Route::post('/tipotramites/retornartipotramite', [App\Http\Controllers\TramitesController::class, 'retornarTipoTramites']);

Route::post('/tramite/listar', [App\Http\Controllers\TramitesController::class, 'retornarTramites']);
