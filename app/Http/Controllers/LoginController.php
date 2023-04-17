<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Abogado;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function verificarCredencial(Request $request){
        $validator = \Validator::make($request->all(), [
            'codigo' => 'required|min:6|max:8',           
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'status'=>422]);
        }else{
            $abogado = Abogado::where('codigo',$request->codigo)->first();
            if(isset($abogado)){
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

  
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
