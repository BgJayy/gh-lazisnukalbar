<?php

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\Roles;

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function view(User $user, AuditLog $auditLog): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AuditLog $auditLog): bool
    {
        return false;
    }

    public function delete(User $user, AuditLog $auditLog): bool
    {
        return false;
    }
}
