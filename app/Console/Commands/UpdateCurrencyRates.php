<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update';

    protected $description = 'Сбросить кэш курсов валют';

    public function handle(): int
    {
        Cache::forget('rates');

        $this->info('Кэш курсов сброшен.');

        return self::SUCCESS;
    }
}
