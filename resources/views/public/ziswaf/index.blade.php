@extends('layouts.public')

@section('title', match ($tab) {
    'infak' => 'Infak',
    'wakaf' => 'Wakaf',
    default => 'Zakat',
})
@section('description', match ($tab) {
    'infak' => 'Salurkan infak dan sedekah melalui NU Care-LAZISNU Kalimantan Barat.',
    'wakaf' => 'Salurkan wakaf melalui NU Care-LAZISNU Kalimantan Barat.',
    default => 'Hitung zakat penghasilan, emas, fitrah, dan harta lainnya di NU Care-LAZISNU Kalimantan Barat.',
})
@section('canonical', match ($tab) {
    'infak' => route('ziswaf.infak'),
    'wakaf' => route('ziswaf.wakaf'),
    default => route('ziswaf.zakat'),
})

@section('content')
    <section class="bg-nu text-white">
        <p class="mx-auto max-w-3xl px-4 pb-16 pt-8 text-center text-sm leading-6 md:text-base">Hitung zakat Anda dengan tepat, atau salurkan infak dan wakaf untuk amal yang terus mengalir. Pilih jenis amal di bawah.</p>
    </section>

    <div class="mx-auto -mt-8 max-w-xl px-4 pb-16">
        <div class="grid grid-cols-3 gap-1 rounded-2xl bg-white p-2 shadow-lg">
            @foreach ([
                ['zakat', 'Zakat', route('ziswaf.zakat')],
                ['infak', 'Infak', route('ziswaf.infak')],
                ['wakaf', 'Wakaf', route('ziswaf.wakaf')],
            ] as [$key, $label, $href])
                <a href="{{ $href }}" class="rounded-xl px-2 py-3 text-center text-sm font-semibold {{ $tab === $key ? 'bg-nu text-white' : 'text-ink hover:bg-canvas' }}" @if ($tab === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </div>

        <div class="mt-4 rounded-3xl bg-white p-5 shadow-lg sm:p-6">
            @if ($tab === 'zakat')
                @include('public.ziswaf.partials.zakat')
            @elseif ($tab === 'infak')
                @include('public.ziswaf.partials.infak')
            @else
                @include('public.ziswaf.partials.wakaf')
            @endif
        </div>
    </div>
@endsection
