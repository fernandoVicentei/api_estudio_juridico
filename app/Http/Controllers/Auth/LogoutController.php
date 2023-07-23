<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
        } catch (\Throwable $th) {
            print_r($th->getMessage());
            return response()->json(['message' => 'Error en el Servidor'], 500);
        }
    }
}
