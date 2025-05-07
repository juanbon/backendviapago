<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = 'reasons';

    protected $fillable = [
        'status',
        'reason',
        'description',
    ];

    public static $filterAliases = [
        'Motivo' => 'reason',
      //  'Estado' => 'status',
    ];

    public $timestamps = true; // ya tenés createdAt y updatedAt en tu tabla
}
