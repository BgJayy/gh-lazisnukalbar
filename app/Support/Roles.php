<?php

namespace App\Support;

final class Roles
{
    public const SUPER_ADMIN = 'Super Admin';

    public const FINANCE = 'Finance';

    public const BRANCH_ADMIN = 'Admin Cabang';

    public const EDITOR = 'Editor';

    public const DONOR = 'Donatur';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::SUPER_ADMIN,
            self::FINANCE,
            self::BRANCH_ADMIN,
            self::EDITOR,
            self::DONOR,
        ];
    }

    /**
     * @return list<string>
     */
    public static function panel(): array
    {
        return [
            self::SUPER_ADMIN,
            self::FINANCE,
            self::BRANCH_ADMIN,
            self::EDITOR,
        ];
    }
}
