<?php

namespace App\Enums;

use App\Support\HasOptions;
use Filament\Support\Contracts\HasLabel;

enum FundBook: string implements HasLabel
{
    use HasOptions;

    case Zakat = 'zakat';
    case InfakSedekah = 'infak_sedekah';
    case Wakaf = 'wakaf';

    public function getLabel(): string
    {
        return match ($this) {
            self::Zakat => 'Zakat',
            self::InfakSedekah => 'Infak/Sedekah',
            self::Wakaf => 'Wakaf',
        };
    }
}
