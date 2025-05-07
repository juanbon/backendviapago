<?php

// app/Models/Promotion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'order',
        'name',
        'description',
        'url',
        'image',
        'status',
    ];

    public static $filterAliases = [
        'name' => 'name',
        'status' => 'status',
    ];
}
