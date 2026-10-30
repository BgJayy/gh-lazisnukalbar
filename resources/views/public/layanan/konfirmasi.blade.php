@extends('layouts.public')

@section('title', 'Konfirmasi Donasi')

@section('content')
    <x-page-header kicker="Layanan" title="Konfirmasi donasi" lead="Cek invoice yang sudah dibuat, atau mulai donasi baru. Status lunas hanya berubah setelah bendahara menyetujui." />
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 lg:grid-cols-2">
        <section class="rounded-3xl border border-line bg-white p-6">
            <h2 class="text-xl font-bold">Sudah punya invoice</h2>
            <p class="mt-2 text-sm leading-6 text-muted">Masukkan kode yang tampil setelah Anda menekan lanjut bayar. Di halaman itu Anda bisa mengunggah bukti transfer.</p>
            <div class="mt-4">
                @include('public.partials.alerts')
                @include('public.partials.invoice-lookup')
            </div>
        </section>
        <section class="rounded-3xl border border-line bg-white p-6">
            <h2 class="text-xl font-bold">Belum membuat donasi</h2>
            <ol class="mt-4 list-decimal space-y-2 pl-5 text-sm leading-6 text-slate-700">
                <li>Pilih zakat, infak, wakaf, atau kampanye.</li>
                <li>Isi nominal dan lanjut bayar.</li>
                <li>Scan QRIS atau transfer ke rekening sesuai buku kas.</li>
                <li>Unggah bukti. Status tetap Menunggu.</li>
            </ol>
            <a class="btn-gold mt-6" href="{{ route('donasi.create') }}">Buat donasi</a>
        </section>
    </div>
@endsection
