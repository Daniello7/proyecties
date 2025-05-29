<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin') && $ability !== 'create') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function update(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function cancel(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function delete(User $user): bool
    {
        return false;
    }

    public function restore(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function forceDelete(User $user): bool
    {
        return $user->hasRole('porter');
    }
}
