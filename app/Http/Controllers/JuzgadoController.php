<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juzgado;

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
        $juzgados= Juzgado::find($request->idJuzgado);
        $juzgados->nombre = $request->nombre;
        $juzgados->direccion = $request->direccion;
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

    public function agregarJuzgados( Request $request ){
        $juzgado = new Juzgado();
        $juzgado->nombre = $request->nombre;
        $juzgado->direccion = $request->direccion;
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
