<?php

namespace App\Jobs;

use App\Events\UserCreated;
use App\Models\BulkUserFailure;
use App\Models\BulkUserImport;
use App\Models\User;
use App\Notifications\BulkImportCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Queue\SerializesModels;
class ProcessBulkUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $bulkId;
    public array $users;

    public function __construct(int $bulkId, array $users)
    {
        $this->bulkId = $bulkId;
        $this->users = $users;
    }

    public function handle(): void
    {
        $bulk = BulkUserImport::find($this->bulkId);
        $bulk->update(['status' => 'processing']);

        $stats = [
            'success' => 0,
            'failed' => 0,
        ];

        collect($this->users)->chunk(500)->each(function ($chunk) use ($bulk, &$stats) {
            $this->processChunk($chunk, $bulk, $stats);
        });

        $bulk->update([
            'status' => 'done',
            'success_count' => $stats['success'],
            'failed_count' => $stats['failed'],
        ]);

        $this->notifyUser($bulk);
    }

    private function processChunk($chunk, $bulk, &$stats): void
    {
        foreach ($chunk as $user) {
            $validator = $this->validateUser($user);

            if ($validator->fails()) {
                $stats['failed']++;
                BulkUserFailure::create([
                    'bulk_user_import_id' => $bulk->id,
                    'email' => $user['email'] ?? null,
                    'reason' => $validator->errors()->all(),
                ]);

                continue;
            }

            $this->insertUser($user);
            $stats['success']++;
        }
    }

    private function validateUser(array $user)
    {
        return Validator::make($user, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'nullable|exists:roles,name',
        ]);
    }

    private function insertUser(array $user):void
    {
        $password = Hash::make('password');

        $user = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $password,
            'tenant_id' => auth()->user()->tenant_id,
        ]);
        UserCreated::dispatch($user);
        $roleToAssign = $user['role'] ?? User::DEFAULTRULE;
        $user->syncRoles([$roleToAssign]);
    }

    private function notifyUser(BulkUserImport $bulk):void
    {
        $user = User::find($bulk->requested_by);
        $user->notify(new BulkImportCompleted($bulk));
    }
}
