<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CurrencyService;
use Illuminate\Contracts\Support\DeferrableProvider;

class CurrencyServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrencyService::class, function ($app) {
            return new CurrencyService(config('currency.api'), config('currency.cache'));
        });
    }

    public function provides(): array
    {
        return [CurrencyService::class];
    }
}
