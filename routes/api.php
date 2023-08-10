<?php

use App\Http\Controllers\ArchivosController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DatosPersonales;
use App\Http\Controllers\JuzgadoController;
use App\Http\Controllers\PretensionController;
use App\Http\Controllers\TipoProcesoController;
use App\Http\Controllers\TramitesController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;

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
        Route::post('agregar', 'store')
            ->middleware('permission:clientes.agregar');
        Route::post('actualizar', 'actualizarCliente')
            ->middleware('permission:clientes.actualizar');
        Route::post('eliminar', 'eliminarCliente')
            ->middleware('permission:clientes.eliminar');
        Route::post('buscar', 'buscarCliente')
            ->middleware('permission:clientes.buscar');
        Route::post('retornarclientes', 'retornarClientes')
            ->middleware('permission:clientes.retornarclientes');
        Route::post('retornarclientesbasico', 'retornarClientesBasico')
            ->middleware('permission:clientes.retornarclientesbasico');
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
        Route::post('agregar', 'agregarAbogado')
            ->middleware('permission:abogados.agregar');
        Route::post('actualizar', 'actualizarAbogado')
            ->middleware('permission:abogados.actualizar');
        Route::post('eliminar', 'eliminarAbogado')
            ->middleware('permission:abogados.eliminar');
        Route::post('buscar', 'buscarCliente')
            ->middleware('permission:abogados.buscar');
        Route::post('retornarAbogados', 'retornarAbogados')
            ->middleware('permission:abogados.retornarAbogados');
        Route::post('retornarAbogadosBasicos', 'retornarabogadosbasico')
            ->middleware('permission:abogados.retornarAbogadosBasicos');
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
        Route::post('agregar', 'crearTipotramite')
            ->middleware('permission:tipotramite.agregar');
        Route::post('actualizar', 'actualizarTipotramite')
            ->middleware('permission:tipotramite.actualizar');
        Route::post('eliminar', 'eliminarTipotramite')
            ->middleware('permission:tipotramite.eliminar');
        Route::post('retornartipotramite', 'retornarTipoTramites')
            ->middleware('permission:tipotramite.retornartipotramite');
        Route::post('pretenciones/retornartipopretenciones', 'retornarTipoPretencion')
            ->middleware('permission:tipotramite.retornartipopretenciones');
    }
);

//TRAMITES
Route::group(
    [
        'prefix' => 'tramites',
        'middleware' => 'auth:sanctum',
        'controller' => TramitesController::class,
    ],
    function () {
        Route::post('agregar', 'crearTramite')
            ->middleware('permission:tramites.agregar');
        Route::post('editar', 'editarTramite')
            ->middleware('permission:tramites.editar');
        Route::post('listar', 'retornarTramites')
            ->middleware('permission:tramites.retornarTramites');
        Route::post('buscar', 'buscarTramite')
            ->middleware('permission:tramites.buscar');
    }
);

//JUZGADOS
Route::group(
    [
        'prefix' => 'juzgado',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => JuzgadoController::class,
    ],
    function () {
        Route::post('retornarjuzgados', 'retornarJuzgados')
            ->middleware('permission:juzgados.agregar');
        Route::post('actualizar', 'editarJuzgados')
            ->middleware('permission:juzgados.actualizar');
        Route::post('agregar', 'agregarJuzgados')
            ->middleware('permission:juzgados.agregar');
        Route::post('retornarjuzgadobasico', 'retornarJuzgados')
            ->middleware('permission:juzgados.retornarjuzgadobasico');
        Route::post('pretencion/buscar', 'buscarPretenciones')
            ->middleware('permission:juzgados.pretencionbuscar');
        Route::post('detalle/buscar', 'buscarDetalleProceso')
            ->middleware('permission:juzgados.detallebuscar');
        Route::post('buscar','buscarJuzgado');
        Route::post('eliminar', 'eliminarJuzgado' );

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
        Route::post('store', 'store')
            ->middleware('permission:tiposProcesos.store');
        Route::post('update', 'update')
            ->middleware('permission:tiposProcesos.update');
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
            /* ->middleware('citas.store'); */
        Route::post('update', 'update');
            /* ->middleware('citas.update'); */
        Route::post('filtrarCitasAbogado', 'filtrarCitasAbogado');
            /* ->middleware('citas.filtrarCitasAbogado'); */
        Route::post('retornar','retornarCitas');
        Route::post('tramites','retornarProcesos');
        Route::post('buscar','buscarCita');
        Route::post('eliminar','EliminarCita');
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
        Route::post('update', 'update')
            ->middleware('permission:pretensiones.update');
    }
);

//ARCHIVOS
Route::group(
    [
        'prefix' => 'archivos',
        /* 'middleware' => 'auth:sanctum', */
        'controller' => ArchivosController::class,
    ],
    function () {
        Route::post('procesos','index');
        Route::post('retornarproceso','retornarProceso');
        Route::post('guardar','store');
        Route::post('documentos','retornarTipoDoc');
        Route::post('eliminar','eliminarDocumentos');
        Route::get('descargar/{id}','descargar');

    }
);
