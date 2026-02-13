<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CacheObserver
{
    private function clearCache(User $user): void
    {
        $tenantId = $user->tenant_id;
        Cache::tags(["users", "tenant_{$tenantId}"])->flush();
        $today = Carbon::today()->toDateString();
        Cache::forget("daily_statistic_{$tenantId}_{$today}");
        Cache::forget("daily_statistic_all_{$today}");
    }

    public function created(User $user): void
    {
        $this->clearCache($user);
    }

    public function updated(User $user): void
    {
        $this->clearCache($user);
    }

    public function deleted(User $user): void
    {
        $this->clearCache($user);
    }
}