<?php

namespace App\Models;

use App\Enums\FundBook;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['bank_name', 'account_number', 'account_holder', 'fund_book', 'is_active'])]
class BankAccount extends Model
{
    protected function casts(): array
    {
        return [
            'fund_book' => FundBook::class,
            'is_active' => 'boolean',
        ];
    }
}
