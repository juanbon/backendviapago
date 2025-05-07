<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webview extends Model
{
    use HasFactory;

    protected $table = 'webviews';

    protected $fillable = [
        'idType',
        'idSubtype',
        'text',
        'file',
        'status',
        'validSince',
        'extension',
    ];

    protected $dates = ['validSince', 'createdAt', 'updatedAt', 'deletedAt'];

    /**
     * Relación con el modelo WebviewType
     */
    public function webviewType()
    {
        return $this->belongsTo(WebviewType::class, 'idType');
    }

    /**
     * Relación con el modelo WebviewSubtype
     */
    public function webviewSubtype()
    {
        return $this->belongsTo(WebviewSubtype::class, 'idSubtype');
    }
}
