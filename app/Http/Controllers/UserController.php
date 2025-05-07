<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Login de usuario y generar token
     */
    public function signin(Request $request)
    {
        // Validamos que vengan email y password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscamos el usuario por email
        $user = User::where('email', $request->email)->first();

        // Si no existe o la password no matchea
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales no son correctas.'
            ], 401);
        }

        // Creamos el token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolvemos respuesta con el token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout del usuario (revoca tokens)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.'
        ]);
    }
}
