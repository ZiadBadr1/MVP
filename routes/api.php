<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')->group(function () {
    Route::post('/register',  [AuthController::class, 'register']);
    Route::post('/login',  [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function (){
        Route::post('/logout',  [AuthController::class, 'logout']);

        Route::apiResource("users", UserController::class);
    });

});