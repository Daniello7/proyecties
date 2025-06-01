<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeyControlPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'porter']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function update(User $user): bool
    {
        return $user->hasRole(['admin', 'porter']);
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function cancel(User $user): bool
    {
        return $user->hasRole(['admin', 'porter']);
    }
}
