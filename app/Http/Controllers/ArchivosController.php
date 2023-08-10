<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Archivoproceso;
use App\Models\Documento;
use App\Models\Proceso;
use Facade\FlareClient\Http\Response;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ArchivosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $procesos = Proceso::select(
            'procesos.id',
            'abg.nombre',
            'abg.apellido1',
            'abg.apellido2',
            'procesos.fecha',
            DB::raw('COUNT(archivosprocesos.id) as archivos_count')
        )
        ->join('abogados', 'abogados.id', '=', 'procesos.abogado_id')
        ->join('persona as abg', 'abg.id', '=', 'abogados.persona_id')
        ->leftJoin('archivosprocesos', 'archivosprocesos.procesos_id', '=', 'procesos.id')
        ->groupBy('procesos.id', 'abg.nombre', 'abg.apellido1', 'abg.apellido2', 'procesos.fecha')
        ->orderBy('procesos.id','desc')
        ->get();


        return response()->json(['status' => 200,  'procesos' => $procesos  ]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'idTramite' => 'required',
            'file' => 'required',
            'tipo'=>'required',
            'idTipoDoc'=>'required',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $idTramite = $request->idTramite;
            $tipo = $request->tipo;
            $idTipoDoc= $request->idTipoDoc;
            $archivos = $request->file('file');
            $nombre = date('Ymd_His').'_'.$idTramite.'.'. $archivos->getClientOriginalExtension();

            //$archivos->storeAs('archivos/img',$nombre, 'public');
            if( $tipo == 'imagen' ){
                $nombre = 'archivos/img/'.$nombre;
            }else if( $tipo == 'word' ){
                $nombre = 'archivos/word/'.$nombre;
            }else if( $tipo == 'pdf' ){
                $nombre = 'archivos/pdf/'.$nombre;
            }else {
                return response()->json([
                    'status' => 200,
                    'guardado'=>false,

                ]);
            }

            $ruta = public_path(  $nombre);
            File::copy($archivos, $ruta);

            $archivo =  new Archivo();
            $archivo->rutaArchivo = $nombre;
            $archivo->fecha = date('Y-m-d H:i:s');
            $archivo->save();

            $archi_tram = new Archivoproceso();
            $archi_tram->archivos_id = $archivo->id;
            $archi_tram->procesos_id = $idTramite;
            $archi_tram->documentos_id = $idTipoDoc;
            $archi_tram->save();
            //$ruta = Storage::disk('public')->get('img/'.$nombre);
            //$ruta =  asset(Storage::disk('public')->url( 'img/'.$nombre ) );
            // Storage::path('img/'.$nombre );
            return response()->json([
                'status' => 200,
                'ruta'=> $ruta,
                'guardado'=>true,
                'idArchivo'=> $archivo->id,
            ]);

         }

    }

    public function retornarTipoDoc(){
        $docs = Documento::all();
        return response()->json([
            'status' => 200,
            'documentos'=>$docs
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function retornarProceso(Request $request){
        $validator  = Validator::make($request->all(), [
            'idTramite' => 'required',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $proceso = Proceso::select('procesos.fecha','abg.nombre as nombreabg','abg.apellido1 as ap1abg',
            'abg.apellido2 as ap2abg',
            'cli.nombre as nombrecli','cli.apellido1 as ap1cli','cli.apellido2 as ap2cli', 'procesos.hechosOcurridos',
            'procesos.estado'
            )->join('abogados','abogados.id','=','procesos.abogado_id')
                ->join('clientes','clientes.id','=','procesos.cliente_id')
                ->join('persona as abg','abg.id','=','abogados.persona_id')
                ->join('persona as cli','cli.id','=','clientes.persona_id')
                ->where('procesos.id', $request->idTramite )
                ->first();

            $listaDocs = Archivo::select('rutaArchivo','fecha','documentos_id','archivos.id as codigo')
                    ->join('archivosprocesos','archivosprocesos.archivos_id','=','archivos.id')
                    ->where('archivosprocesos.procesos_id', $request->idTramite )
                    ->get();
            $listaDCS =[];
            foreach ($listaDocs as $key => $value) {
                array_push( $listaDCS, [
                       'rutaArchivo'=> public_path($value->rutaArchivo),
                       'fecha'=> $value->fecha ,
                       'documentos_id'=> $value->documentos_id,
                       'codigo'=> $value->codigo
                ] );
            }

            return response()->json([
                'status' => 200,
                'tramite'=>$proceso,
                'archivos'=>$listaDCS
            ]);
         }
    }

    public function descargar( $id ){
        $idArchivo = $id;
        $archivo = Archivo::find($idArchivo);
        $rutaCompleta = public_path( $archivo->rutaArchivo);
        if (file_exists($rutaCompleta)) {
            return Response::download($rutaCompleta);
        } else {
            abort(404);
        }
    }

    public function eliminarDocumentos(Request $request){
        $validator  = Validator::make($request->all(), [
            'idTramite' => 'required',
            'idArchivo'=>'required',
         ]);

         if( $validator->fails() ){
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
         }else{
            $archivo = Archivo::find($request->idArchivo);
            $ruta = public_path($archivo->rutaArchivo);
            if (File::exists($ruta)) {
                File::delete($ruta);
                $archi_tram = Archivoproceso::where('archivos_id', $archivo->id )->first();
                $archi_tram->delete();
                $archivo->delete();
                return response()->json(['message' => 'Archivo eliminado exitosamente','status'=>200 ]);
            } else {
                return response()->json(['mensaje' => 'Archivo no encontrado','status'=>200] );
            }
         }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
