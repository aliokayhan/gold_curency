<?php
namespace App\Console\Commands;

use App\Services\CurrencyService;
use Illuminate\Console\Command;
use App\Models\Price;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchData extends Command
{
    protected $signature = 'gold:fetch-price';
    protected $description = 'Fetch gold price from CurrencyFreaks API';

    public function __construct(protected CurrencyService $currencyService) {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->info('Starting to fetch gold price at: ' . Carbon::now());
            Log::info('FetchData command started', ['time' => Carbon::now()->toDateTimeString()]);

            $data = $this->currencyService->getLatestRates();
            $this->info('API Response: ' . json_encode($data));

            if ($data === null) {
                $this->error('Failed to fetch data from CurrencyFreaks.');
                Log::error('CurrencyFreaks API returned null response');
                return 1;
            }

            $price = $data['rates']['XAU'] ?? null;
            if (!$price) {
                $this->error('XAU rate not found in the response.');
                Log::error('XAU rate missing from API response', ['data' => $data]);
                return 1;
            }

            $priceRecord = Price::create([
                'base_currency' => 'USD',
                'quote_currency' => 'XAU',
                'raw_price' => $price,
            ]);

            $this->info("Gold price record created with ID: {$priceRecord->id}");
            $this->info("Price value: $price");
            Log::info("Gold price updated", [
                'price' => $price,
                'record_id' => $priceRecord->id,
                'created_at' => $priceRecord->created_at
            ]);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
            Log::error('FetchData command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
