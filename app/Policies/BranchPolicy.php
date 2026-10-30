<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use App\Support\Roles;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Branch $branch): bool
    {
        return false;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return false;
    }
}
