<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('porter');
    }

    public function view(User $user, Package $package): bool
    {
        return $user->id === $package->internal_person_id;
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
        return $user->hasRole('admin');
    }

    public function restore(User $user): bool
    {
        return $user->hasRole(['porter', 'admin']);
    }

    public function forceDelete(User $user): bool
    {
        return $user->hasRole(['porter', 'admin']);
    }
}
