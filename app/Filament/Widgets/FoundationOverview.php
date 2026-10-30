<?php

namespace App\Filament\Widgets;

use App\Enums\CampaignStatus;
use App\Enums\PaymentStatus;
use App\Models\Campaign;
use App\Models\Donation;
use App\Support\Roles;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FoundationOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole([
            Roles::SUPER_ADMIN,
            Roles::FINANCE,
            Roles::BRANCH_ADMIN,
        ]) ?? false;
    }

    protected function getStats(): array
    {
        $donations = Donation::query()->visibleTo();
        $campaigns = Campaign::query()->visibleTo();

        $collected = (clone $donations)
            ->where('payment_status', PaymentStatus::Paid)
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('amount');

        return [
            Stat::make('Menunggu verifikasi', (string) (clone $donations)->where('payment_status', PaymentStatus::Pending)->count()),
            Stat::make('Terkumpul bulan ini', 'Rp '.number_format((float) $collected, 0, ',', '.')),
            Stat::make('Kampanye tayang', (string) (clone $campaigns)->where('status', CampaignStatus::Published)->count()),
        ];
    }
}
