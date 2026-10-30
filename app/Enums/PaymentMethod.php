<?php

namespace App\Enums;

use App\Support\HasOptions;
use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    use HasOptions;

    case ManualTransfer = 'manual_transfer';

    public function getLabel(): string
    {
        return match ($this) {
            self::ManualTransfer => 'Transfer manual',
        };
    }
}
