<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use App\Support\Roles;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function delete(User $user, Setting $setting): bool
    {
        return false;
    }
}
