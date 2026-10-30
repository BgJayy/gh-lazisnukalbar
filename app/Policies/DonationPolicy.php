<?php

namespace App\Policies;

use App\Enums\PaymentStatus;
use App\Models\Donation;
use App\Models\User;
use App\Support\Roles;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([Roles::FINANCE, Roles::BRANCH_ADMIN]);
    }

    public function view(User $user, Donation $donation): bool
    {
        if ($user->hasRole(Roles::BRANCH_ADMIN)) {
            return (int) $donation->branch_id === (int) $user->branch_id;
        }

        return $user->hasRole(Roles::FINANCE);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->hasRole(Roles::FINANCE);
    }

    public function delete(User $user, Donation $donation): bool
    {
        return false;
    }

    public function approve(User $user, Donation $donation): bool
    {
        return $user->hasRole(Roles::FINANCE)
            && $donation->payment_status === PaymentStatus::Pending;
    }
}
