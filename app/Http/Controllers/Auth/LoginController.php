<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();

                /** @var \App\Models\User $user **/
                $token = $user->createToken('fernandin-sos-mi-gatita')->plainTextToken;

                return response()->json(
                    ['message' => 'Inicio de Sesion exitoso', 'user' => responseUser($user, $token)],
                    200
                );
            }
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error en el Servidor'], 500);
        }
    }
}
