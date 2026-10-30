<?php

namespace App\Domain;

use App\Enums\DonationChannel;
use App\Enums\FundBook;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Validation\ValidationException;

class DonationRules
{
    public static function assert(Donation $donation): void
    {
        $channel = $donation->channel;
        $book = $donation->fund_book;

        if (! $channel instanceof DonationChannel || ! $book instanceof FundBook) {
            throw ValidationException::withMessages([
                'channel' => 'Kanal dan buku kas wajib diisi.',
            ]);
        }

        if ($channel !== DonationChannel::Campaign) {
            $required = $channel->requiredBook();

            if ($required !== null && $book !== $required) {
                throw ValidationException::withMessages([
                    'fund_book' => 'Buku kas tidak sesuai dengan kanal donasi.',
                ]);
            }

            return;
        }

        if (! $donation->campaign_id) {
            throw ValidationException::withMessages([
                'campaign_id' => 'Kampanye wajib diisi untuk kanal ini.',
            ]);
        }

        $campaign = $donation->relationLoaded('campaign')
            ? $donation->campaign
            : Campaign::query()->find($donation->campaign_id);

        if (! $campaign) {
            throw ValidationException::withMessages([
                'campaign_id' => 'Kampanye tidak ditemukan.',
            ]);
        }

        if ($book === FundBook::Zakat) {
            if ($campaign->fund_book !== FundBook::Zakat && ! $campaign->accepts_zakat) {
                throw ValidationException::withMessages([
                    'fund_book' => 'Kampanye ini tidak menerima dana zakat.',
                ]);
            }
        } elseif ($book !== $campaign->fund_book) {
            throw ValidationException::withMessages([
                'fund_book' => 'Buku kas donasi harus sama dengan buku kas kampanye.',
            ]);
        }

        if ((int) $donation->branch_id !== (int) $campaign->branch_id) {
            throw ValidationException::withMessages([
                'branch_id' => 'Cabang donasi harus sama dengan cabang kampanye.',
            ]);
        }
    }
}
