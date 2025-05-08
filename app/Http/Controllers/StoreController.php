<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::query();

        // WHERE
        if ($where = $request->input('filter.where')) {
            foreach ($where as $field => $value) {
                if (is_array($value) && isset($value['like'])) {
                    $query->where($field, 'LIKE', $value['like']);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        // ORDER
        if ($order = $request->input('filter.order')) {
            foreach ($order as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }

        // PAGINACIÓN
        $offset = $request->input('filter.offset', 0);
        $limit = $request->input('filter.limit', 30);

        $results = $query->offset($offset)->limit($limit)->get();

        return response()->json($results);
    }
}
