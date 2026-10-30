@extends('layouts.public')

@section('title', $article->title)
@section('description', $article->excerpt ?: $article->title)
@section('og_type', 'article')
@if ($article->coverUrl())
    @section('og_image', $article->coverUrl())
    @section('og_image_width', '1200')
    @section('og_image_height', '630')
@endif

@push('head')
    <script type="application/ld+json">{!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'description' => $article->excerpt ?: $article->title,
        'datePublished' => $article->published_at?->toAtomString(),
        'image' => $article->coverUrl() ? [$article->coverUrl()] : null,
        'mainEntityOfPage' => route('berita.show', $article->slug),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'NU Care-LAZISNU Kalimantan Barat',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/logo-lazisnu.png'),
            ],
        ],
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
    <x-page-header :kicker="$article->category.' · '.$article->dateLabel()" :title="$article->title" :lead="$article->excerpt" />
    @if ($article->coverUrl())
        <div class="mx-auto max-w-6xl px-4">
            <img src="{{ $article->coverUrl() }}" alt="{{ $article->title }}" width="1200" height="630" class="aspect-[1200/630] w-full rounded-3xl object-cover">
        </div>
    @endif
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-12 lg:grid-cols-[minmax(0,1fr)_280px]">
        <article class="article-body space-y-4 text-base leading-7 text-slate-700 [&_img]:my-4 [&_img]:w-full [&_img]:rounded-2xl [&_img]:object-cover [&_strong]:font-semibold">
            {!! \App\Support\PublicHtml::clean($article->body) !!}
        </article>
        <aside class="grid content-start gap-3">
            <p class="text-sm font-semibold">Artikel lain</p>
            @foreach ($articles as $item)
                <a class="flex items-center gap-3 rounded-2xl border border-line bg-white p-3 text-sm font-semibold leading-5 hover:border-nu" href="{{ route('berita.show', $item->slug) }}">
                    @if ($item->coverUrl())
                        <img src="{{ $item->coverUrl() }}" alt="" width="96" height="96" class="size-16 shrink-0 rounded-lg object-cover">
                    @endif
                    <span>{{ $item->title }}</span>
                </a>
            @endforeach
        </aside>
    </div>
@endsection
