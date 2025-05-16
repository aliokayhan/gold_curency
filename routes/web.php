<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/gold-price', [PriceController::class, 'getPrice']);