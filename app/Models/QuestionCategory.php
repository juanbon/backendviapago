<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'status'];

protected  $table = "questionscategories";

    const STATUS_ENABLE = 'enable';
    const STATUS_DISABLE = 'disable';

    protected $casts = [
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        // Después de crear o actualizar una categoría
        static::saved(function ($model) {
            // Puedes agregar un log aquí si necesitas
        });

        // Después de eliminar una categoría
        static::deleted(function ($model) {
            // Puedes agregar un log aquí si necesitas
        });
    }
}
