<?php

namespace App\Enums;

use App\Support\HasOptions;
use Filament\Support\Contracts\HasLabel;

enum BranchType: string implements HasLabel
{
    use HasOptions;

    case Pw = 'pw';
    case Pc = 'pc';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pw => 'Pengurus Wilayah',
            self::Pc => 'Pengurus Cabang',
        };
    }
}
