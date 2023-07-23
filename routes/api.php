<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PretensionController;
use App\Http\Controllers\TipoProcesoController;
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

//CLIENTES
Route::post('/clientes/agregar', [App\Http\Controllers\DatosPersonales::class, 'agregarCliente']);
Route::post('/clientes/actualizar', [App\Http\Controllers\DatosPersonales::class, 'actualizarCliente']);
Route::post('/clientes/eliminar', [App\Http\Controllers\DatosPersonales::class, 'eliminarCliente']);
Route::post('/clientes/buscar', [App\Http\Controllers\DatosPersonales::class, 'buscarCliente']);


// ABOGADOS
Route::post('/abogados/agregar', [App\Http\Controllers\DatosPersonales::class, 'agregarAbogado']);
Route::post('/abogados/actualizar', [App\Http\Controllers\DatosPersonales::class, 'actualizarAbogado']);
Route::post('/abogados/eliminar', [App\Http\Controllers\DatosPersonales::class, 'eliminarAbogado']);
Route::post('/abogados/buscar', [App\Http\Controllers\DatosPersonales::class, 'buscarAbogado']);

//TIPO TRAMITES

Route::post('/tipotramite/agregar', [App\Http\Controllers\TramitesController::class, 'crearTipotramite']);
Route::post('/tipotramite/actualizar', [App\Http\Controllers\TramitesController::class, 'actualizarTipotramite']);
Route::post('/tipotramite/eliminar', [App\Http\Controllers\TramitesController::class, 'eliminarTipotramite']);

Route::post('/login', [App\Http\Controllers\LoginController::class, 'verificarCredencial']);

//RETORNO
Route::post('/clientes/retornarclientes', [App\Http\Controllers\DatosPersonales::class, 'retornarClientes']);
Route::post('/abogados/retornarabogados', [App\Http\Controllers\DatosPersonales::class, 'retornarAbogados']);
Route::post('/tipotramites/retornartipotramite', [App\Http\Controllers\TramitesController::class, 'retornarTipoTramites']);
Route::post('/clientes/retornarclientesbasico', [App\Http\Controllers\DatosPersonales::class, 'retornarClientesBasico']);
Route::post('/abogados/retornarabogadosbasico', [App\Http\Controllers\DatosPersonales::class, 'retornarAbogadosBasico']);
Route::post('/juzgado/retornarjuzgadobasico', [App\Http\Controllers\TramitesController::class, 'retornarJuzgados']);
Route::post('/pretenciones/retornartipopretenciones', [App\Http\Controllers\TramitesController::class, 'retornarTipoPretencion']);

//TRAMITES
Route::post('/tramite/agregar', [App\Http\Controllers\TramitesController::class, 'crearTramite']);
Route::post('/tramite/editar', [App\Http\Controllers\TramitesController::class, 'editarTramite']);
Route::post('/tramite/listar', [App\Http\Controllers\TramitesController::class, 'retornarTramites']);
Route::post('/tramite/buscar', [App\Http\Controllers\TramitesController::class, 'buscarTramite']);
Route::post('/pretencion/buscar', [App\Http\Controllers\TramitesController::class, 'buscarPretenciones']);
Route::post('/detalle/buscar', [App\Http\Controllers\TramitesController::class, 'buscarDetalleProceso']);

//JUZGADOS
Route::post('/juzgado/retornarjuzgados', [App\Http\Controllers\JuzgadoController::class, 'retornarJuzgados']);
Route::post('/juzgado/actualizar', [App\Http\Controllers\JuzgadoController::class, 'editarJuzgados']);
Route::post('/juzgado/agregar', [App\Http\Controllers\JuzgadoController::class, 'agregarJuzgados']);


Route::controller(TipoProcesoController::class)->group(function () {
    Route::post('tiposProcesos/store','store');
    Route::post('tiposProcesos/update','update');
});

Route::controller(CitaController::class)->group(function () {
    Route::post('citas/store','store');
    Route::post('citas/update','update');
    Route::post('citas/filtrarCitasAbogado','filtrarCitasAbogado');
});

Route::controller(PretensionController::class)->group(function () {
    Route::post('pretensiones/update','update');
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
});
