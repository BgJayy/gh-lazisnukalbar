<?php

namespace App\Models;

use App\Domain\CampaignTotals;
use App\Enums\CampaignStatus;
use App\Enums\FundBook;
use App\Support\BranchAccess;
use Database\Factories\CampaignFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['branch_id', 'pillar_id', 'cover_media_id', 'title', 'slug', 'fund_book', 'accepts_zakat', 'target_amount', 'start_date', 'end_date', 'story_content', 'is_urgent', 'status'])]
class Campaign extends Model
{
    /** @use HasFactory<CampaignFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'fund_book' => FundBook::class,
            'accepts_zakat' => 'boolean',
            'is_urgent' => 'boolean',
            'status' => CampaignStatus::class,
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Campaign $campaign): void {
            if (blank($campaign->slug) && filled($campaign->title)) {
                $campaign->slug = Str::slug($campaign->title);
            }

            $user = auth()->user();

            if ($user && BranchAccess::limitsToBranch($user)) {
                $campaign->branch_id = $user->branch_id;
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function pillar(): BelongsTo
    {
        return $this->belongsTo(Pillar::class);
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'cover_media_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function refreshCollectedAmount(): void
    {
        $this->forceFill([
            'collected_amount' => CampaignTotals::paidTotal($this),
        ])->saveQuietly();
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', CampaignStatus::Published);
    }

    public function progressPercent(): int
    {
        $target = (float) $this->target_amount;

        if ($target <= 0) {
            return 0;
        }

        return (int) min(100, round(((float) $this->collected_amount / $target) * 100));
    }

    public function daysRemainingLabel(): ?string
    {
        if ($this->end_date === null) {
            return null;
        }

        $days = (int) round(now()->startOfDay()->diffInDays($this->end_date->copy()->startOfDay()));

        if ($days < 0) {
            return 'Berakhir';
        }

        if ($days === 0) {
            return 'Berakhir hari ini';
        }

        return $days.' hari lagi';
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
