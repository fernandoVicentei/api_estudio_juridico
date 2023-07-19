<?php

namespace App\Http\Controllers;

use App\Models\TipoProceso;
use Illuminate\Http\Request;

class TipoProcesoController extends Controller
{
    public function store(Request $request)
    {
        try {

            $procesoTramite = $request->proceso;
            $descripcionTramite = $request->descripcion_tramite;
            $precioTramite = $request->precio_tramite;

            if (!isset($procesoTramite) || !isset($descripcionTramite) || !isset($precioTramite)) {
                return responseJson('Verifique los Parametros', ['precioTramite' => $procesoTramite, 'descripcionTramite' => $descripcionTramite, 'precioTramite' => $precioTramite], 404);
            }

            $tipoProceso = new TipoProceso();
            $tipoProceso->proceso = $procesoTramite;
            $tipoProceso->descripcion = $descripcionTramite;
            $tipoProceso->precioinicial = $precioTramite;
            $tipoProceso->save();
            if ($tipoProceso->save()) {
                return responseJson('Registrado Exitosamente', $tipoProceso, 200);
            } else {
                return responseJson('Error al Registrar', $tipoProceso, 400);
            }
        } catch (\Exception $e) {
            return responseJson('Server Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $tipoProcesoID = $request->tipo_proceso_id;
            $procesoTramite = $request->proceso;
            $descripcionTramite = $request->descripcion_tramite;
            $precioTramite = $request->precio_tramite;

            if (!isset($tipoProcesoID)) {
                return responseJson('Verifique los Parametros', $tipoProcesoID, 404);
            }

            $tipoProceso = TipoProceso::find($tipoProcesoID);
            $tipoProceso->proceso = $procesoTramite;
            $tipoProceso->descripcion = $descripcionTramite;
            $tipoProceso->precioinicial = $precioTramite;
            $tipoProceso->save();
            if ($tipoProceso->save()) {
                return responseJson('Registrado Actualizado Exitosamente', $tipoProceso, 200);
            } else {
                return responseJson('Error al Actualizar', $tipoProceso, 400);
            }

        } catch (\Exception $e) {
            return responseJson('Server Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }


}
