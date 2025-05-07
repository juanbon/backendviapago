<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // ESTA es la forma correcta cuando inyectás desde middleware
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return response()->json(Menu::jsonMenu($user->id));
    }
    
    

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu' => 'required|string|max:100',
            'url' => 'nullable|string',
            'type' => 'required|integer',
            'idmain' => 'nullable|integer|exists:menus,id',
            'access' => 'nullable|string',
            'permission' => 'nullable|integer|exists:permissions,id',
            'restrict' => 'nullable|boolean',
            'status' => 'required|in:enable,disable',
        ]);

        $menu = Menu::create($data);

        return response()->json(['menu' => $menu], 201);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'menu' => 'sometimes|string|max:100',
            'url' => 'nullable|string',
            'type' => 'sometimes|integer',
            'idmain' => 'nullable|integer|exists:menus,id',
            'access' => 'nullable|string',
            'permission' => 'nullable|integer|exists:permissions,id',
            'restrict' => 'nullable|boolean',
            'status' => 'in:enable,disable',
        ]);

        $menu->update($data);

        return response()->json(['menu' => $menu]);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json(['message' => 'Menu deleted']);
    }
}
