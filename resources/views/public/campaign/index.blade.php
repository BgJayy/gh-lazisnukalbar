@extends('layouts.public')

@section('title', 'Campaign')

@section('content')
    <x-page-header kicker="Campaign" title="Galang dana" lead="Kampanye yang sudah diterbitkan cabang. Donasi baru bisa dibuat dari halaman detail." />
    <div class="mx-auto max-w-6xl px-4 py-10">
        <div class="flex gap-2 overflow-auto pb-2">
            <a href="{{ route('campaign.index') }}" class="shrink-0 rounded-full border px-4 py-2 text-sm font-semibold {{ $current === '' ? 'border-nu bg-nu text-white' : 'border-line bg-white text-ink' }}">Semua</a>
            @foreach ($pillars as $pillar)
                <a href="{{ route('campaign.index', ['pilar' => $pillar->slug]) }}" class="shrink-0 rounded-full border px-4 py-2 text-sm font-semibold {{ $current === $pillar->slug ? 'border-nu bg-nu text-white' : 'border-line bg-white text-ink' }}">{{ $pillar->name }}</a>
            @endforeach
        </div>
        @if ($campaigns->isEmpty())
            <div class="mt-6 rounded-3xl border border-dashed border-line bg-white p-8">
                <p class="font-semibold">Belum ada kampanye pada saringan ini.</p>
                <p class="mt-2 text-sm text-muted">Kampanye draf tidak tampil di situs publik.</p>
            </div>
        @else
            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($campaigns as $campaign)
                    <x-campaign-card :campaign="$campaign" />
                @endforeach
            </div>
            <div class="mt-8">{{ $campaigns->links() }}</div>
        @endif
    </div>
@endsection
