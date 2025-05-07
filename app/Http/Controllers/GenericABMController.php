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



/*


    public function index(Request $request, $query = null)
    {
        if (is_numeric($query) && count($request->query()) === 0) {
            return $this->show($request, $query);
        }
    
        $model = $this->getModel($request);
        $queryBuilder = $model->newQuery();
    
        if ($query !== null && $query !== 'all') {
            $queryBuilder->where('id', $query);
        }
    
        $excluded = ['paginated', 'Page', 'per_page', 'page'];
    
        // Acá definís los alias
        $filterAliases = property_exists($model, 'filterAliases') ? $model::$filterAliases : [];
    
        foreach ($request->query() as $field => $value) {
            if (!in_array($field, $excluded) && $value !== null && $value !== '') {
                $dbField = $filterAliases[$field] ?? $field;
                $queryBuilder->where($dbField, 'like', '%' . $value . '%');
            }
        }
    
        $result = $request->boolean('paginated', false)
            ? $queryBuilder->paginate(10)
            : $queryBuilder->get();
    
        return response()->json($result, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }


*/ 



public function index(Request $request, $query = null)
{
    $model = $this->getModel($request);
    $queryBuilder = $model->newQuery();

    // Si hay un ID en la URL, redirige a la función show
    if (is_numeric($query) && count($request->query()) === 0) {
        return $this->show($request, $query);
    }

    $excluded = ['paginated', 'Page', 'per_page', 'page'];

    // Definir el mapeo de alias si es necesario
    $filterAliases = property_exists($model, 'filterAliases') ? $model::$filterAliases : [];

    // Si hay parámetros en la URL, los usamos para buscar
    foreach ($request->query() as $field => $value) {
        if (!in_array($field, $excluded) && $value !== null && $value !== '') {
            $dbField = $filterAliases[$field] ?? $field;
    
            // ✅ Asegurarse de que NO sea un array antes de aplicar LIKE
            if (!is_array($value)) {
                $queryBuilder->where($dbField, 'like', '%' . $value . '%');
            }
        }
    }
    

    if (isset($request->filter['where'])) {
        foreach ($request->filter['where'] as $field => $conditions) {
            foreach ($conditions as $op => $value) {
                switch ($op) {
                    case 'eq': $queryBuilder->where($field, '=', $value); break;
                    case 'lt': $queryBuilder->where($field, '<', $value); break;
                    // etc.
                }
            }
        }
    }
    
    

    // Si se quiere paginar, se hace
    $result = $request->boolean('paginated', false)
        ? $queryBuilder->paginate(10)
        : $queryBuilder->get();

    return response()->json($result, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
}



/*

    public function index(Request $request)
    {
        $model = $this->getModel($request);
        $query = $model->newQuery();
    
        $excluded = ['paginated', 'Page', 'per_page','page'];
        foreach ($request->query() as $field => $value) {
            if (!in_array($field, $excluded) && $value !== null && $value !== '') {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
    
        $result = $request->boolean('paginated', false)
            ? $query->paginate(10)
            : $query->get();
    
        return response()->json($result, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
    
    */

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
    

        $this->logOperation($model, 'delete', auth()->id());

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
