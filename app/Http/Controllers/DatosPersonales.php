<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Abogado;
use App\Models\Persona;

class DatosPersonales extends Controller
{

    public function crearPersona($request){
        $persona = new Persona();
        $persona->nombre = $request->nombre ;
        $persona->apellido1 = $request->apellido1 ;
        $persona->apellido2 = $request->apellido2 ;
        $persona->cedula = $request->cedula ;
        $persona->celular = $request->celular ;
        $persona->direccion = $request->direccion ;
        $persona->fechaNacimiento = $request->fechaNacimiento ;
        $persona->genero = $request->genero ;
        $persona->estadoCivil = $request->estadocivil ;
        $persona->tipoPersona_id = $request->tipopersona ;
         $persona->save();
        return $persona;
    }

    public function buscarAbogado(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $abogado = Abogado::find($request->id);
            $persona = Persona::find($abogado->persona_id);
            $obj = [
                'nombre'=>$persona->nombre,
                'appaterno'=>$persona->apellido1,
                'apmaterno'=>$persona->apellido2,
                'cedula'=>$persona->cedula,
                'celular'=>$persona->celular,
                'direccion'=>$persona->direccion,
                'fechaNacimiento'=>$persona->fechaNacimiento,
                'genero'=>$persona->genero,
                'estadocivil'=>$persona->estadocivil,
                'tipopersona_id'=>$persona->tipopersona_id,
                'codigo_acceso'=>$abogado->codigo,
                'persona_id'=>$persona->id,
                'estado'=> $abogado->estado
            ];
            return response()->json([
                'success' => true,
                'abogado'=>$obj
            ]);
        }



    }

    public function buscarCliente(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $cliente = Cliente::find($request->id);
            $persona = Persona::find($cliente->persona_id);
            $obj = [
                'nombre'=>$persona->nombre,
                'appaterno'=>$persona->apellido1,
                'apmaterno'=>$persona->apellido2,
                'cedula'=>$persona->cedula,
                'celular'=>$persona->celular,
                'direccion'=>$persona->direccion,
                'fechaNacimiento'=>$persona->fechaNacimiento,
                'genero'=>$persona->genero,
                'estadocivil'=>$persona->estadocivil,
                'tipopersona_id'=>$persona->tipopersona_id,
                'persona_id'=>$persona->id,
                'estado'=> $cliente->estado
            ];
            return response()->json([
                'success' => true,
                'cliente'=>$obj
            ]);
        }



    }

    public function actualizarPersona($request){
        $persona = Persona::find($request->idpersona);
        $persona->nombre = $request->nombre ;
        $persona->apellido1 = $request->apellido1 ;
        $persona->apellido2 = $request->apellido2 ;
        $persona->cedula = $request->cedula ;
        $persona->celular = $request->celular ;
        $persona->direccion = $request->direccion ;
        $persona->fechaNacimiento = $request->fechaNacimiento ;
        $persona->genero = $request->genero ;
        $persona->estadoCivil = $request->estadocivil ;
        $persona->tipoPersona_id = $request->tipopersona ;
        return $persona->save();
    }

    public function agregarCliente(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:3|max:100',
            'cedula'=>'required|min:6|max:11',
            'celular'=>'required|min:6|max:11',
            'direccion'=>'required|min:6|max:50',
            'fechaNacimiento'=>'required',
            'genero'=>'required',
            'estadocivil'=>'required',
            /* 'tipoPersona_id' =>'required', */
            'estado'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $persona = $this->crearPersona($request);
            if( isset($persona) ){
                $idpersona =$persona->id;
                $cliente = new Cliente();
                $cliente->estado = $request->estado;
                $cliente->persona_id = $idpersona;
                $cliente->save();
                if( $cliente->save() ){
                    return response()->json([
                        'success' => true
                    ]);
                }else{
                    return response()->json([
                        'success' => false
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }
    }

    public function actualizarCliente(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:3|max:100',
            'cedula'=>'required|min:6|max:11',
            'celular'=>'required|min:6|max:11',
            'direccion'=>'required|min:6|max:50',
            'fechaNacimiento'=>'required',
            'genero'=>'required',
            'estadocivil'=>'required',
            /* 'tipoPersona_id' =>'required', */
            'estado'=>'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $cliente = Cliente::find($request->id);
            $cliente->estado = $request->estado;
            $cliente->save();
            if($cliente->save()){
                $persona = $this->actualizarPersona($request);
                if($persona){
                    return response()->json([
                        'success' => true
                    ]);
                }else{
                    return response()->json([
                        'success' => false
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }

    }

    public function eliminarCliente(Request $request){

        $validator = Validator::make($request->all(), [
            'idCliente' => 'required',

        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $cliente = Cliente::find($request->idCliente);
            $cliente->estado = 0;
            $cliente->Save();
            if( $cliente->save() ){
                return response()->json([
                    'success' => true
                ]);
            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
           // $persona = Persona::find($request->idPersona);
            /* if(isset($cliente)){
                $res = $cliente->delete();
                if($res){
                    $res = $persona->delete();
                    if($res){
                        return response()->json([
                            'success' => true
                        ]);
                    }else{
                        return response()->json([
                            'success' => false,

                        ]);
                    }
                }else{
                    return response()->json([
                        'success' => false,
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                ]);
            } */
        }

    }

    public function agregarAbogado(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:3|max:100',
            'cedula'=>'required|min:6|max:11',
            'celular'=>'required|min:6|max:11',
            'direccion'=>'required|min:6|max:50',
            'fechaNacimiento'=>'required',
            'genero'=>'required',
            'estadocivil'=>'required',
            'codigo'=>'required|min:6|max:8'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $persona = $this->crearPersona($request);
            if( isset($persona) ){
                $idpersona =$persona->id;
                $abogado = new Abogado();
                $abogado->codigo= $request->codigo;
                $abogado->persona_id = $idpersona;
                $abogado->save();
                if( $abogado->save() ){
                    return response()->json([
                        'success' => true
                    ]);
                }else{
                    return response()->json([
                        'success' => false
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }
    }


    public function actualizarAbogado(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:3|max:100',
            'cedula'=>'required|min:6|max:15',
            'celular'=>'required|min:6|max:11',
            'direccion'=>'required|min:6|max:50',
            'fechaNacimiento'=>'required',
            'genero'=>'required',
            'estadocivil'=>'required',
            'codigo'=>'required|min:6|max:8'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{

            $abogado = Abogado::find($request->id);
            $abogado->codigo = $request->codigo;
            $abogado->save();
            if($abogado->save()){
                $persona = $this->actualizarPersona($request);
                if($persona){
                    return response()->json([
                        'success' => true
                    ]);
                }else{
                    return response()->json([
                        'success' => false
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false
                ]);
            }
        }
    }

    public function eliminarAbogado(Request $request){

        $validator = Validator::make($request->all(), [
            'idAbogado' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $abogado = Abogado::find($request->idAbogado);
            $abogado->estado = 0;
            $abogado->save();
            if( $abogado->save() ){
                return response()->json([
                    'success' => true
                ]);
            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
           /*  $abogado = Abogado::find($request->idAbogado);
            $persona = Persona::find($request->idPersona);
            if(isset($abogado)){
                $res = $abogado->delete();
                if($res){
                    $res = $persona->delete();
                    if($res){
                        return response()->json([
                            'success' => true
                        ]);
                    }else{
                        return response()->json([
                            'success' => false,

                        ]);
                    }
                }else{
                    return response()->json([
                        'success' => false,
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                ]);
            } */
        }
    }

    public function retornarClientes(){
        $clientes= Cliente::orderBy('id','desc')->get();
        $clientes_datos =[];
        foreach ($clientes as $key => $value) {
            $persona = $value->persona;
            $obj=[
                'id'  => $value->id,
                'nombre' => $persona->nombre,
                'appaterno'=>$persona->apellido1,
                'apmaterno'=> $persona->apellido2,
                 'carnet'=>  $persona->cedula ,
                 'genero'=> $persona->genero==0?'MUJER':'HOMBRE' ,
                 'celular'=> $persona->celular ,
                 'estadocivil'=>($persona->estadocivil==1? 'CASADO': ($persona->estadocivil==2?'SOLTERO':( $persona->estadocivil==3?'DIVORCIADO':'VIUDO' ) ) ) ,
                 'fechanac'=> $persona->fechaNacimiento ,
                 'estado'=>  $value->estado ,
                 'direccion'=>$persona->direccion,
                 'tipopersona'=>  $persona->tipopersona_id,
                 'idpersona'=>$persona->id
            ];
            array_push( $clientes_datos, $obj );
        }

        return response()->json([
            'success'=>true,
            'clientes'=>$clientes_datos
        ]);

    }

    public function retornarAbogados(){
        $abogados= Abogado::orderBy('id','desc')->get();
        $abogados_datos =[];
        foreach ($abogados as $key => $value) {
            $persona = $value->persona;
            $obj=[
                'id'  => $value->id,
                'nombre' => $persona->nombre,
                'appaterno'=>$persona->apellido1,
                'apmaterno'=> $persona->apellido2,
                 'carnet'=>  $persona->cedula ,
                 'genero'=> $persona->genero==0?'MUJER':'HOMBRE' ,
                 'celular'=> $persona->celular ,
                 'estadocivil'=>($persona->estadocivil==1? 'CASADO': ($persona->estadocivil==2?'SOLTERO':( $persona->estadocivil==3?'DIVORCIADO':'VIUDO' ) ) ) ,
                 'fechanac'=> $persona->fechaNacimiento ,
                 'codigo'=>  $value->codigo ,
                 'direccion'=>$persona->direccion,
                 'tipopersona'=>  $persona->tipopersona_id,
                 'idpersona'=>$persona->id
            ];
            array_push( $abogados_datos, $obj );
        }

        return response()->json([
            'success'=>true,
            'abogados'=>$abogados_datos
        ]);

    }

    public function retornarClientesBasico(){
        $clientes= Cliente::all();
        $clientes_datos =[];
        foreach ($clientes as $key => $value) {
            $persona = $value->persona;
            $obj=[
                'id'  => $value->id,
                'nombre_completo' => $persona->nombre.' '.$persona->apellido1.' '.$persona->apellido2.' ('.$persona->cedula.')',
            ];
            array_push( $clientes_datos, $obj );
        }

        return response()->json([
            'status'=> 200 ,
            'clientes'=>$clientes_datos
        ]);

    }

    public function retornarAbogadosBasico(){
        $abogados= Abogado::all();
        $abogados_datos =[];
        foreach ($abogados as $key => $value) {
            $persona = $value->persona;
            $obj=[
                'id'  => $value->id,
                'nombre_completo' => 'Lic. '.$persona->nombre.' '.$persona->apellido1.' '.$persona->apellido2.' ('.$persona->cedula.')',
            ];
            array_push( $abogados_datos, $obj );
        }

        return response()->json([
            'status'=> 200 ,
            'abogados'=>$abogados_datos
        ]);

    }

}

