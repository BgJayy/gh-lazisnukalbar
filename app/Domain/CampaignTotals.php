<?php

namespace App\Domain;

use App\Enums\PaymentStatus;
use App\Models\Campaign;
use App\Models\Donation;

class CampaignTotals
{
    public static function sync(Donation $donation): void
    {
        $ids = array_unique(array_filter([
            $donation->campaign_id ? (int) $donation->campaign_id : null,
            $donation->getRawOriginal('campaign_id') ? (int) $donation->getRawOriginal('campaign_id') : null,
        ]));

        foreach ($ids as $id) {
            Campaign::query()->find($id)?->refreshCollectedAmount();
        }
    }

    public static function paidTotal(Campaign $campaign): string
    {
        return (string) $campaign->donations()
            ->where('payment_status', PaymentStatus::Paid->value)
            ->sum('amount');
    }
}
