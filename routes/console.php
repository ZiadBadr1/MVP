<?php

use App\Models\DailyStatistic;
use App\Models\Tenant;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $tenants = Tenant::all();

    foreach ($tenants as $tenant) {
        DailyStatistic::firstOrCreate([
            'date' => now()->toDateString(),
            'tenant_id' => $tenant->id,
        ]);
    }
})->dailyAt('00:00')->timezone('GMT');