<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Price extends Model
{
    protected $fillable = [
        'base_currency',
        'quote_currency',
        'raw_price',
    ];
}
