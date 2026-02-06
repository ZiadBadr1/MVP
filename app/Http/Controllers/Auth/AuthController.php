<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ReqisterRequest;
use App\Services\Auth\AuthService;
use App\Helper\ApiResponse;
use App\Http\Resources\Auth\UserResource;
class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (isset($result['error'])) {
            return ApiResponse::error([], $result['error'], 400);
        }

        return ApiResponse::success(
            $result,
            'User Login Successfully',
        );
    }

    public function register(ReqisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return ApiResponse::success(
            new UserResource($user),
            'User Registered Successfully',
            201
        );
    }

    public function logout()
    {
        $this->authService->logout();

        return ApiResponse::success(
            [],
            'User Logout Successfully'
        );
    }
}
