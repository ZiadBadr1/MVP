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
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        if (! $token = JWTAuth::claims(['tenant_id' => $user->tenant_id])->attempt($data)) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        return [
            'user' => new UserResource($user),
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }

    public function register(array $data)
    {
        $user = User::create($data);
        $user->syncRoles([User::DEFAULTRULE]);
        return $user;
    }

    public function logout(): true
    {
        auth('api')->logout();
        return true;
    }
}