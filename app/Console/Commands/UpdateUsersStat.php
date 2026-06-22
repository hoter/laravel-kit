<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

#[Signature('stats:generate')]
#[Description('Подсчёт пользовательской статистики вручную')]
class UpdateUsersStat extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::count();
        Cache::put('users_count', $count, 5000);
        Log::info("User stats: $count");
    }
}
