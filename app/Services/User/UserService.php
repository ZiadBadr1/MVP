<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Tenant\TenantService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAll($count = 10)
    {
        $page = request()->get('page', 1);
        $tenantId = app(TenantService::class)->getTenant();
        $cacheKey = "users_tenant_{$tenantId}_page_{$page}_count_{$count}";
        return Cache::tags(["users", "tenant_$tenantId"])->remember($cacheKey, 3600, function () use ($count) {
            return User::paginate($count);
        });
    }

    public function create(array $data): User
    {
        if (! auth()->user()->is_super_admin) {
            unset($data['tenant_id']);
        }
        return DB::transaction(function () use ($data) {

            $user = User::create($data);
            $this->assignRole($user, $data['role'] ?? null);

            return $user->load('roles');
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            $this->assignRole($user, $data['role'] ?? null);

            return $user->load('roles');
        });
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    protected function assignRole(User $user, ?string $role = null): void
    {
        $roleToAssign = $role ?? User::DEFAULTRULE;
        $user->syncRoles([$roleToAssign]);
    }
}
