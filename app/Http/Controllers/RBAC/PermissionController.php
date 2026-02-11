<?php

namespace App\Http\Controllers\RBAC;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RBAC\Permissions\StorePermissionRequest;
use App\Http\Requests\RBAC\Permissions\UpdatePermissionRequest;
use App\Http\Resources\RBAC\PermissionResource;
use App\Services\RBAC\PermissionService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:manage_permissions'),
        ];
    }

    public function index()
    {
        return ApiResponse::success(PermissionResource::collection($this->permissionService->list()),"This is All Permissions");
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionService->create(
            $request->validated()
        );
        return ApiResponse::success(new PermissionResource($permission),"Created Successfully",201);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission = $this->permissionService->update(
            $permission,
            $request->validated()
        );

        return ApiResponse::success(new PermissionResource($permission),"Updated Successfully");
    }

    public function destroy(Permission $permission)
    {
        $this->permissionService->delete($permission);

        return ApiResponse::success([],"Deleted Successfully",204);
    }
}