<?php

// app/Models/PromotionLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionLog extends Model
{
    protected $table = 'promotionsLogs';

    protected $fillable = [
        'order',
        'name',
        'description',
        'url',
        'image',
        'status',
        'operationLog',
        'userIdLog',
    ];

    public $timestamps = false;
}
