@extends('layouts.public')

@section('title', $campaign->title)
@section('description', \Illuminate\Support\Str::limit(trim((string) preg_replace('/\s+/u', ' ', strip_tags((string) $campaign->story_content))) ?: $campaign->title, 155))
@if ($campaign->cover)
    @section('og_image', $campaign->cover->publicUrl())
@endif

@section('content')
    @php
        $paragraphs = array_values(array_filter(
            preg_split("/\r\n|\n|\r/", trim((string) $campaign->story_content)) ?: [],
            fn (string $paragraph): bool => trim($paragraph) !== '',
        ));
        $share = 'https://wa.me/?text='.rawurlencode($campaign->title.' — '.route('campaign.show', $campaign->slug));
    @endphp
    <article class="border-b border-line bg-white">
        @if ($campaign->cover)
            <img src="{{ $campaign->cover->publicUrl() }}" alt="{{ $campaign->title }}" class="h-48 w-full object-cover md:h-64">
        @else
            <div class="h-48 bg-gradient-to-br from-nu-dark to-nu md:h-64"></div>
        @endif
        <div class="mx-auto max-w-6xl px-4 py-8">
            <p class="text-sm font-semibold text-nu">{{ $campaign->branch?->name }}</p>
            <h1 class="mt-2 max-w-3xl text-3xl font-bold tracking-tight md:text-4xl">{{ $campaign->title }}</h1>
            <p class="mt-3 text-sm font-semibold text-amber-800">{{ $campaign->fund_book->getLabel() }} · {{ $campaign->pillar?->name }}</p>
            <p class="mt-4 text-lg tabular-nums"><span class="font-bold">@rupiah($campaign->collected_amount)</span> <span class="text-muted">dari @rupiah($campaign->target_amount)</span></p>
            <div class="mt-3 h-2 max-w-xl overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-gold" style="width: {{ $campaign->progressPercent() }}%"></div>
            </div>
            @if ($campaign->daysRemainingLabel())
                <p class="mt-2 text-sm text-muted">{{ $campaign->daysRemainingLabel() }}</p>
            @endif
        </div>
    </article>
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 lg:grid-cols-[minmax(0,1fr)_300px]">
        <div class="space-y-10">
            <section>
                <h2 class="text-xl font-bold">Cerita</h2>
                <div class="mt-3 space-y-3">
                    @forelse ($paragraphs as $paragraph)
                        <p class="leading-7 text-slate-700">{{ $paragraph }}</p>
                    @empty
                        <p class="text-muted">Cerita kampanye belum diisi.</p>
                    @endforelse
                </div>
            </section>
            <section>
                <h2 class="text-xl font-bold">Donatur</h2>
                <p class="mt-2 text-sm text-muted">Hanya donasi yang sudah disetujui bendahara.</p>
                <div class="mt-4 grid gap-3">
                    @forelse ($supporters as $supporter)
                        <div class="rounded-2xl border border-line bg-white p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold">{{ $supporter->is_anonymous ? 'Hamba Allah' : $supporter->donor_name }}</p>
                                <p class="tabular-nums font-semibold">@rupiah($supporter->amount)</p>
                            </div>
                            @if ($supporter->prayer_message)
                                <p class="mt-2 text-sm text-muted">{{ $supporter->prayer_message }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-muted">Belum ada donasi yang disetujui.</p>
                    @endforelse
                </div>
            </section>
            @if ($related->isNotEmpty())
                <section>
                    <h2 class="text-xl font-bold">Kampanye pilar yang sama</h2>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        @foreach ($related as $item)
                            <x-campaign-card :campaign="$item" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
        <aside class="hidden lg:block">
            <div class="sticky top-24 rounded-3xl border border-line bg-white p-5">
                <p class="text-sm text-muted">Donasi untuk kampanye ini</p>
                <p class="mt-1 font-semibold">{{ $campaign->fund_book->getLabel() }}</p>
                <a class="btn-gold mt-4 w-full" href="{{ route('donasi.create', ['campaign' => $campaign->slug]) }}">Donasi Sekarang</a>
                <a class="btn-line mt-2 w-full" href="{{ $share }}" target="_blank" rel="noopener">Bagikan ke WhatsApp</a>
            </div>
        </aside>
    </div>
    <div class="fixed inset-x-0 bottom-16 z-30 border-t border-line bg-white p-3 lg:hidden">
        <a class="btn-gold w-full" href="{{ route('donasi.create', ['campaign' => $campaign->slug]) }}">Donasi Sekarang</a>
    </div>
@endsection
