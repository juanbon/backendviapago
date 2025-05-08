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



    public function index(Request $request)
    {
        $filter = $request->input('filter', []);
    
        $query = User::query();
    
        // Filtro por campos específicos (filter[where])
        if (isset($filter['where'])) {
            foreach ($filter['where'] as $field => $condition) {
                if (is_array($condition)) {
                    foreach ($condition as $operator => $value) {
                        if ($operator === 'like') {
                            if (!empty($value)) {
                                $query->where($field, 'like', $value);
                            }
                        }
                        // Agregá más operadores si necesitás
                    }
                } elseif (!empty($condition)) {
                    $query->where($field, $condition);
                }
            }
        }
    
        // Filtro de búsqueda global (filter[query])
        if (!empty($filter['query'])) {
            $search = $filter['query'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
    
        // Ordenamiento (filter[order])
        if (isset($filter['order']) && is_array($filter['order'])) {
            foreach ($filter['order'] as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }
    
        // Paginación
        $offset = isset($filter['offset']) ? (int) $filter['offset'] : 0;
        $limit = isset($filter['limit']) ? (int) $filter['limit'] : 30;
    
        $query->offset($offset)->limit($limit);
    
        return response()->json(
           //  'data' =>  $query->get()
           $query->get()
        );
    }


    public function show($id)
    {
        // Buscar el usuario por el ID
        $user = User::findOrFail($id);

        // Retornar los datos del usuario como JSON
        return response()->json($user);
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
