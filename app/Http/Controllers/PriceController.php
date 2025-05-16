<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Price;
use App\Models\AppSetting;

class PriceController extends Controller
{
    public function getPrice(Request $request)
    {
        $base = $request->input('base', 'USD');
        $currency = $request->input('currency', 'XAU');

        $latest = Price::where('base_currency', $base)
            ->where('quote_currency', $currency)
            ->latest()
            ->first();

        if (!$latest) {
            return response()->json(['error' => 'Price data not available.'], 404);
        }

        $buyMargin = AppSetting::where('key', 'buy_margin')->first();
        $sellMargin = AppSetting::where('key', 'sell_margin')->first();

        if (!$buyMargin || !$sellMargin) {
            return response()->json(['error' => 'Margin settings not found.'], 404);
        }

        $buy_price = $latest->raw_price * (1 + floatval($buyMargin->value) / 100);
        $sell_price = $latest->raw_price * (1 + floatval($sellMargin->value) / 100);

        $history = Price::where('base_currency', $base)
            ->where('quote_currency', $currency)
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->map(function ($item) {
                return [
                    'timestamp' => $item->created_at->toIso8601String(),
                    'price' => round($item->raw_price, 6)
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'base' => $base,
            'currency' => $currency,
            'buy_price' => round($buy_price, 6),
            'sell_price' => round($sell_price, 6),
            'last_updated' => $latest->created_at->toIso8601String(),
            'history' => $history
        ]);
    }
}
