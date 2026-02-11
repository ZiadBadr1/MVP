<?php

namespace App\Http\Controllers\Statistic;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\StatisticResource;
use App\Models\DailyStatistic;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        $today = Carbon::today();
        $statistic = DailyStatistic::whereDate('date', $today)->first();
        return ApiResponse::success(new StatisticResource($statistic),"This is Today Statistic");
    }


}
