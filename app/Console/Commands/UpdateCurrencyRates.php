<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

#[Signature('app:update-currency-rates')]
#[Description('Command description')]
class UpdateCurrencyRates extends Command
{
    // Сигнатура: как вызывать команду
    protected $signature = 'currency:update
                            {base? : Базовая валюта (по умолчанию USD)}
                            {--fresh : Принудительно сбросить кэш}';

    // Описание для php artisan list
    protected $description = 'Обновить кэш курсов валют';

    public function handle(): int
    {
        $base = $this->argument('base') ?: 'USD';
        $fresh = $this->option('fresh');

        if ($fresh) {
            Cache::forget("currency_rates_{$base}");
            Cache::forget("currency_all_rates_{$base}");
            $this->info("Кэш для {$base} сброшен.");
        }

        $this->info("Запрашиваем курсы валют для {$base}...");

        try {
            $response = Http::get(
                "https://api.exchangerate-api.com/v4/latest/{$base}"
            );

            if ($response->failed()) {
                $this->error('Ошибка API: ' . $response->status());
                return self::FAILURE;
            }

            $data = $response->json();
            Cache::put("currency_all_rates_{$base}", $data, 3600);
            Cache::put("currency_rates_{$base}", $data['rates'], 3600);

            $this->info('Курсы обновлены. Валюта: ' . $base);
            $this->table(
                ['Валюта', 'Курс'],
                collect($data['rates'])->take(10)->map(
                    fn($rate, $code) => [$code, $rate]
                )
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
