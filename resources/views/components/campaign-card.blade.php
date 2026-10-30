@props(['campaign'])

<a href="{{ route('campaign.show', $campaign->slug) }}" class="flex h-full flex-col overflow-hidden rounded-3xl border border-line bg-white transition hover:-translate-y-0.5 hover:shadow-md">
    @if ($campaign->cover)
        <div class="relative">
            <img src="{{ $campaign->cover->publicUrl() }}" alt="{{ $campaign->title }}" class="h-40 w-full object-cover">
            <p class="absolute bottom-3 left-3 rounded-full bg-white/90 px-2 py-1 text-xs font-semibold text-nu">{{ $campaign->pillar?->name }}</p>
        </div>
    @else
        <div class="flex h-28 items-end bg-gradient-to-br from-nu-dark to-nu p-4 text-white">
            <p class="text-xs font-semibold">{{ $campaign->pillar?->name }}</p>
        </div>
    @endif
    <div class="flex flex-1 flex-col p-4">
        <p class="text-xs font-semibold text-nu">{{ $campaign->branch?->name }}</p>
        <h2 class="mt-1 text-lg font-bold leading-snug">{{ $campaign->title }}</h2>
        <p class="mt-2 text-xs font-semibold text-amber-800">{{ $campaign->fund_book->getLabel() }}</p>
        <p class="mt-4 text-sm tabular-nums"><span class="font-semibold">@rupiah($campaign->collected_amount)</span> <span class="text-muted">dari @rupiah($campaign->target_amount)</span></p>
        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-gold" style="width: {{ $campaign->progressPercent() }}%"></div>
        </div>
        @if ($campaign->daysRemainingLabel())
            <p class="mt-2 text-xs text-muted">{{ $campaign->daysRemainingLabel() }}</p>
        @endif
    </div>
</a>
