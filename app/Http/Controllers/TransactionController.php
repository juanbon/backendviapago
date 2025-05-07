<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{


public function summary(Request $request)
{
    $query = Transaction::query();

    // Filtro por mes y año
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);

    $query->whereYear('date', $year)
          ->whereMonth('date', $month);

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

    // Agrupación por día y tipo
    $summary = $query->selectRaw('DATE(date) as day, type, COUNT(*) as total, SUM(amount) as total_amount')
        ->groupBy('day', 'type')
        ->orderBy('day', 'asc')
        ->get();

    return response()->json($summary);
}


}