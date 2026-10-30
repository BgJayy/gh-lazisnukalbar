@extends('layouts.public')

@section('title', 'Sekilas NU Care - LAZISNU Kalbar')
@section('description', 'Lembaga Amil Zakat, Infak, dan Sedekah Nahdlatul Ulama di Kalimantan Barat.')

@section('content')
    <section class="bg-nu-deep text-white">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-14 md:grid-cols-[minmax(0,1.15fr)_minmax(16rem,0.85fr)] md:py-20">
            <div>
                <h1 class="max-w-xl text-4xl font-bold leading-tight tracking-tight md:text-5xl">Sekilas NU Care - LAZISNU Kalbar</h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-white/90">Lembaga Amil Zakat, Infak, dan Sedekah Nahdlatul Ulama (LAZISNU) merupakan lembaga nirlaba milik Nahdlatul Ulama yang berkhidmat dalam pemberdayaan masyarakat melalui pendayagunaan dana zakat, infak, sedekah, dan wakaf (ZISWAF).</p>
                <p class="mt-6 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path d="M4 20h16" stroke-linecap="round" />
                        <path d="M6 20V8l6-4 6 4v12" />
                        <path d="M10 20v-5h4v5" />
                    </svg>
                    LAZNAS Resmi
                </p>
            </div>
            @if (is_file(public_path('images/logo-lazisnu-white.png')))
                <img src="{{ asset('images/logo-lazisnu-white.png') }}" alt="NU Care-LAZISNU" class="mx-auto w-full max-w-sm">
            @endif
        </div>
    </section>

    <section class="mx-auto grid max-w-3xl gap-6 px-4 py-14 sm:grid-cols-2" aria-label="Pengurus">
        @foreach ([
            ['abdul-kholiq.jpg', 'ABDUL KHOLIQ, S.Sos', 'Ketua NU Care-LAZISNU PWNU KALBAR'],
            ['habib-ali-hasan.jpg', 'HABIB ALI HASAN AL BAHAR', 'Ketua NU Care-LAZISNU PBNU'],
        ] as [$photo, $name, $role])
            <article class="rounded-[1.75rem] bg-white px-6 py-8 text-center shadow-[0_18px_40px_-28px_rgba(15,23,42,0.45)]">
                <div class="mx-auto grid h-40 w-40 place-items-center rounded-full bg-white shadow-[0_0_0_6px_#008C45]">
                    <img src="{{ asset('images/pengurus/'.$photo) }}" alt="{{ $name }}" width="800" height="800" class="h-36 w-36 rounded-full object-cover">
                </div>
                <h2 class="mt-6 text-lg font-bold leading-snug">{{ $name }}</h2>
                <p class="mt-2 text-sm font-semibold leading-6 text-nu">{{ $role }}</p>
            </article>
        @endforeach
    </section>
@endsection
