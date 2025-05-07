<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigLog extends Model
{
    protected $table = 'config_logs';
    public $timestamps = false;

    protected $fillable = [
        'idConfig', 'description', 'value', 'editable',
        'operationLog', 'userIdLog'
    ];
}
