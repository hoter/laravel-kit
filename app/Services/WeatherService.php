<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function __construct(
        private string $apiUrl,
        private string $apiKey,
    ) {}

    public function getTemperature(string $city): ?float
    {
        try {
            $response = Http::timeout(5)
                ->get("{$this->apiUrl}/v1/current", [
                    'city' => $city,
                    'api_key' => $this->apiKey,
                ]);

            if ($response->successful()) {
                return $response->json('temperature');
            }
        } catch (\Exception $e) {
            // fall through
        }

        return null;
    }
}
