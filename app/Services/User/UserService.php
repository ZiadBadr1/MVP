<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getAll()
    {
        return User::all();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function createBulk(array $data): array
    {
        $users = $this->prepareUsers($data['users']);

        try {
            $this->insertUsers($users);

            return [
                'success' => true,
                'inserted_count' => $users->count(),
            ];
        } catch (\Throwable $e) {
            Log::error('Bulk user insert failed', [
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException(
                'Failed to insert users. Please check the input data.'
            );
        }
    }
    private function prepareUsers(array $users): \Illuminate\Support\Collection
    {
        $now = now();
        $password = Hash::make('password');

        return collect($users)->map(fn ($user) => [
            'name'       => $user['name'],
            'email'      => $user['email'],
            'password'   => $password,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function insertUsers($users): void
    {
        $users->chunk(500)->each(function ($chunk) {
            DB::transaction(function () use ($chunk) {
                User::insert($chunk->toArray());
            });
        });
    }
}
