@extends('layouts.public')

@section('title', 'Visi dan Misi')

@section('content')
    <section class="bg-white">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-14 lg:grid-cols-[minmax(0,1.15fr)_18rem]">
            <div>
                <p class="text-sm font-semibold text-nu">Sekilas</p>
                <h1 class="mt-2 max-w-xl text-4xl font-bold tracking-tight">NU Care - LAZISNU Kalbar</h1>
                <p class="mt-4 max-w-xl text-base leading-7 text-slate-700">Lembaga Amil Zakat, Infak, dan Sedekah Nahdlatul Ulama (LAZISNU) merupakan lembaga nirlaba milik Nahdlatul Ulama yang berkhidmat dalam pemberdayaan masyarakat melalui pendayagunaan dana zakat, infak, sedekah, dan wakaf (ZISWAF).</p>
            </div>
            <img
                src="{{ asset('images/visi-khidmat.png') }}"
                alt="Penyaluran santunan anak yatim dan dhuafa oleh NU Care-LAZISNU PWNU Kalbar"
                width="1200"
                height="1493"
                class="mx-auto aspect-[4/5] w-full max-w-xs rounded-3xl object-cover"
            >
        </div>
    </section>

    <section class="mx-auto max-w-6xl space-y-8 px-4 py-14">
        <article class="rounded-3xl bg-nu px-6 py-8 text-white md:px-10">
            <p class="text-sm font-semibold text-emerald-100">Visi</p>
            <p class="mt-3 max-w-3xl text-2xl font-bold leading-snug md:text-3xl">Menjadi Lembaga Filantropi Islam Terkemuka</p>
        </article>

        <div>
            <h2 class="text-2xl font-bold">Misi</h2>
            <ol class="mt-4 grid gap-3">
                @foreach ([
                    'Menggalakkan literasi serta mengoptimalkan penggalangan dana Zakat, Infaq, Shadaqah (ZIS), Dana Sosial Keagamaan Lainnya (DSKL), dan Dana Abadi (Trust Fund) berbasis digital untuk kepentingan kegiatan yang berbasis Investasi Sosial.',
                    'Menjadi Pilihan Utama Mitra Strategis dalam kolaborasi dan sinergi menjalankan berbagai kegiatan/usaha sosial.',
                    'Menyediakan program-program untuk peningkatan kualitas Sumber Daya Manusia sehingga mampu melahirkan Intelektual, Teknokrat, dan Wirausahawan yang Unggul dan Handal, serta memberikan akses lapangan kerja dan kesempatan berkarir di Sektor Strategis, yang selaras dengan bidang yang dibutuhkan pemerintah.',
                    'Menggerakan sektor riil dan para pelaku UMKM (creativepreneur) dengan pola Supply Chain yang berdampak pada pertumbuhan ekonomi desa, melalui pemanfaatan dana sosial berbasis Ziswaf (Zakat, Infaq, Shadaqah, Wakaf) produktif.',
                    'Membentuk Badan Usaha Milik Komunitas yang berbentuk Investment Holding Company (NU CARE Venture) dari umat, oleh umat, dan untuk umat yang sesuai dengan standar World Class Company.',
                ] as $misi)
                    <li class="flex gap-4 rounded-3xl border border-line bg-white p-5">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-nu-soft text-sm font-bold text-nu">{{ $loop->iteration }}</span>
                        <p class="text-sm leading-7 text-ink">{{ $misi }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endsection
