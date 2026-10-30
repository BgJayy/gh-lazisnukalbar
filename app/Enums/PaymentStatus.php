<?php

namespace App\Enums;

use App\Support\HasOptions;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel
{
    use HasOptions;

    case Pending = 'pending';
    case Paid = 'paid';
    case Expired = 'expired';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Paid => 'Lunas',
            self::Expired => 'Kedaluwarsa',
        };
    }
}
