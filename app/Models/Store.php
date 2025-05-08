<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    protected $table = 'stores';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'userId', 'userIdentityId', 'status', 'name', 'image', 'notes', 'phone', 'email',
        'link1', 'link2', 'link3', 'link4', 'position', 'address1', 'address2',
        'createdAt', 'updatedAt', 'deletedAt'
    ];

    public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}
