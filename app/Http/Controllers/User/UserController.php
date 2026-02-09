<?php

namespace App\Http\Controllers\User;

use App\Events\UserCreated;
use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserBulkRequest;
use App\Http\Requests\User\UserBulkRequestV2;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\User\BulkUserImportResource;
use App\Models\User;
use App\Services\User\BulkUserService;
use App\Services\User\UserService;

class UserController extends Controller
{

    public function __construct(
        protected UserService $service,
        protected BulkUserService $bulkService,
    )
    {}

    public function index()
    {
        $users = $this->service->getAll();
        return ApiResponse::success(UserResource::collection($users),"This is all users");
    }

    public function store(UserRequest $request)
    {
        $user = $this->service->create($request->validated());
        UserCreated::dispatch($user);
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

    public function storeBulkV1(UserBulkRequest $request)
    {
        $response = $this->bulkService->createBulk($request->validated());
        return ApiResponse::success($response,"Users created successfully",201);
    }
    public function storeBulkV2(UserBulkRequestV2 $request)
    {
        $response = $this->bulkService->processBulkCreation($request->validated());
        return ApiResponse::success(new BulkUserImportResource($response),"Users created successfully",201);
    }
}
