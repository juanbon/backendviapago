<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebviewSubtype extends Model
{
    use HasFactory;

    protected $table = 'webviewssubtypes';

    protected $fillable = [
        'description',
        'status',
    ];

    /**
     * Relación con el modelo Webview
     */
    public function webviews()
    {
        return $this->hasMany(Webview::class, 'idSubtype');
    }
}
