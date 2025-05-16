<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'description', 'type'];
    protected $casts = [
        'value' => 'string',
    ];
}
