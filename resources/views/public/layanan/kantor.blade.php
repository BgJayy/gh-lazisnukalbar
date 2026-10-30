@extends('layouts.public')

@section('title', 'Kantor Layanan')

@section('content')
    <x-page-header kicker="Layanan" title="Kantor layanan" lead="Pengurus wilayah di Pontianak dan pengurus cabang di kabupaten serta kota se-Kalimantan Barat." />
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($branches as $branch)
            <x-branch-card :branch="$branch" />
        @empty
            <p class="text-muted">Data kantor belum tersedia.</p>
        @endforelse
    </div>
@endsection
