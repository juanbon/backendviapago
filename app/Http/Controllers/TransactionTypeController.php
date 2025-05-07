<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypesTransaction;

class TransactionTypeController extends Controller
{
    public function index()
    {
        $types = TypesTransaction::where('status', 'enable')
            ->select('id', 'typeTransaction as key', 'description as value')
            ->orderBy('description')
            ->get();

        return response()->json($types);
    }
}
