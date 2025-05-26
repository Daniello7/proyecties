<?php

namespace App\Policies;

//use App\Models\PersonEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonEntryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasRole(['porter']);
    }

    public function update(User $user): bool
    {
        return $user->hasRole(['admin', 'porter']);
    }

    public function delete(User $user): bool
    {
        return $user->hasRole(['admin']);
    }

    public function cancel(User $user): bool
    {
        return $user->hasRole(['porter']);
    }
}
