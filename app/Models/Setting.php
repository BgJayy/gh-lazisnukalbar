<?php

namespace App\Models;

use App\Domain\AuditLogger;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    public const GOLD_PRICE = 'gold_price_idr_per_gram';

    /**
     * @return array<string, string>
     */
    public static function allowedKeys(): array
    {
        return [
            self::GOLD_PRICE => 'Harga emas per gram (Rp)',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Setting $setting): void {
            if (! array_key_exists($setting->key, self::allowedKeys())) {
                throw ValidationException::withMessages([
                    'key' => 'Kunci pengaturan ini tidak diizinkan. Rahasia payment dan WhatsApp tinggal di environment.',
                ]);
            }
        });

        static::saved(function (Setting $setting): void {
            if ($setting->wasRecentlyCreated || $setting->wasChanged('value')) {
                AuditLogger::log($setting, $setting->wasRecentlyCreated ? 'created' : 'updated', [
                    'value' => $setting->getRawOriginal('value'),
                ], [
                    'value' => $setting->value,
                ]);
            }
        });
    }
}
