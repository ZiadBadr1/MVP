<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RBAC\PermissionController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\Statistic\StatisticController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('/register',  [AuthController::class, 'register']);
    Route::post('/login',  [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function (){
        Route::post('/logout',  [AuthController::class, 'logout']);

        Route::apiResource("users", UserController::class);
        Route::post('users-bulk', [UserController::class, 'storeBulkV1']);
        Route::post('users-bulk-v2', [UserController::class, 'storeBulkV2']);

        Route::apiResource("permissions", PermissionController::class);
        Route::apiResource("roles", RoleController::class);

        Route::get('get-statistics',StatisticController::class);
    });

});