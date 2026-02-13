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
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserService     $service,
        protected BulkUserService $bulkService,
    )
    {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('tenant'),
            new Middleware('permission:create_user', only: ['store', 'storeBulkV1', 'storeBulkV2']),
            new Middleware('permission:update_user', only: ['update']),
            new Middleware('permission:delete_user', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $start = microtime(true);
        $users = $this->service->getAll($request->count);
        $end = microtime(true);
        $executionTime = ($end - $start) * 1000;
        return ApiResponse::success($users, "Loaded in {$executionTime} ms");
    }

    public function store(UserRequest $request)
    {
        $user = $this->service->create($request->validated());
        UserCreated::dispatch($user,$user->tenant_id);
        return ApiResponse::success(new UserResource($user), "User created successfully", 201);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return ApiResponse::success(new UserResource($user), "User Retrieved successfully");
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user = $this->service->update($user, $request->validated());
        return ApiResponse::success(new UserResource($user), "User Updated successfully");
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->service->delete($user);
        return ApiResponse::success([], "User Deleted successfully", 204);
    }

    public function storeBulkV1(UserBulkRequest $request)
    {
        $response = $this->bulkService->createBulk($request->validated());
        return ApiResponse::success($response, "Users created successfully", 201);
    }

    public function storeBulkV2(UserBulkRequestV2 $request)
    {
        $response = $this->bulkService->processBulkCreation($request->validated());
        return ApiResponse::success(new BulkUserImportResource($response), "Users created successfully", 201);
    }
}
