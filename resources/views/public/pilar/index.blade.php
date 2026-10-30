@extends('layouts.public')

@section('title', 'Pilar Program')

@section('content')
    <x-page-header kicker="Program" title="5 Pilar Program NU Care-LAZISNU" lead="Cerdas, Berdaya, Sehat, Damai, dan Hijau. Kampanye yang tayang memilih salah satu pilar ini." />
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 md:grid-cols-2 lg:grid-cols-5">
        @foreach ($pillars as $pillar)
            <a href="{{ route('pilar.show', $pillar) }}" class="flex flex-col rounded-3xl border border-line bg-white p-5 hover:border-nu">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-nu-soft text-nu">
                    <x-pillar-icon :name="$pillar->icon" />
                </span>
                <h2 class="mt-4 text-lg font-bold">{{ $pillar->name }}</h2>
                <p class="mt-2 text-sm leading-6 text-muted">{{ $pillar->description }}</p>
                @if ($pillar->programs)
                    <ul class="mt-4 grid gap-1 text-sm">
                        @foreach ($pillar->programs as $program)
                            <li>{{ $program }}</li>
                        @endforeach
                    </ul>
                @endif
            </a>
        @endforeach
    </div>
@endsection
