@extends('layouts.public')

@section('title', 'Edukasi')

@section('content')
    <x-page-header kicker="Edukasi" title="Literasi dan penguatan sumber daya manusia" lead="Literasi ZIS, dana sosial keagamaan, dan dana abadi untuk kegiatan investasi sosial." />
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 md:grid-cols-2">
        <a href="{{ route('profil.visi') }}" class="rounded-3xl border border-line bg-white p-6 hover:border-nu">
            <h2 class="text-xl font-bold">Literasi dan penggalangan</h2>
            <p class="mt-3 leading-7 text-muted">Menggalakkan literasi serta mengoptimalkan penggalangan dana Zakat, Infaq, Shadaqah (ZIS), Dana Sosial Keagamaan Lainnya (DSKL), dan Dana Abadi (Trust Fund) berbasis digital.</p>
        </a>
        <a href="{{ route('pilar.show', 'nu-care-cerdas') }}" class="rounded-3xl border border-line bg-white p-6 hover:border-nu">
            <h2 class="text-xl font-bold">Sumber daya manusia</h2>
            <p class="mt-3 leading-7 text-muted">Program peningkatan kualitas sumber daya manusia agar lahir intelektual, teknokrat, dan wirausahawan yang unggul, selaras dengan bidang yang dibutuhkan.</p>
        </a>
    </div>
@endsection
