<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DatosPersonales;
use App\Http\Controllers\PretensionController;
use App\Http\Controllers\TipoProcesoController;
use App\Http\Controllers\TramitesController;
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

//AUTHENTICATION
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
});

//CLIENTES
Route::group(
    [
        'prefix' => 'clientes',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => DatosPersonales::class,
    ],
    function () {
        Route::post('agregar', 'store');
        Route::post('actualizar', 'actualizarCliente');
        Route::post('eliminar', 'eliminarCliente');
        Route::post('buscar', 'buscarCliente');
        Route::post('retornarclientes', 'retornarClientes');
        Route::post('retornarclientesbasico', 'retornarClientesBasico');
    }
);

// ABOGADOS
Route::group(
    [
        'prefix' => 'abogados',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => DatosPersonales::class,
    ],
    function () {
        Route::post('agregar', 'agregarAbogado');
        Route::post('actualizar', 'actualizarAbogado');
        Route::post('eliminar', 'eliminarAbogado');
        Route::post('buscar', 'buscarCliente');
        Route::post('retornarAbogados', 'retornarAbogados');
        Route::post('retornarabogadosbasico', 'retornarabogadosbasico');
    }
);

//TIPO TRAMITES
Route::group(
    [
        'prefix' => 'tipotramites',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => TramitesController::class,
    ],
    function () {
        Route::post('agregar', 'crearTipotramite');
        Route::post('actualizar', 'actualizarTipotramite');
        Route::post('eliminar', 'eliminarTipotramite');
        Route::post('retornartipotramite', 'retornarTipoTramites');
        Route::post('retornartipopretenciones', 'retornarTipoPretencion');
    }
);

//TRAMITES
Route::group(
    [
        'prefix' => 'tramite',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => TramitesController::class,
    ],
    function () {
        Route::post('agregar', 'crearTramite');
        Route::post('editar', 'editarTramite');
        Route::post('listar', 'retornarTramites');
        Route::post('buscar', 'buscarTramite');
    }
);

//JUZGADOS
Route::group(
    [
        'prefix' => 'juzgado',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => TramitesController::class,
    ],
    function () {
        Route::post('retornarjuzgados', 'retornarJuzgados');
        Route::post('actualizar', 'editarJuzgados');
        Route::post('agregar', 'agregarJuzgados');
        Route::post('retornarjuzgadobasico', 'retornarJuzgados');
        Route::post('pretencion/buscar', 'buscarPretenciones');
        Route::post('detalle/buscar', 'buscarDetalleProceso');
    }
);

//TIPOS DE PROCESOS
Route::group(
    [
        'prefix' => 'tiposProcesos',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => TipoProcesoController::class,
    ],
    function () {
        Route::post('store', 'store');
        Route::post('update', 'update');
    }
);

//CITAS
Route::group(
    [
        'prefix' => 'citas',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => CitaController::class,
    ],
    function () {
        Route::post('store', 'store');
        Route::post('update', 'update');
        Route::post('filtrarCitasAbogado', 'filtrarCitasAbogado');
    }
);

//PRETENCIONES
Route::group(
    [
        'prefix' => 'pretensiones',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => PretensionController::class,
    ],
    function () {
        Route::post('update', 'update');
    }
);
