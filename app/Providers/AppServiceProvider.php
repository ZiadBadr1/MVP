<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\ModelActivityObserver;
use App\Observers\StatisticObserver;
use App\Services\Tenant\TenantService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantService::class, function () {
            return new TenantService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        User::observe(ModelActivityObserver::class);
//        User::observe(StatisticObserver::class);
    }
}
