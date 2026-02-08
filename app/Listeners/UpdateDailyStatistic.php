<?php

namespace App\Listeners;

use App\Events\UsersBulkInserted;
use App\Models\DailyStatistic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDailyStatistic implements ShouldQueue
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
        $count = count($event->userIds);

        if ($count === 0) {
            return;
        }

        $date = now()->toDateString();

        DailyStatistic::query()
            ->firstOrCreate(['date' => $date])
            ->increment('total_users_created', $count);
    }
}
