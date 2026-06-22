<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;
use App\Services\CurrencyService;

class Currency extends Facade
{
    protected static function getFacadeAccessor() {
        return CurrencyService::class;
    }
}
