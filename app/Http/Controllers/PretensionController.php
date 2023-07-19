<?php

namespace App\Http\Controllers;

use App\Models\Pretencion;
use Illuminate\Http\Request;

class PretensionController extends Controller
{
    public function update(Request $request)
    {
        try {
            $pretensionID = $request->pretension_id;
            $fechaPretension = $request->fecha_pretension;
            $valorMedida = $request->valor_medida;
            $procesoID = $request->proceso_id;
            $tipoProcesoID = $request->tipo_proceso_id;

            if (!isset($pretensionID)) {
                return responseJson('Verifique los Parametros',  ['pretensionID'=> $pretensionID], 404);
            }

            $pretension = Pretencion::find($pretensionID);
            $pretension->fecha = $fechaPretension;
            $pretension->valorMedida = $valorMedida;
            $pretension->tipopretension_id = $tipoProcesoID;
            $pretension->proceso_id = $procesoID;
            $pretension->save();
            if ($pretension->save()) {
                return responseJson('Registrado Actualizado Exitosamente', $pretension, 200);
            } else {
                return responseJson('Error al Actualizar', $pretension, 400);
            }
        } catch (\Exception $e) {
            return responseJson('Server Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }
}
