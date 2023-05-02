<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\TipoProceso;

use App\Models\Proceso;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Abogado;
use App\Models\Tipopretencion;
use App\Models\Detallepretencion;
use App\Models\Detalleproceso;
use App\Models\Presupuesto;
use App\Models\Pretencion;
use App\Models\Juzgado;

use Illuminate\Support\Facades\DB;

class TramitesController extends Controller
{
    public function crearTipotramite(Request $request){
        $validator = \Validator::make($request->all(), [
            'nombre' => 'required|min:5|max:40',
            'descripcion'=>'required|min:10|max:60',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $tipoproceso= new TipoProceso();
            $tipoproceso->proceso = $request->nombre;
            $tipoproceso->descripcion = $request->descripcion;
            $tipoproceso->save();
            if( $tipoproceso->save() ){
                return response()->json([
                    'success' => true
                ]);
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }
    }

    public function actualizarTipotramite(Request $request){
        $validator = \Validator::make($request->all(), [
            'nombre' => 'required|min:5|max:40',
            'descripcion'=>'required|min:10|max:60',
            'id'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $tipoproceso=  TipoProceso::find($request->id);
            $tipoproceso->proceso = $request->nombre;
            $tipoproceso->descripcion = $request->descripcion;
            $tipoproceso->save();
            if( $tipoproceso->save() ){
                return response()->json([
                    'success' => true
                ]);
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }

    }
    public function eliminarTipotramite(Request $request){
        $validator = \Validator::make($request->all(), [
            'id'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $tipoproceso=  TipoProceso::find($request->id);
            $res = $tipoproceso->delete();
            if( $res ){
                return response()->json([
                    'success' => true
                ]);
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }

    }

    public function retornarTipoTramites(){
        $tramites = TipoProceso::all();
        return response()->json([
            'status' => 200,
            'tipotramites'=>$tramites
        ]);
    }

    public function retornarTipoPretencion(){
        $pretenciones = Tipopretencion::all();
        return response()->json([
            'status' => 200,
            'pretenciones'=>$pretenciones
        ]);
    }

    public function retornarTramites(){

        $procesos = Proceso::select('procesos.fecha', 'procesos.estado', 'procesos.hechosOcurridos',
            DB::raw("CONCAT(persona.nombre, ' ', persona.apellido1, ' ', persona.apellido2) AS abogado"),
            DB::raw("CONCAT(persona_c.nombre, ' ', persona_c.apellido1, ' ', persona_c.apellido2) AS cliente"))
        ->join('abogados', 'abogados.id', '=', 'procesos.abogado_id')
        ->join('persona', 'persona.id', '=', 'abogados.persona_id')
        ->join('clientes', 'clientes.id', '=', 'procesos.cliente_id')
        ->join('persona as persona_c', 'persona_c.id', '=', 'clientes.persona_id')
        ->get();

        return response()->json([
            'success' => true,
            'tramites'=>$procesos
        ]);
    }

    public function crearTramite( Request $request ){
        $validator  = Validator::make($request->all(), [
            'fecha' => 'required',
            'estado' => 'required',
            'hechosOcurridos' => 'required',
            'abogado_id' => 'required',
            'cliente_id' => 'required',
            'tipoproceso_id' => 'required',
            'valor_medida'=>'required',
            'tipopretencion_id'=>'required',
            'detallePretencionCliente'=>'required',
            'declaracionCliente'=>'required'
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $proceso = new Proceso();
            $proceso->fecha = $request->fecha;
            $proceso->estado = $request->estado;
            $proceso->hechosOcurridos = $request->hechosOcurridos;
            $proceso->abogado_id = $request->abogado_id;
            $proceso->cliente_id = $request->cliente_id;
            $proceso->tipoproceso_id = $request->tipoproceso_id;
            $proceso->juzgado_id = $request->juzgado_id;
            $proceso->save();

            if( $proceso->save() ){
                $detalleproceso = new Detalleproceso();
                $detalleproceso->declaracionDemandante=$request->declaracionCliente;
                $detalleproceso->declaracionDemandado = $request->declaracionDemandado;
                $detalleproceso->procesos_id = $proceso->id;
                $detalleproceso->save();

                $presupuesto = new Presupuesto();
                $presupuesto->asunto = $request->asunto;
                $presupuesto->monto = $request->monto;
                $presupuesto->fecha = $request->fecha;
                $presupuesto->estado = 1;
                $presupuesto->save();

                $pretencion = new Pretencion();
                $pretencion->proceso_id = $proceso->id;
                $pretencion->fecha = $request->fecha;
                $pretencion->valorMedida = $request->valor_medida;
                $pretencion->tipopretension_id = $request->tipopretencion_id;
                $pretencion->save();

                $detallepretencion = new Detallepretencion();
                $detallepretencion->detallePretencionDemandante = $request->detallePretencionCliente;
                $detallepretencion->detallePretencionDemandado = $request->detallePretencionDemandado;
                $detallepretencion->pretencion_id = $pretencion->id;
                $detallepretencion->save();

                return response()->json([ 'status'=>200, 'mensaje'=>'El trámite se ha registrado correctamente.' ]);

            }else{
                return response()->json([ 'status'=>500, 'errors'=>'Error al crear el proceso' ]);
            }

         }
    }

    public function retornarJuzgados(){
        $juzgado= Juzgado::all();
        $juzgado_datos =[];
        foreach ($juzgado as $key => $value) {
            $nombre = $value->nombre;
            $obj=[
                'id'  => $value->id,
                'nombre' => ''.$nombre,
            ];
            array_push( $juzgado_datos, $obj );
        }

        return response()->json([
            'status'=> 200 ,
            'juzgados'=>$juzgado_datos
        ]);

    }



}
