<?php

namespace App\Enums;

use App\Support\HasOptions;
use Filament\Support\Contracts\HasLabel;

enum CampaignStatus: string implements HasLabel
{
    use HasOptions;

    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Published => 'Tayang',
            self::Closed => 'Ditutup',
        };
    }
}
