<?php

namespace App\Policies;

use App\Models\Pillar;
use App\Models\User;

class PillarPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Pillar $pillar): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Pillar $pillar): bool
    {
        return false;
    }

    public function delete(User $user, Pillar $pillar): bool
    {
        return false;
    }
}
