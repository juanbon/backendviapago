<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $table = 'users';

    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'status',
        'access',
        'accessToken', // si querés usarlo como en Node
    ];

    protected $hidden = [
        'password',
        'accessToken',
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    // En App\Models\User.php

public function permissions()
{
    return $this->hasMany(UserPermission::class);
}

}
