<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, User $user): bool
    {
        if ($authUser->is_super_admin) {
            return true;
        }

        return $authUser->tenant_id === $user->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */

    public function update(User $authUser, User $user): bool
    {
        if ($authUser->is_super_admin) {
            return true;
        }

        return $authUser->tenant_id === $user->tenant_id
            && (
                $authUser->id === $user->id
                || $authUser->hasRole('admin')
            );
    }
    /**
     * Determine whether the user can delete the model.
     */

    public function delete(User $authUser, User $user): bool
    {
        if ($authUser->is_super_admin) {
            return true;
        }
        return $authUser->tenant_id === $user->tenant_id
            && (
                $authUser->id === $user->id
                || $authUser->hasRole('admin')
            );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
