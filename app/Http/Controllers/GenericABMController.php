<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;



class GenericABMController extends Controller
{
    protected function getModel(Request $request): Model
    {
        $modelClass = $request->route('model');

        if (!class_exists($modelClass)) {
            abort(400, 'Invalid model class');
        }

        return new $modelClass;
    }







    public function index(Request $request, $query = null)
    {
        $model = $this->getModel($request);
        $queryBuilder = $model->newQuery();
    
        // Si hay un ID en la URL, redirige a la función show
        if (is_numeric($query) && count($request->query()) === 0) {
            return $this->show($request, $query);
        }
    
        $excluded = ['paginated', 'Page', 'per_page', 'page', 'PageSize', 'sortField', 'sortOrder'];
    
        // Mapeo de alias si existe
        $filterAliases = property_exists($model, 'filterAliases') ? $model::$filterAliases : [];
    
        // Filtros por query string
        
        foreach ($request->query() as $field => $value) {

            if (!in_array($field, $excluded) && $value !== null && $value !== '' && $value !== 'null') {

                $dbField = $filterAliases[$field] ?? $field;
            
                // Si el valor tiene formato de fecha (YYYY-MM-DD)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    if (Str::contains(Str::lower($field), 'desde')) {
                        $queryBuilder->where('date', '>=', $value);
                    } elseif (Str::contains(Str::lower($field), 'hasta')) {
                        $queryBuilder->where('date', '<=', $value);
                    } else {
                        // Si no dice desde ni hasta, filtra exacto por campo
                        $queryBuilder->where($dbField, '=', $value);
                    }
                } else {
                    $queryBuilder->where($dbField, 'like', '%' . $value . '%');
                }
            }
            
            
        }
    
        // Filtros avanzados
        if ($request->has('filter.where')) {
            foreach ($request->filter['where'] as $field => $conditions) {
                foreach ($conditions as $op => $value) {
                    switch ($op) {
                        case 'eq': $queryBuilder->where($field, '=', $value); break;
                        case 'lt': $queryBuilder->where($field, '<', $value); break;
                        case 'gt': $queryBuilder->where($field, '>', $value); break;
                        case 'lte': $queryBuilder->where($field, '<=', $value); break;
                        case 'gte': $queryBuilder->where($field, '>=', $value); break;
                        case 'ne': $queryBuilder->where($field, '!=', $value); break;
                    }
                }
            }
        }
    
        // **Asegurándonos que la paginación sea manejada correctamente:**
        $shouldPaginate = $request->input('Page', 1); // Página actual, por defecto es la 1
        $perPage = $request->input('PageSize', 20);  // Tamaño de la página (por defecto 20)
    
        // Reporte completo (sin paginar)
        if ($request->input('Page') == -1) {
            $result = $queryBuilder->get(); // reporte completo
        } else {
            // Si la paginación está habilitada
            $result = $queryBuilder->paginate($perPage, ['*'], 'page', $shouldPaginate);
        }
    
        return response()->json($result, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
    











    public function store(Request $request)
    {
        $model = $this->getModel($request);
        $fillable = $model->getFillable();
    
        $data = $request->only($fillable);
    
        $validator = Validator::make($data, $this->getValidationRules($model, 'store'));
        $validator->validate();
    
        $item = $model->create($data);
    
        // Logging
        if ($model instanceof \App\Models\Config) {
            \App\Models\ConfigLog::create([
                ...$item->toArray(),
                'operationLog' => 'create',
                'userIdLog' => $request->user()?->id ?? auth()->id()
            ]);
        }
    
        $this->logOperation($item, 'create', auth()->id());

        return $item;
    }
    


    private function logOperation($model, $operation, $userId)
{
    $logData = $model->toArray();
    $logData['operationLog'] = $operation;
    $logData['userIdLog'] = $userId;
    
    // Ejemplo dinámico para tabla de logs
    $logModelClass = get_class($model) . 'Log';
    $logModelClass = str_replace('App\\Models\\', 'App\\Models\\', $logModelClass); // por si querés personalizarlo más

    if (class_exists($logModelClass)) {
        $logModelClass::create($logData);
    }
}



    public function update(Request $request, $id)
    {
        $modelClass = $this->getModel($request);
        $item = $modelClass->findOrFail($id);
        $fillable = $item->getFillable();
    
        $data = $request->only($fillable);
    
        $validator = Validator::make($data, $this->getValidationRules($item, 'update'));
        $validator->validate();
    
        $item->update($data);
    
        // Logging
        if ($item instanceof \App\Models\Config) {
            \App\Models\ConfigLog::create([
                ...$item->toArray(),
                'operationLog' => 'update',
                'userIdLog' => $request->user()?->id ?? auth()->id()
            ]);
        }
    

        $this->logOperation($item, 'update', auth()->id());

        return $item;
    }
    

    public function destroy(Request $request, $id)
    {
        $modelClass = $this->getModel($request);
        $item = $modelClass->findOrFail($id);
    
        // Logging antes del delete
        if ($item instanceof \App\Models\Config) {
            \App\Models\ConfigLog::create([
                ...$item->toArray(),
                'operationLog' => 'delete',
                'userIdLog' => $request->user()?->id ?? auth()->id()
            ]);
        }
    
        $item->delete();
    

     //    $this->logOperation($model, 'delete', auth()->id());

        return response()->json(['message' => 'Deleted successfully']);
    }
    

    public function show(Request $request, $id)
    {
        $model = $this->getModel($request)->findOrFail($id);
        return $model;
    }

    protected function getValidationRules(Model $model, string $action): array
    {
        // Opcional: puedes personalizar las reglas aquí por modelo y acción
        return [];
    }
}
