<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAll($count = 10)
    {
        return User::paginate($count);
    }

    public function create(array $data): User
    {
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
