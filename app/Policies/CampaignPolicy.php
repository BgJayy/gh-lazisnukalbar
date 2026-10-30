<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use App\Support\Roles;

class CampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([Roles::FINANCE, Roles::BRANCH_ADMIN, Roles::EDITOR]);
    }

    public function view(User $user, Campaign $campaign): bool
    {
        if ($user->hasRole(Roles::BRANCH_ADMIN)) {
            return (int) $campaign->branch_id === (int) $user->branch_id;
        }

        return $user->hasAnyRole([Roles::FINANCE, Roles::EDITOR]);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Roles::BRANCH_ADMIN) && filled($user->branch_id);
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $user->hasRole(Roles::BRANCH_ADMIN)
            && (int) $campaign->branch_id === (int) $user->branch_id;
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $this->update($user, $campaign);
    }
}
