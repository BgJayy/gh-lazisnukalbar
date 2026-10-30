@extends('layouts.public')

@section('title', 'Susunan Pengurus')

@section('content')
    <x-page-header kicker="Profil" title="Susunan Pengurus" lead="Struktur jabatan Pengurus Wilayah dan jaringan cabang. Nama pejabat mengikuti SK yang berlaku dan belum ditampilkan di halaman ini." />
    <div class="mx-auto max-w-6xl space-y-12 px-4 py-12">
        <section>
            <h2 class="text-2xl font-bold">Pengurus harian dan bidang</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['Ketua', 'Pengurus Harian'],
                    ['Wakil Ketua', 'Pengurus Harian'],
                    ['Sekretaris', 'Pengurus Harian'],
                    ['Wakil Sekretaris', 'Pengurus Harian'],
                    ['Bendahara', 'Pengurus Harian'],
                    ['Wakil Bendahara', 'Pengurus Harian'],
                    ['Bidang Penghimpunan', 'Bidang'],
                    ['Bidang Pendistribusian', 'Bidang'],
                    ['Bidang Keuangan dan Kepatuhan', 'Bidang'],
                    ['Bidang Komunikasi', 'Bidang'],
                ] as [$jabatan, $rumpun])
                    <article class="rounded-3xl border border-line bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-nu">{{ $rumpun }}</p>
                        <h3 class="mt-1 text-lg font-bold">{{ $jabatan }}</h3>
                    </article>
                @endforeach
            </div>
        </section>
        <section>
            <h2 class="text-2xl font-bold">Jaringan cabang</h2>
            <p class="mt-2 text-sm text-muted">{{ $branches->filter(fn ($branch) => $branch->type === \App\Enums\BranchType::Pc)->count() }} pengurus cabang berada di bawah pengurus wilayah.</p>
            <div class="mt-4 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($branches as $branch)
                    <x-branch-card :branch="$branch" />
                @endforeach
            </div>
        </section>
    </div>
@endsection
