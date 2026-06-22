<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{

    public function __construct(private string $api, private int $cache) {
    }

    public function getRate(string $from, string $to): float {
        $rates = $this->getAllRates($from);
        return $rates[$to];
    }

    public function getAllRates(string $base = 'USD'): array {
        if (Cache::has('rates')) {
            return Cache::get('rates');
        }

        $output = [];
        $response = Http::get($this->api . $base);
        if ($response->successful()) {
            $data = $response->json();
            $output = $data['rates'];
        }

        Cache::put('rates', $output, $this->cache);
        return $output;
    }

    public function convert(float $amount, string $from, string $to): float {
        $rate = $this->getRate($from, $to);
        return $rate * $amount;
    }

    public function getSupportedCurrencies(): array {
        $rates = $this->getAllRates();
        return array_keys($rates);
    }
}
