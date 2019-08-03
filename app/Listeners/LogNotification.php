<?php

namespace App\Listeners;

use App\Events\NotificationsUpdated;
use App\Models\User;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        // If this is a user object..
        if($user = User::find($event->notifiable->id)){
            broadcast(new NotificationsUpdated($user));
        }
    }
}
