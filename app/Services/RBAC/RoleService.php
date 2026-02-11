<?php

namespace App\Services\RBAC;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function list()
    {
        return Role::with('permissions')->get();
    }

    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data) {

            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'api'
            ]);

            if (!empty($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role->load('permissions');
        });
    }

    public function update(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {

            if (isset($data['name'])) {
                $role->update(['name' => $data['name']]);
            }

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role->load('permissions');
        });
    }

    public function delete(Role $role): void
    {
        DB::transaction(function () use ($role) {
            $role->syncPermissions([]);
            $role->delete();
        });
    }
}
