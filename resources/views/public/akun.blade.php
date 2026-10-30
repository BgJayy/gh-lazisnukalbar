@extends('layouts.public')

@section('title', 'Akun')

@section('content')
    <x-page-header kicker="Akun" title="Cek donasi" lead="Masuk tidak wajib untuk berdonasi. Pengurus masuk lewat panel admin. Donatur cukup menyimpan nomor invoice." />
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 lg:grid-cols-2">
        <section class="rounded-3xl border border-line bg-white p-6">
            <h2 class="text-xl font-bold">Status invoice</h2>
            <div class="mt-4">
                @include('public.partials.alerts')
                @include('public.partials.invoice-lookup')
            </div>
        </section>
        <section class="rounded-3xl border border-line bg-white p-6">
            <h2 class="text-xl font-bold">Pengurus</h2>
            <p class="mt-2 text-sm leading-6 text-muted">Bendahara menyetujui donasi dari panel admin. Persetujuan itulah yang mengubah status menjadi Lunas.</p>
            <a class="btn-nu mt-5" href="{{ url('/admin/login') }}">Masuk panel admin</a>
        </section>
    </div>
@endsection
