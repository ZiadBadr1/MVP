<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DailyStatistic;
use Illuminate\Support\Facades\DB;

class StatisticObserver
{
    public function created(User $user): void
    {
        $this->incrementStat($user->tenant_id, 'total_users_created');
    }

    public function updated(User $user): void
    {
        $this->incrementStat($user->tenant_id, 'total_users_updated');
    }

    public function deleted(User $user): void
    {
        $this->incrementStat($user->tenant_id, 'total_users_deleted');
    }


    private function incrementStat($tenantId, string $column): void
    {
        $date = now()->toDateString();

        DailyStatistic::upsert(
            [
                [
                    'tenant_id' => $tenantId,
                    'date' => $date,
                    $column => 1,
                ]
            ],
            ['tenant_id', 'date'],
            [
                $column => DB::raw("$column + 1"),
            ]
        );
    }
}
