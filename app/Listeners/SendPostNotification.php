<?php

namespace App\Listeners;

use App\Events\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPostNotification implements ShouldQueue
{
    public function handle(PostPublished $event): void
    {
        Log::info("Post published: {$event->post->title}");
    }
}
