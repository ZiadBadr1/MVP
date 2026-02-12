<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CacheObserver
{
    private function clearCache():void
    {
        Cache::tags(['users'])->flush();
        $today = Carbon::today()->toDateString();
        Cache::forget("daily_statistic_$today");
    }

    public function created(User $user): void
    {
        $this->clearCache();
    }

    public function updated(User $user): void
    {
        $this->clearCache();
    }

    public function deleted(User $user): void
    {
        $this->clearCache();
    }
}