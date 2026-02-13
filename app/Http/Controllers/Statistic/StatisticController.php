<?php

namespace App\Http\Controllers\Statistic;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\StatisticResource;
use App\Models\DailyStatistic;
use App\Services\Tenant\TenantService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;

class StatisticController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:view_statistics'),
        ];
    }

    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $tenantService = app(TenantService::class);
        $isSuperAdmin = auth()->user()->hasRole('super_admin');

        if ($isSuperAdmin) {

            $cacheKey = "daily_statistic_all_{$today}";

            $statistics = Cache::remember(
                $cacheKey,
                now()->addMinutes(10),
                function () use ($today) {
                    return DailyStatistic::withoutGlobalScope('tenant')
                        ->whereDate('date', $today)
                        ->get();
                }
            );
            return ApiResponse::success(StatisticResource::collection($statistics), "This is Today Statistic");
        } else {

            $tenantId = $tenantService->getTenant();
            $cacheKey = "daily_statistic_{$tenantId}_{$today}";

            $statistics = Cache::remember(
                $cacheKey,
                now()->addMinutes(10),
                function () use ($today) {
                    return DailyStatistic::whereDate('date', $today)
                        ->first();
                });
            return ApiResponse::success(new StatisticResource($statistics), "This is Today Statistic");
        }
    }


}