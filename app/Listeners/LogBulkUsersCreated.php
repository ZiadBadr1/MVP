<?php

namespace App\Listeners;

use App\Events\UsersBulkInserted;
use App\Helper\ActivityLogger;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogBulkUsersCreated implements ShouldQueue
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
    public function handle(UsersBulkInserted $event): void
    {
        User::whereIn('id', $event->userIds)
            ->chunk(500, function ($users) use ($event) {
                foreach ($users as $user) {
                    ActivityLogger::logCreated(
                        $user,
                        $event->performedBy
                    );
                }
            });
    }
}
