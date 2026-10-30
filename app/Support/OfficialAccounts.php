<?php

namespace App\Support;

class OfficialAccounts
{
    /**
     * @return list<array{bank: string, number: string, holder: string}>
     */
    public static function all(): array
    {
        return [
            ['bank' => 'BSI', 'number' => '1926202002', 'holder' => 'NU Care Lazisnu PWNU Kalbar'],
            ['bank' => 'Mandiri', 'number' => '1460019889455', 'holder' => 'NU Care Lazisnu PWNU Kalbar'],
        ];
    }
}
