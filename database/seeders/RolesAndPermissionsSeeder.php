<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create_user',
            'update_user',
            'delete_user',
            'view_statistics',
            'manage_roles',
            'manage_permissions',
            'manage_tenant',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        // Roles
        $super = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'api']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'api']);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);

        $super->givePermissionTo([
            'manage_tenant',
            'view_statistics',
            'create_user',
        ]);

        $admin->givePermissionTo([
            'create_user',
            'update_user',
            'delete_user',
            'view_statistics',
            'manage_roles',
            'manage_permissions',
        ]);

        $manager->givePermissionTo([
            'create_user',
            'update_user',
            'view_statistics',
        ]);

        $user->givePermissionTo([
            'view_statistics',
        ]);
    }
}