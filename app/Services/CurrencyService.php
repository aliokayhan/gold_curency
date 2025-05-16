<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('CURRENCY_FREAKS_API_KEY');
        if (!$this->apiKey) {
            throw new \RuntimeException('CURRENCY_FREAKS_API_KEY is not set in .env file');
        }
        $this->baseUrl = 'https://api.currencyfreaks.com';
    }

    public function getLatestRates(): ?array
    {
        try {
            Log::info('Fetching rates from CurrencyFreaks API');
            
            $response = Http::get($this->baseUrl . '/latest', [
                'apikey' => $this->apiKey,
                'symbols' => 'XAU',
                'format' => 'json',
                'decimals' => '6'
            ]);

            Log::info('API Request URL: ' . $response->effectiveUri());

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Successfully fetched rates', ['data' => $data]);
                return $data;
            }

            Log::error('Failed to fetch rates', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching rates', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}