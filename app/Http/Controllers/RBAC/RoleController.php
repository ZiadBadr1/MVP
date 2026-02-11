<?php

namespace App\Http\Controllers\RBAC;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RBAC\Roles\StoreRoleRequest;
use App\Http\Requests\RBAC\Roles\UpdateRoleRequest;
use App\Http\Resources\RBAC\RoleResource;
use App\Services\RBAC\RoleService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:manage_roles'),
        ];
    }
    public function index()
    {
        return ApiResponse::success(RoleResource::collection($this->roleService->list()),"All Roles");
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());

        return ApiResponse::success(new RoleResource($role),"Created Successfully",201);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role = $this->roleService->update($role, $request->validated());

        return ApiResponse::success(new RoleResource($role),"Updated Successfully");
    }

    public function destroy(Role $role)
    {
        $this->roleService->delete($role);

        return ApiResponse::success([],"Deleted Successfully",204);
    }
}