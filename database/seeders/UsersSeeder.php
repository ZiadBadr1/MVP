<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $superRole = Role::where('name', 'super_admin')->where('guard_name', 'api')->first();
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'api')->first();
        $managerRole = Role::where('name', 'manager')->where('guard_name', 'api')->first();
        $userRole = Role::where('name', 'user')->where('guard_name', 'api')->first();
        $tenant = Tenant::first();

        $super = User::firstOrCreate(
            ['email' => 'super@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_super_admin' => true,
            ]
        );

        $super->assignRole($superRole);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'tenant_id' => $tenant->id,
            ]
        );
        $admin->assignRole($adminRole);

        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password123'),
                'tenant_id' => $tenant->id,
            ]
        );
        $manager->assignRole($managerRole);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('password123'),
                'tenant_id' => $tenant->id,
            ]
        );
        $user->assignRole($userRole);
    }
}
