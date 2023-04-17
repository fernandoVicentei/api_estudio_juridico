<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\TipoProceso;

use App\Models\Proceso;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Abogado;
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

    public function retornarTipoTramites( ){
        $tramites = TipoProceso::all();
        return response()->json([            
            'success' => true,
            'tipotramites'=>$tramites
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
}
