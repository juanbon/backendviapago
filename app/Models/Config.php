<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'idConfig';
    public $timestamps = false;

    protected $fillable = ['description', 'value', 'editable'];

    public static $filterAlias = [
        'idConfig' => 'idConfig',
        'description' => ['description', 'like'],
    ];
}
