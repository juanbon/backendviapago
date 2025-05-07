<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Recharge extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $table = 'recharge';
    public $timestamps = false;

    protected $fillable = ['id'];

    public static $filterAliases = [
        'id' => 'id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (self::count() >= 8) {
                abort(400, 'Se alcanzó el límite de recargas ingresadas');
            }
        });
    }
}
