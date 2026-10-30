<?php

namespace App\Models;

use App\Domain\AuditLogger;
use App\Domain\CampaignTotals;
use App\Domain\DonationRules;
use App\Enums\DonationChannel;
use App\Enums\FundBook;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Support\BranchAccess;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['invoice_number', 'user_id', 'branch_id', 'campaign_id', 'donor_name', 'donor_phone', 'donor_email', 'is_anonymous', 'prayer_message', 'fund_book', 'channel', 'amount', 'payment_method', 'payment_status', 'proof_of_transfer', 'paid_at'])]
class Donation extends Model
{
    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'fund_book' => FundBook::class,
            'channel' => DonationChannel::class,
            'amount' => 'decimal:2',
            'payment_method' => PaymentMethod::class,
            'payment_status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Donation $donation): void {
            if (blank($donation->invoice_number)) {
                $donation->invoice_number = 'LZ'.now()->format('ymdHis').Str::upper(Str::random(16));
            }
        });

        static::saving(function (Donation $donation): void {
            if ($donation->payment_status === PaymentStatus::Paid && blank($donation->paid_at)) {
                $donation->paid_at = now();
            }

            if ($donation->payment_status !== PaymentStatus::Paid) {
                $donation->paid_at = null;
            }

            DonationRules::assert($donation);
        });

        static::saved(function (Donation $donation): void {
            CampaignTotals::sync($donation);

            $previousStatus = $donation->getRawOriginal('payment_status');

            if ($donation->wasChanged('payment_status') && filled($previousStatus)) {
                AuditLogger::log($donation, 'status_changed', [
                    'payment_status' => $previousStatus,
                ], [
                    'payment_status' => $donation->payment_status?->value,
                ]);
            } elseif ($donation->wasRecentlyCreated) {
                AuditLogger::log($donation, 'created', null, [
                    'payment_status' => $donation->payment_status?->value,
                    'amount' => $donation->amount,
                    'fund_book' => $donation->fund_book?->value,
                ]);
            }
        });

        static::deleted(function (Donation $donation): void {
            CampaignTotals::sync($donation);
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeVisibleTo(Builder $query): Builder
    {
        return BranchAccess::apply($query);
    }
}
