<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Support\Facades\Log;

class SendCommentNotification
{

    /**
     * Handle the event.
     */
    public function handle(CommentAdded $event): void
    {
        Log::info('Comment added', ['title' => $event->comment->content]);
    }
}
