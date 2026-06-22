<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [5, 15, 30];

    public function __construct(
        public User $user
    ) {}

    public function handle(): void
    {
        Log::info("Sending welcome email to: {$this->user->email}");

        Mail::to($this->user->email)
        ->send(new WelcomeMail($this->user));

        Log::info("Welcome email sent to: {$this->user->email}");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("Failed to send email to: {$this->user->email}", [
            'error' => $e->getMessage(),
        ]);
    }
}
