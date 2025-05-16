<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

abstract class Controller
{
    // add get method
    public function get($url, $params = [])
    {
        $response = Http::get('https://api.currencyfreaks.com/latest', [
            'apikey' => env('CURRENCY_FREAKS_API_KEY'),
            'symbols' => 'XAU',
            'base' => 'USD',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'error' => 'Unable to fetch data from CurrencyFreaks API'
        ], 500);
    }
}
