<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendCommentQueueNotification implements ShouldQueue
{

    /**
     * Handle the event.
     */
    public function handle(CommentAdded $event): void
    {
        Log::info('Comment added', ['title' => $event->comment->content]);
    }
}
