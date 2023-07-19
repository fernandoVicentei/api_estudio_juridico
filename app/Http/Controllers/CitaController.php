<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        try {

            $direccionCita = $request->direccion_cita;
            $fechaCita = $request->fecha_cita;
            $asuntoCita = $request->asunto_cita;

            if (!isset($direccionCita) || !isset($fechaCita) || !isset($asuntoCita)) {
                return responseJson('Verifique los Parametros', ['direccionCita' => $direccionCita, 'fechaCita' => $fechaCita, 'asuntoCita' => $asuntoCita], 404);
            }

            $cita = new Citas();
            $cita->direccion = $direccionCita;
            $cita->fecha = $fechaCita;
            $cita->asunto = $asuntoCita;
            $cita->save();
            if ($cita->save()) {
                return responseJson('Registrado Exitosamente', $cita, 200);
            } else {
                return responseJson('Error al Registrar', $cita, 400);
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
            $citaID = $request->cita_id;
            $direccionCita = $request->direccion_cita;
            $fechaCita = $request->fecha_cita;
            $asuntoCita = $request->asunto_cita;

            if (!isset($citaID)) {
                return responseJson('Verifique los Parametros', $citaID, 404);
            }

            $cita = Citas::find($citaID);
            $cita->direccion = $direccionCita;
            $cita->fecha = $fechaCita;
            $cita->asunto = $asuntoCita;
            $cita->save();
            if ($cita->save()) {
                return responseJson('Registrado Actualizado Exitosamente', $cita, 200);
            } else {
                return responseJson('Error al Actualizar', $cita, 400);
            }
        } catch (\Exception $e) {
            return responseJson('Server Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }

    public function filtrarCitasAbogado(Request $request)
    {
        try {
            $fechaCita = $request->fecha_cita;
            $abogadoID = $request->abogado_id;
            $fechActual = Carbon::now()->toDateTimeString();

            if (!isset($abogadoID)) {
                return responseJson('Verifique los Parametros', ['abogadoID' => $abogadoID], 404);
            }

            $citas = Citas::with('proceso')
                ->whereHas('proceso', function ($q) {
                    $q->where('abogado_id', $abogadoID);
                })
                ->where('fecha', Carbon::parse($fechaCita ? $fechaCita : $fechActual)->toDateString())
                ->get();

            return count($citas) > 0 ? responseJson('Listado Citas', $citas, 200) : responseJson('No se Encontraron Citas', $citas, 400);
        } catch (\Exception $e) {
            return responseJson('Server Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }
}
