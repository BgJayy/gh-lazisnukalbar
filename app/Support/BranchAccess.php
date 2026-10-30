<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BranchAccess
{
    public static function limitsToBranch(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user?->hasRole(Roles::BRANCH_ADMIN) ?? false;
    }

    /**
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    public static function apply(Builder $query, string $column = 'branch_id'): Builder
    {
        $user = auth()->user();

        if (! self::limitsToBranch($user)) {
            return $query;
        }

        if (! $user->branch_id) {
            return $query->whereRaw('0 = 1');
        }

        return $query->where($query->getModel()->getTable().'.'.$column, $user->branch_id);
    }
}
