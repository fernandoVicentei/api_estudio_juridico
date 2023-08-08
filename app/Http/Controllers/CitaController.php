<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Proceso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{

    public function retornarCitas(){
        $citas = Citas::select('abg.nombre as nombre_abg','abg.apellido1 as ap1_abg','abg.apellido2 as ap2_abg',
         'cli.nombre as nombre_cli','cli.apellido1 as ap1_cli','cli.apellido2 as ap2_cli', 'citas.asunto','citas.fecha',
         'procesos.id as cod_proceso','citas.id' )
        ->join('procesos', 'procesos.id', '=', 'citas.proceso_id')
        ->join('abogados', 'abogados.id', '=', 'procesos.abogado_id')
        ->join('clientes', 'clientes.id', '=', 'procesos.cliente_id')
        ->join('persona as abg', 'abg.id', '=', 'abogados.persona_id')
        ->join('persona as cli', 'cli.id', '=', 'clientes.persona_id')
        ->orderBy('citas.id','DESC')
        ->get();

        return response()->json([
            'status'=>200,
            'citas'=>$citas
        ]);

    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'direccion_cita' => 'required|max:100',
            'fecha_cita'=>'required|after:now',
            'asunto_cita'=>'required|min:5|max:300',
            'idProceso'=>'required'
         ],[
            'fecha_cita.after' => 'La fecha debe ser posterior a la fecha actual.',
        ]);

        if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else
        {
            $direccionCita = $request->direccion_cita;
            $fechaCita = $request->fecha_cita;
            $asuntoCita = $request->asunto_cita;
            $idProceso = $request->idProceso;

            $cita = new Citas();
            $cita->direccion = $direccionCita;
            $cita->fecha = $fechaCita;
            $cita->asunto = $asuntoCita;
            $cita->proceso_id = $idProceso;
            $cita->save();
            if ($cita->save()) {
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 500]);
            }

        }
    }

    public function buscarCita( Request $request ){
        $validator  = Validator::make($request->all(), [
           'idCita'=>'required'
        ]);

        if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else
        {
            $cita= Citas::find( $request->idCita );
            if( isset($cita) ){
                return response()->json(['status' => 200, 'cita' => $cita ]);
            }else{
                return response()->json(['status' => 200, 'cita' => null ]);
            }
        }
    }

    public function EliminarCita( Request $request ){
        $validator  = Validator::make($request->all(), [
           'idCita'=>'required'
        ]);

        if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else
        {
            $cita= Citas::find( $request->idCita );
            $cita->delete();
            return response()->json(['status' => 200  ]);
        }
    }

    public function retornarProcesos(){
        $procesos = Proceso::select('persona.nombre','persona.apellido1','procesos.id as cod_proceso','procesos.fecha')->join('abogados','abogados.id','=','procesos.abogado_id')
            ->join('persona','persona.id','=','abogados.persona_id')
            ->where('procesos.estado',1)->get();
            return response()->json(['status' => 200, 'procesos' => $procesos]);
    }

    public function update(Request $request)
    {
         $validator  = Validator::make($request->all(), [
            'direccion_cita' => 'required|max:100',
            'fecha_cita'=>'required|after:now',
            'asunto_cita'=>'required|min:5|max:300',
            'idCita' => 'required',
            'idProceso'=>'required'
         ],[
            'fecha_cita.after' => 'La fecha debe ser posterior a la fecha actual.',
        ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else
        {
            $citaID = $request->idCita;
            $direccionCita = $request->direccion_cita;
            $fechaCita = $request->fecha_cita;
            $asuntoCita = $request->asunto_cita;
            $idProceso = $request->idProceso;

            $cita = Citas::find($citaID);
            $cita->direccion = $direccionCita;
            $cita->fecha = $fechaCita;
            $cita->asunto = $asuntoCita;
            $cita->proceso_id = $idProceso;
            $cita->save();

            if ($cita->save()) {
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 500]);
            }
        }
    }

    public function filtrarCitasAbogado(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'abogado_id'=>'required',
        ]);

        if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{

            $fecha_actual = Carbon::parse( Carbon::now()->format('Y-m-d') );
            $fecha_diez_dias = Carbon::parse(Carbon::now()->addDays(5));

            $abogadoID = $request->abogado_id;

            $citas = Citas::select('*')->whereExists(function ($query) use ($abogadoID) {
                $query->from('procesos')
                      ->whereColumn('citas.proceso_id', 'procesos.id')
                      ->where('abogado_id',$abogadoID );
            })
            ->whereBetween('citas.fecha', [$fecha_actual->format('Y-m-d'), $fecha_diez_dias->format('Y-m-d') ])
            ->get();

            $citas_pendientes=[];
            $mostrarAlerta= false;

            if( count($citas)>0 ){
                foreach ($citas as $key => $cita) {
                    $fecha_parcial = Carbon::parse($cita->fecha);
                    $fecha_actual = Carbon::parse( Carbon::now()->format('Y-m-d') );
                    if( $fecha_parcial>= $fecha_actual  ){
                        $cantidadDias = $fecha_actual->diffInDays( $fecha_parcial );
                        if( $cantidadDias<=5 ){
                            $cliente = $this->retornarCliente( $cita->proceso_id );
                            $mostrarAlerta = true;
                            $objeto = [
                                'asunto'=>$cita->asunto,
                                'fecha'=>$cita->fecha,
                                'cliente'=>$cliente
                            ];
                            array_push( $citas_pendientes, $objeto );

                        }
                    }
                }
                return response()->json(['status' => 200, 'alerta'=>$mostrarAlerta, 'listaCita'=>$citas_pendientes]);
            }else{
                return response()->json(['status' => 200, 'alerta'=>$mostrarAlerta, 'listaCita'=>$citas_pendientes]);
            }
        }
    }

    public function retornarCliente($idProceso){
        $proceso = Proceso::find($idProceso);
        $cliente = Cliente::find( $proceso->cliente_id );
        $datospersonales =Persona::find($cliente->persona_id);
        return ( $datospersonales->nombre.' '. $datospersonales->apellido1.' '.$datospersonales->apellido2);
    }

}
