<?php

use App\Models\DailyStatistic;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    DailyStatistic::firstOrCreate([
        'date' => now()->toDateString(),
    ]);
})->dailyAt('00:00')->timezone('GMT');