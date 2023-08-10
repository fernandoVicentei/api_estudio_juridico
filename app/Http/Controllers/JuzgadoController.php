<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juzgado;
use App\Models\Proceso;
use Illuminate\Support\Facades\Validator;

class JuzgadoController extends Controller
{
    //
    public function retornarJuzgados(){
        $juzgados= Juzgado::orderBy('id','DESC')->get();
        return response()->json([
            'status'=>200,
            'juzgados'=>$juzgados
        ]);
    }

    public function editarJuzgados(Request $request){

        $validator  = Validator::make($request->all(), [
            'idJuzgado' => 'required',
            'nombre'=>'required|min:5|max:50',
            'direccion' => 'required|min:5|max:40',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $juzgados= Juzgado::find($request->idJuzgado);
            $juzgados->nombre = $request->nombre;
            $juzgados->direccion = $request->direccion;
            $juzgados->estado = 1 ;
            $juzgados->save();
            if( $juzgados->save() ){
                return response()->json([
                    'status'=>200,
                ]);

            }else{
                return response()->json([
                    'status'=>500,
                ]);
            }
         }
    }

    public function agregarJuzgados( Request $request ){
        $validator  = Validator::make($request->all(), [
            'nombre'=>'required|min:5|max:50',
            'direccion' => 'required|min:5|max:40',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $juzgado = new Juzgado();
            $juzgado->nombre = $request->nombre;
            $juzgado->direccion = $request->direccion;
            $juzgado->estado = 1;
            $juzgado->save();
            if( $juzgado->save() ){
                return response()->json([
                    'status'=>200,
                ]);
            }else{
                return response()->json([
                    'status'=>500,
                ]);
            }
         }
    }

    public function eliminarJuzgado(Request $request){
        $validator  = Validator::make($request->all(), [
            'idJuzgado' => 'required',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $procesos = Proceso::where('juzgado_id',$request->idJuzgado )->count();
            $mensaje = "";
            if( $procesos > 0 ){
                $juzgado = Juzgado::find($request->idJuzgado);
                $juzgado->estado = 0 ;
                $juzgado->save();
                $mensaje = 'El registro no se eliminó porque ya está siendo utilizado, pero se deshabilitó para futuros trámites. ';
            }else{
                $juzgado = Juzgado::find($request->idJuzgado);
                $juzgado->delete();
                $mensaje = 'El registro se elimino correctamente.';
            }
            return response()->json([
                'status' => 200,
                'mensaje'=> $mensaje
            ]);

         }
    }

    public function buscarJuzgado( Request $request ){
        $validator  = Validator::make($request->all(), [
            'idJuzgado' => 'required',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{

             $juzgado = Juzgado::find( $request->idJuzgado );
             return response()->json([
                'status' => 200,
                'juzgado'=>$juzgado
            ]);
         }

    }

}
