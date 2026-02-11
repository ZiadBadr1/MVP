<?php

namespace App\Services\RBAC;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    public function list()
    {
        return Permission::all();
    }

    public function create(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => 'api',
        ]);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update([
            'name' => $data['name'],
        ]);

        return $permission;
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}