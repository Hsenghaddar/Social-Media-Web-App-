<?php

namespace App\Listeners;

use App\Events\UserNotified;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserNotified $event): void
    {
        Notification::create([
            "user_id"=>$event->user->id,
            "message"=>$event->message,
            "link"=>$event->link
        ]);
    }
}
