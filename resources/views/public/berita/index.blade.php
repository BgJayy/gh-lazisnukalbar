@extends('layouts.public')

@section('title', 'Berita')

@section('content')
    <x-page-header kicker="Berita" title="Kabar dari LAZISNU Kalbar" lead="Catatan penyaluran dan kegiatan NU Care-LAZISNU Kalimantan Barat." />
    <div class="mx-auto grid max-w-6xl gap-4 px-4 py-12 md:grid-cols-3">
        @forelse ($articles as $article)
            <a href="{{ route('berita.show', $article->slug) }}" class="overflow-hidden rounded-3xl border border-line bg-white hover:border-nu">
                @if ($article->coverUrl())
                    <img src="{{ $article->coverUrl() }}" alt="" width="1200" height="630" class="aspect-[1200/630] w-full object-cover">
                @endif
                <div class="p-5">
                    <p class="text-xs font-semibold text-nu">{{ $article->category }} · {{ $article->dateLabel() }}</p>
                    <h2 class="mt-2 text-xl font-bold">{{ $article->title }}</h2>
                    @if ($article->excerpt)
                        <p class="mt-3 text-sm leading-6 text-muted">{{ $article->excerpt }}</p>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-muted md:col-span-3">Belum ada berita.</p>
        @endforelse
    </div>
@endsection
