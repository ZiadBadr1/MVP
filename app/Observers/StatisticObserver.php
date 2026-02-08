<?php

namespace App\Observers;

use App\Jobs\UpdateDailyStats;
use App\Models\User;

class StatisticObserver
{
    public function created(User $user): void
    {
        UpdateDailyStats::dispatch('total_users_created');
    }

    public function updated(User $user): void
    {
        UpdateDailyStats::dispatch('total_users_updated');
    }

    public function deleted(User $user): void
    {
        UpdateDailyStats::dispatch('total_users_deleted');
    }
}