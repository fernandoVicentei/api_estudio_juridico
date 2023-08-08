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
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class TramitesController extends Controller
{

    // ESTADO 0: CERRADO 1:EN CURSO 2:FINALIZADO
    public function crearTipotramite(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:5|max:40',
            'descripcion'=>'required|min:10|max:60',
            'precio'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $tipoproceso= new TipoProceso();
            $tipoproceso->proceso = $request->nombre;
            $tipoproceso->descripcion = $request->descripcion;
            $tipoproceso->precioinicial = $request->precio;
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

    public function actualizarTipotramite(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:5|max:40',
            'descripcion'=>'required|min:10|max:60',
            'precio'=>'required',
            'id'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{

            $tipoproceso=  TipoProceso::find($request->id);
            $tipoproceso->proceso = $request->nombre;
            $tipoproceso->descripcion = $request->descripcion;
            $tipoproceso->precioinicial = $request->precio;
            $tipoproceso->estado = 1;
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
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $mensaje ='';
            $procesos = Proceso::where('tipoproceso_id', $request->id )->count();
            if( $procesos>0 ){
                $tipoproceso=  TipoProceso::find($request->id);
                $tipoproceso->estado = 0;
                $tipoproceso->save();
                $mensaje = 'El registro no se eliminó porque ya forma parte de otros trámites en proceso, pero se deshabilitó para futuros trámites.';
            }else{
                $tipoproceso=  TipoProceso::find($request->id);
                $res = $tipoproceso->delete();
                $mensaje = "El registro se elimino correctamente.";
            }

            return response()->json([
                'success' => true,
                'mensaje' => $mensaje
            ]);


        }

    }

    public function retornarTipoTramites(){
        $tramites = TipoProceso::orderBy('id','desc')->get();
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

    public function retornarTramites()
    {
        $procesos = Proceso::select('procesos.fecha', 'procesos.estado', 'procesos.hechosOcurridos',
            DB::raw("CONCAT(persona.nombre, ' ', persona.apellido1, ' ', persona.apellido2) AS abogado"),
            DB::raw("CONCAT(persona_c.nombre, ' ', persona_c.apellido1, ' ', persona_c.apellido2) AS cliente"),
            'procesos.id'
        )
        ->join('abogados', 'abogados.id', '=', 'procesos.abogado_id')
        ->join('persona', 'persona.id', '=', 'abogados.persona_id')
        ->join('clientes', 'clientes.id', '=', 'procesos.cliente_id')
        ->join('persona as persona_c', 'persona_c.id', '=', 'clientes.persona_id')
        ->orderBy('procesos.id', 'DESC')
        ->get();

        return response()->json([
            'success' => true,
            'tramites'=>$procesos
        ]);
    }

    public function crearTramite( Request $request ){
        $validator  = Validator::make($request->all(), [
            'fecha' => 'required',
            'fechaSucesos'=>'required',
            'estado' => 'required',
            'hechosOcurridos' => 'required|min:15',
            'abogado_id' => 'required',
            'cliente_id' => 'required',
            'tipoproceso_id' => 'required',
            'valor_medida'=>'required',
            'tipopretencion_id'=>'required',
            'detallePretencionCliente'=>'required|min:20',
            'declaracionCliente'=>'required|min:30|max:300',
            'asunto'=>'max:200',
            'juzgado_id'=>'required'
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $proceso = new Proceso();
            $proceso->fecha = $request->fecha;
            $proceso->fechaSucesos = $request->fechaSucesos;
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
                $presupuesto->procesos_id = $proceso->id;
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

    public function buscarTramite(Request $request){

         $validator  = Validator::make($request->all(), [
            'idTramite' => 'required',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $pretencionCliente='';
            $pretencionDemandado = "";
            $tipopretencion=null;
            $valorMedida = null;
            $proceso = Proceso::find($request->idTramite);
            $pretenciones = Pretencion::where('proceso_id', $request->idTramite)->first();
            $presupuestoInicial = Presupuesto::where('procesos_id',$request->idTramite )->first();
            $detalle = Detalleproceso::where('procesos_id',$request->idTramite )->first();

            $fechaRegistro = Carbon::parse($proceso->fecha)->format('Y-m-d');
            $fechaSucesos =Carbon::parse( $proceso->fechaSucesos  )->format('Y-m-d');

            if( isset( $pretenciones ) ){
                $detallepretencion = Detallepretencion::where('pretencion_id', $pretenciones->id)->first();
                $pretencionCliente =  $detallepretencion->detallePretencionDemandante;
                $pretencionDemandado =  $detallepretencion->detallePretencionDemandado;
                $tipopretencion = $pretenciones->tipopretension_id;
                $valorMedida = $pretenciones->valorMedida;
            }
            $proceso_R=[
                'id'=>$proceso->id,
                'fecha'=> $fechaRegistro ,
                'fechaSuceso'=> $fechaSucesos ,
                'estado'=>$proceso->estado,
                'hechosOcurridos'=>$proceso->hechosOcurridos,
                'abogado_id'=>$proceso->abogado_id,
                'cliente_id'=>$proceso->cliente_id,
                'tipoproceso_id'=>$proceso->tipoproceso_id,
                'juzgado_id'=>$proceso->juzgado_id,
                'tipopretencion_id'=> $tipopretencion ,
                'asunto'=>$presupuestoInicial->asunto,
                'monto'=>$presupuestoInicial->monto,
                'valorMedida'=> $valorMedida,
                'detalleCliente'=>$detalle->declaracionDemandante,
                'detalleDemandado'=>$detalle->declaracionDemandado,
                'detallePretencionDemandante'=> $pretencionCliente,
                'detallePretencionDemandado'=> $pretencionDemandado,
            ];
            return response()->json([
                'status' => 200,
                'tramite'=>$proceso_R
            ]);
         }

    }

    public function buscarTipoTramite(Request $request){
        $validator  = Validator::make($request->all(), [
           'idTipoTramite' => 'required',
        ]);

        if( $validator->fails() ){
           return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{

            $ttramite =  TipoProceso::find( $request->idTipoTramite );

            return response()->json([
               'status' => 200,
               'tipotramite'=>$ttramite
           ]);
        }

   }

    public function buscarPretenciones(Request $request){
        $idTramite = $request->idTramite;

        $pretenciones = Pretencion::where('proceso_id', $idTramite)->first();
        if(  isset($pretenciones) ){
            $detallepretencion = Detallepretencion::where('pretencion_id', $pretenciones->id)->first();
            if(  isset($detallepretencion) ){
                return response()->json([
                    'status' => 200,
                    'tipopretencion_id'=> $pretenciones->tipopretension_id,
                    'valorMedida'=> $pretenciones->valorMedida,
                    'detallePretencionDemandante'=> $detallepretencion->detallePretencionDemandante,
                    'detallePretencionDemandado'=> $detallepretencion->detallePretencionDemandado,
                ]);

            }else{
                return response()->json([
                    'status' => 200,
                    'tipopretencion_id'=> $pretenciones->tipopretension_id,
                    'valorMedida'=> $pretenciones->valorMedida,
                    'detallePretencionDemandante'=> null,
                    'detallePretencionDemandado'=> null,
                ]);
            }

        }else{
            return response()->json([
                'status' => 200,
                'tipopretencion_id'=> null,
                'valorMedida'=> null,
                'detallePretencionDemandante'=> null,
                'detallePretencionDemandado'=> null,
            ]);
        }

    }

    public function buscarDetalleProceso(Request $request){
        $idTramite = $request->idTramite;
        $detalle = Detalleproceso::where('procesos_id',$idTramite)->first();
        $presupuestoIncial = Presupuesto::where('procesos_id',$idTramite)->first();

        if( isset($presupuestoIncial) ){
            $detalleT = [
                'status'=>200,
                'detalleCliente'=>$detalle->declaracionDemandante,
                'detalleDemandado'=>$detalle->declaracionDemandado,
                'presupuestoinicial'=>$presupuestoIncial->monto ,
            ];

        }else{
            $detalleT = [
                'status'=>200,
                'detalleCliente'=>$detalle->declaracionDemandante,
                'detalleDemandado'=>$detalle->declaracionDemandado,
                'presupuestoinicial'=>0 ,
            ];
        }
        return response()->json( $detalleT );
    }

    public function editarTramite( Request $request ){

        $validator  = Validator::make($request->all(),
            [
            'idTramite'=>'required',
            'fecha' => 'required',
            'fechaSucesos'=>'required',
            'estado' => 'required',
            'hechosOcurridos' => 'required|min:15',
            'abogado_id' => 'required',
            'cliente_id' => 'required',
            'tipoproceso_id' => 'required',
            'valor_medida'=>'required',
            'tipopretencion_id'=>'required',
            'detallePretencionCliente'=>'required|min:20',
            'declaracionCliente'=>'required|min:30|max:300',
            'asunto'=>'max:200',
            'juzgado_id'=>'required'
            ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{

            $proceso = Proceso::find($request->idTramite);
            $proceso->fecha = $request->fecha;
            $proceso->fechaSucesos = $request->fechaSucesos;
            $proceso->estado = $request->estado;
            $proceso->hechosOcurridos = $request->hechosOcurridos;
            $proceso->abogado_id = $request->abogado_id;
            $proceso->cliente_id = $request->cliente_id;
            $proceso->tipoproceso_id = $request->tipoproceso_id;
            $proceso->juzgado_id = $request->juzgado_id;
            $proceso->save();

            if( $proceso->save() ){
                $detalleproceso = Detalleproceso::where('procesos_id', $request->idTramite )->first();
                $detalleproceso->declaracionDemandante=$request->declaracionCliente;
                $detalleproceso->declaracionDemandado = $request->declaracionDemandado;
                $detalleproceso->save();

                $presupuesto = Presupuesto::where('procesos_id', $request->idTramite )->first();
                if( isset( $presupuesto) ){
                    $presupuesto->asunto = 'PRESUPUESTO INICIAL';
                    $presupuesto->monto = $request->monto;
                    $presupuesto->fecha = $request->fecha;
                    $presupuesto->estado = 1;
                    $presupuesto->save();
                }else{
                    $presupuesto = new Presupuesto();
                    $presupuesto->asunto = 'PRESUPUESTO INICIAL';
                    $presupuesto->monto = $request->monto;
                    $presupuesto->fecha = $request->fecha;
                    $presupuesto->procesos_id =$request->idTramite;
                    $presupuesto->estado = 1;
                    $presupuesto->save();
                }

                $pretencion = Pretencion::where('proceso_id', $request->idTramite)->first();
                if(isset( $pretencion ) ){
                    $pretencion->valorMedida = $request->valor_medida;
                    $pretencion->tipopretension_id = $request->tipopretencion_id;
                    $pretencion->save();
                }else{
                    $pretencion = new Pretencion();
                    $pretencion->proceso_id = $proceso->id;
                    $pretencion->fecha = $request->fecha;
                    $pretencion->valorMedida = $request->valor_medida;
                    $pretencion->tipopretension_id = $request->tipopretencion_id;
                    $pretencion->save();
                }

                $detallepretencion = Detallepretencion::where('pretencion_id', $pretencion->id )->first();
                if( isset( $detallepretencion ) ){
                    $detallepretencion->detallePretencionDemandante = $request->detallePretencionCliente;
                    $detallepretencion->detallePretencionDemandado = $request->detallePretencionDemandado;
                    $detallepretencion->save();
                }else{
                    $detallepretencion = new Detallepretencion();
                    $detallepretencion->detallePretencionDemandante = $request->detallePretencionCliente;
                    $detallepretencion->detallePretencionDemandado = $request->detallePretencionDemandado;
                    $detallepretencion->pretencion_id = $pretencion->id;
                    $detallepretencion->save();
                }

                return response()->json([ 'status'=>200, 'mensaje'=>'El trámite se ha actualizado correctamente.' ]);
            }else{
                return response()->json([ 'status'=>500, 'errors'=>'Error al actualizar el proceso' ]);
            }

         }
    }

}
