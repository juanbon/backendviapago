<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypesTransaction extends Model
{
    protected $table = 'typestransactions';

    public $timestamps = false; // si no usás created_at / updated_at de Eloquent

    protected $fillable = ['typeTransaction', 'description', 'status', 'createdAt', 'updatedAt', 'deletedAt'];
}
