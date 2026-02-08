<?php

namespace App\Listeners;

use App\Events\UsersBulkInserted;
use App\Models\MessageLog;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBulkWelcomeEmails implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UsersBulkInserted $event): void
    {
        User::whereIn('id', $event->userIds)
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
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
                    }
                }
            });
    }
}