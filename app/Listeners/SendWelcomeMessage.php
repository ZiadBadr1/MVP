<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\MessageLog;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeMessage implements ShouldQueue
{
    use InteractsWithQueue;
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
    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        try {
            $user->notify(new WelcomeNotification());

            MessageLog::create([
                'user_id' => $user->id,
                'message_type' => 'welcome_email',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            MessageLog::create([
                'user_id' => $user->id,
                'message_type' => 'welcome_email',
                'status' => 'failed',
            ]);
            throw $e;
        }
    }
}
