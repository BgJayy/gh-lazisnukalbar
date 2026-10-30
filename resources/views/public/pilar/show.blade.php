@extends('layouts.public')

@section('title', $pillar->name)
@section('description', \Illuminate\Support\Str::limit(trim((string) ($pillar->description ?: $pillar->name)), 155))

@section('content')
    <x-page-header kicker="Pilar program" :title="$pillar->name" :lead="$pillar->description" />
    <div class="mx-auto max-w-6xl space-y-10 px-4 py-12">
        <div class="flex items-center gap-3 text-nu">
            <span class="grid h-12 w-12 place-items-center rounded-full bg-nu-soft">
                <x-pillar-icon :name="$pillar->icon" />
            </span>
            <p class="text-sm font-semibold">{{ $pillar->name }}</p>
        </div>
        @if ($pillar->programs)
            <ul class="grid max-w-3xl gap-2">
                @foreach ($pillar->programs as $program)
                    <li class="rounded-2xl border border-line bg-white px-4 py-3 text-sm font-semibold">{{ $program }}</li>
                @endforeach
            </ul>
        @endif
        <a class="btn-nu" href="{{ route('campaign.index', ['pilar' => $pillar->slug]) }}">Lihat kampanye pilar ini</a>
        @if ($campaigns->isNotEmpty())
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($campaigns as $campaign)
                    <x-campaign-card :campaign="$campaign" />
                @endforeach
            </div>
        @endif
    </div>
@endsection
