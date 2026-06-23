<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

test('WeatherService returns temperature on successful API response', function () {
    Http::fake([
        'api.weather.example/*' => Http::response(['temperature' => 22.5], 200),
    ]);

    $service = new WeatherService('https://api.weather.example', 'test-key');
    $temp = $service->getTemperature('Moscow');

    expect($temp)->toBe(22.5);
    Http::assertSentCount(1);
});

test('WeatherService returns null when API fails', function () {
    Http::fake([
        'api.weather.example/*' => Http::response(null, 500),
    ]);

    $service = new WeatherService('https://api.weather.example', 'test-key');
    $temp = $service->getTemperature('Moscow');

    expect($temp)->toBeNull();
    Http::assertSentCount(1);
});

test('WeatherService returns null on connection timeout', function () {
    Http::fake([
        'api.weather.example/*' => Http::sequence()
            ->pushStatus(500),
    ]);

    $service = new WeatherService('https://api.weather.example', 'test-key');
    $temp = $service->getTemperature('Moscow');

    expect($temp)->toBeNull();
});
