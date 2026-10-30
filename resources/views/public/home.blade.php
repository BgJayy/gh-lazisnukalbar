@extends('layouts.public')

@section('title', 'NU Care-LAZISNU Kalimantan Barat')
@section('description', 'Merawat kepedulian dari Pontianak sampai pelosok')

@section('content')
    <section class="bg-gradient-to-br from-nu-dark to-nu text-white">
        <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-14 md:py-20 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <p class="text-sm font-semibold text-emerald-100">NU Care-LAZISNU Kalimantan Barat</p>
                @if ($hero)
                    <h1 class="mt-3 max-w-xl text-4xl font-bold tracking-tight md:text-5xl">{{ $hero->title }}</h1>
                    <p class="mt-4 max-w-xl text-base leading-7 text-emerald-50">{{ $hero->branch?->name }} · {{ $hero->pillar?->name }}</p>
                    <p class="mt-4 text-sm tabular-nums text-emerald-50">@rupiah($hero->collected_amount) terkumpul dari @rupiah($hero->target_amount)</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a class="btn-gold" href="{{ route('donasi.create', ['campaign' => $hero->slug]) }}">Donasi Sekarang</a>
                        <a class="btn-line" href="{{ route('campaign.show', $hero->slug) }}">Lihat kampanye</a>
                    </div>
                @else
                    <h1 class="mt-3 max-w-xl text-4xl font-bold tracking-tight md:text-5xl">Merawat kepedulian dari Pontianak sampai pelosok.</h1>
                    <p class="mt-4 max-w-xl text-base leading-7 text-emerald-50">Portal penghimpunan zakat, infak, sedekah, dan wakaf untuk 14 kabupaten dan kota di Kalimantan Barat.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a class="btn-gold" href="{{ route('campaign.index') }}">Lihat kampanye</a>
                        <a class="btn-line" href="{{ route('ziswaf.zakat') }}">Hitung zakat</a>
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-3 rounded-3xl bg-white/10 p-4 text-sm">
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-emerald-100">Pengumpulan</p>
                    <p class="mt-1 text-lg font-bold tabular-nums">@rupiah($stats['collected'])</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-emerald-100">Donasi lunas</p>
                    <p class="mt-1 text-lg font-bold tabular-nums">{{ number_format($stats['donors'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-emerald-100">Kampanye tayang</p>
                    <p class="mt-1 text-lg font-bold tabular-nums">{{ number_format($stats['campaigns'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-emerald-100">Cabang</p>
                    <p class="mt-1 text-lg font-bold tabular-nums">{{ number_format($stats['branches'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto -mt-8 max-w-6xl px-4">
        <div class="grid grid-cols-3 gap-3 rounded-3xl border border-line bg-white p-4 shadow-sm md:grid-cols-6">
            @foreach ([
                ['zakat', 'Zakat', route('ziswaf.zakat')],
                ['infak', 'Infak/Sedekah', route('ziswaf.infak')],
                ['galang', 'Galang Dana', route('campaign.index')],
                ['fidyah', 'Fidyah', route('ziswaf.infak').'#fidyah'],
                ['kalkulator', 'Kalkulator', route('ziswaf.zakat').'#kalkulator'],
                ['qurban', 'Qurban', route('ziswaf.infak').'#qurban'],
            ] as [$icon, $label, $href])
                <a href="{{ $href }}" class="grid place-items-center rounded-2xl px-2 py-4 text-center text-sm font-semibold hover:bg-nu-soft">
                    <span class="mb-2 grid h-10 w-10 place-items-center rounded-full bg-nu-soft text-nu">
                        <x-product-icon :name="$icon" />
                    </span>
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-nu">Penggalangan dana</p>
                <h2 class="mt-1 text-2xl font-bold">Penggalangan mendesak</h2>
            </div>
            <a class="text-sm font-semibold text-nu" href="{{ route('campaign.index') }}">Lihat semua</a>
        </div>
        @if ($campaigns->isEmpty())
            <div class="mt-6 rounded-3xl border border-dashed border-line bg-white p-8">
                <p class="font-semibold">Belum ada kampanye yang tayang.</p>
                <p class="mt-2 max-w-xl text-sm leading-6 text-muted">Kampanye muncul di sini setelah Admin Cabang atau Super Admin menerbitkannya. Zakat, infak, dan wakaf tetap bisa ditunaikan tanpa kampanye.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a class="btn-nu" href="{{ route('ziswaf.zakat') }}">Zakat</a>
                    <a class="btn-line" href="{{ route('ziswaf.infak') }}">Infak</a>
                </div>
            </div>
        @else
            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($campaigns as $campaign)
                    <x-campaign-card :campaign="$campaign" />
                @endforeach
            </div>
        @endif
    </section>

    <section class="bg-white py-14">
        <div class="mx-auto max-w-6xl px-4">
            <p class="text-sm font-semibold text-nu">Pilar program</p>
            <h2 class="mt-1 text-2xl font-bold">5 Pilar Program NU Care-LAZISNU</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                @foreach ($pillars as $pillar)
                    <a href="{{ route('pilar.show', $pillar) }}" class="rounded-3xl border border-line p-4 hover:border-nu">
                        <span class="grid h-11 w-11 place-items-center rounded-full bg-nu-soft text-nu">
                            <x-pillar-icon :name="$pillar->icon" />
                        </span>
                        <h3 class="mt-3 font-bold">{{ $pillar->name }}</h3>
                        <p class="mt-2 line-clamp-4 text-sm leading-6 text-muted">{{ $pillar->description }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14">
        <p class="text-sm font-semibold text-nu">Tentang lembaga</p>
        <h2 class="mt-1 text-2xl font-bold">Sekilas NU Care - LAZISNU Kalbar</h2>
        <p class="mt-4 max-w-3xl text-base leading-7 text-slate-700">Lembaga Amil Zakat, Infak, dan Sedekah Nahdlatul Ulama (LAZISNU) merupakan lembaga nirlaba milik Nahdlatul Ulama yang berkhidmat dalam pemberdayaan masyarakat melalui pendayagunaan dana zakat, infak, sedekah, dan wakaf (ZISWAF).</p>
        <a class="btn-nu mt-6" href="{{ route('profil.tentang') }}">Baca profil</a>
    </section>

    <section class="bg-white py-14">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-nu">Berita</p>
                    <h2 class="mt-1 text-2xl font-bold">Kabar terbaru</h2>
                </div>
                <a class="text-sm font-semibold text-nu" href="{{ route('berita.index') }}">Semua berita</a>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($articles as $article)
                    <a href="{{ route('berita.show', $article->slug) }}" class="overflow-hidden rounded-3xl border border-line hover:border-nu">
                        @if ($article->coverUrl())
                            <img src="{{ $article->coverUrl() }}" alt="" width="1200" height="630" class="aspect-[1200/630] w-full object-cover">
                        @endif
                        <div class="p-5">
                            <p class="text-xs font-semibold text-nu">{{ $article->category }} · {{ $article->dateLabel() }}</p>
                            <h3 class="mt-2 text-lg font-bold">{{ $article->title }}</h3>
                            @if ($article->excerpt)
                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-muted">{{ $article->excerpt }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
