<?php

namespace App\Services\Auth;

use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function login(array $data)
    {
        try {
            if (! $token = JWTAuth::attempt($data)) {
                return [
                    'success' => false,
                    'message' => 'invalid_credentials'
                ];
            }
        } catch (JWTException $e) {
            return ['error' => 'could_not_create_token'];
        }

        return [
            'user' => new UserResource(auth()->user()),
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }

    public function register(array $data)
    {
        return User::create($data);
    }

    public function logout(): true
    {
        auth('api')->logout();
        return true;
    }
}