<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['menu', 'url', 'type', 'idmain', 'access', 'permission', 'restrict', 'status'];
    protected $table = "menu";

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'idmain');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'idmain');
    }

    public function permissionRelation()
    {
        return $this->belongsTo(Permission::class, 'permission');
    }

    public static function jsonMenu($userId)
    {
        $permissionIds = UserPermission::where('user_id', $userId)->pluck('permission_id')->toArray();
    
        $menus = self::where('status', 'enable')
            ->where(function ($query) use ($permissionIds) {
                $query->whereNull('permission')
                      ->orWhere('permission', 0)
                      ->orWhereIn('permission', $permissionIds);
            })->get();
    
        // Agrupa todos los menús por su padre
        $groupedMenus = $menus->groupBy('idmain');
    
        // Asegura que todos los menús raíz estén representados, incluso sin hijos
        return self::buildTree($groupedMenus, null);
    }
    
    private static function buildTree($groupedMenus, $parentId)
    {
        $tree = [];
    
        // Usa has() si $groupedMenus es Collection
        if (!$groupedMenus->has($parentId)) return $tree;
    
        foreach ($groupedMenus[$parentId] as $menu) {
            // Saltea si el menú está deshabilitado
            if ($menu->status !== 'enable') {
                continue;
            }
    
            $subitems = $menu->type == 3 ? null : self::buildTree($groupedMenus, $menu->id);
    
            // Salta dropdowns sin hijos válidos
            if ($menu->type != 3 && empty($subitems)) {
                continue;
            }
    
            $item = [
                'id' => $menu->id,
                'menu' => $menu->menu,
                'url' => $menu->url,
                'type' => $menu->type == 3 ? 'item' : 'dropdown',
                'idmain' => $menu->idmain,
                'permission' => $menu->permission,
                'privileges' => self::definePrivileges($menu->access),
                'subitems' => $subitems,
            ];
    
            $tree[] = $item;
        }
    
        return collect($tree)->sortBy('menu')->values()->all();
    }
    
    

    private static function definePrivileges($access)
    {
        if (!$access) return [];

        $roles = [
            1 => 'administrator',
            2 => 'supervisor',
            3 => 'operator',
            4 => 'customer',
            5 => 'user'
        ];

        return collect(explode('-', $access))
            ->map(fn($id) => $roles[(int)$id] ?? null)
            ->filter()
            ->values()
            ->all();
    }
}


