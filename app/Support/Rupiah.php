<?php

namespace App\Support;

final class Rupiah
{
    public static function format(float|int|string|null $amount): string
    {
        return 'Rp'.number_format((float) $amount, 0, ',', '.');
    }
}
