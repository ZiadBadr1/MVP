<?php

namespace App\Http\Controllers\User;

use App\Helper\ApiResponse;
use App\Helper\PaginationFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->getAll();
        return ApiResponse::success(UserResource::collection($users),"This is all users");
    }

    public function store(UserRequest $request)
    {
        $user = $this->service->create($request->validated());
        return ApiResponse::success(new UserResource($user),"User created successfully",201);
    }

    public function show(User $user)
    {
        return ApiResponse::success(new UserResource($user),"User Retrieved successfully");
    }

    public function update(UserRequest $request, User $user)
    {
        $user = $this->service->update($user, $request->validated());
        return ApiResponse::success(new UserResource($user),"User Updated successfully");
    }

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return ApiResponse::success([],"User Deleted successfully" , 204);
    }
}
