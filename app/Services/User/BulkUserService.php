<?php

namespace App\Services\User;

use App\Events\UsersBulkInserted;
use App\Jobs\ProcessBulkUsers;
use App\Models\BulkUserImport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BulkUserService
{
    // Start old Way
    public function createBulk(array $data): array
    {
        $users = $this->prepareUsers($data['users']);
        try {
            $ids = $this->insertUsers($users);
            UsersBulkInserted::dispatch($ids, Auth::id());
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
    private function insertUsers($users): array
    {
        $insertedIds = [];

        $users->chunk(500)->each(function ($chunk) use (&$insertedIds) {

            User::insert($chunk->toArray());

            $ids = User::whereIn('email', $chunk->pluck('email'))
                ->pluck('id')
                ->toArray();

            $insertedIds = array_merge($insertedIds, $ids);
        });

        return $insertedIds;
    }
    // Finish old way

    // Start New Way More Optimized
    public function processBulkCreation(array $data)
    {
        $bulk = BulkUserImport::create([
            'requested_by' => Auth::id(),
            'status'       => 'pending',
            'total_count'  => count($data['users']),
        ]);
        ProcessBulkUsers::dispatch($bulk->id,$data['users']);
        return $bulk;
    }

}