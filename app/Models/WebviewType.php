<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebviewType extends Model
{
    use HasFactory;

    protected $table = 'webviewstypes';

    protected $fillable = [
        'description',
    ];

    /**
     * Relación con el modelo Webview
     */
    public function webviews()
    {
        return $this->hasMany(Webview::class, 'idType');
    }
}
