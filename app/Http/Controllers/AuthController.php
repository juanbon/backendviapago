<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(function ($query) use ($request) {
                $query->where('username', $request->username)
                      ->orWhere('email', $request->username);
            })
            ->where('status', 'enable')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'access' => $user->access,
            ],
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        return response()->json([
            'idusuario' => $user->id,
            'rol' => $user->access,
            'username' => $user->username,
            'email' => $user->email
        ]);
    }
}
