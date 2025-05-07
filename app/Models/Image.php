<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    public $timestamps = false;

    public static $filterAliases = [
        'Descripcion' => 'companyDescription',
      //  'Estado' => 'status',
    ];

    protected $fillable = [
        'companyDescription',
        'logo32px',
        'logo130px',
        'status',
    ];
}

