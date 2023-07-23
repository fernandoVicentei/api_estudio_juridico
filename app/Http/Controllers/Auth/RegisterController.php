<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('fernandin-sos-mi-gatita')->plainTextToken;

            return response()->json(['message' => 'Registro exitoso', 'user' => responseUser($user, $token)], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error en el Servidor'], 500);
        }
    }
}
