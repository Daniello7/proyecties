<?php

namespace App\Policies;

//use App\Models\PersonEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonEntryPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['porter']);
    }

    public function update(User $user): bool
    {
        return $user->hasRole(['porter']);
    }

    public function delete(User $user): bool
    {
        return false;
    }

    public function cancel(User $user): bool
    {
        return $user->hasRole(['porter']);
    }
}
