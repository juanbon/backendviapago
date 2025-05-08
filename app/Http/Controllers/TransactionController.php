<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{


    public function summary(Request $request)
    {
        $query = Transaction::query();
    
        // Filtro por rango de fechas
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [$request->from, $request->to]);
        }
    
        // Filtro por tipos pasados como una cadena delimitada por comas (usando 'types' en lugar de 'type')
        if ($request->filled('types')) {
            $types = explode(',', $request->types);  // Convertimos la cadena a un arreglo
            $query->whereIn('type', $types);  // Filtrar por los tipos
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('sourceUserId', $request->user_id)
                  ->orWhere('targetUserId', $request->user_id);
            });
        }
    
        // Agrupar por bloques de 2 días
        $summary = $query->selectRaw('
        MIN(DATE(date)) as day, 
        type, 
        COUNT(*) as total, 
        SUM(amount) as total_amount, 
        AVG(amount) as avg_amount, status,
        FLOOR((DAYOFYEAR(date) - 1) / 21) as date_block')
        ->groupBy(DB::raw('FLOOR((DAYOFYEAR(date) - 1) / 21)'), 'type','status')
        ->orderBy('day', 'asc')
        ->get();
    
        return response()->json($summary);
    }
    
    
    

    /*


    public function summary(Request $request)
{
    $query = Transaction::query();

    // Filtro por rango de fechas
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('date', [$request->from, $request->to]);
    }

    // Filtros opcionales
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('user_id')) {
        $query->where(function ($q) use ($request) {
            $q->where('sourceUserId', $request->user_id)
              ->orWhere('targetUserId', $request->user_id);
        });
    }

    $summary = $query->selectRaw('DATE(date) as day, type, COUNT(*) as total, SUM(amount) as total_amount')
        ->groupBy('day', 'type')
        ->orderBy('day', 'asc')
        ->get();

    return response()->json($summary);
}


*/




}