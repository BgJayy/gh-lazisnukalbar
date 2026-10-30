@php
    $siteName = 'NU Care-LAZISNU Kalimantan Barat';
    $tagline = 'Merawat kepedulian dari Pontianak sampai pelosok';
    $pageTitle = trim($__env->yieldContent('title', $siteName));
    $documentTitle = ($pageTitle === '' || $pageTitle === $siteName) ? $siteName : $pageTitle.' — '.$siteName;
    $description = trim($__env->yieldContent('description'));

    if ($description === '') {
        $description = match (request()->route()?->getName()) {
            'profil.visi' => 'Visi dan misi NU Care-LAZISNU Kalimantan Barat.',
            'profil.pengurus' => 'Susunan pengurus NU Care-LAZISNU Kalimantan Barat.',
            'profil.eksekutif' => 'Manajemen eksekutif NU Care-LAZISNU Kalimantan Barat.',
            'pilar.index' => 'Lima pilar program NU Care-LAZISNU Kalimantan Barat: Cerdas, Berdaya, Sehat, Damai, dan Hijau.',
            'campaign.index' => 'Campaign zakat, infak, dan wakaf NU Care-LAZISNU Kalimantan Barat.',
            'edukasi' => 'Edukasi zakat, infak, sedekah, dan wakaf dari NU Care-LAZISNU Kalimantan Barat.',
            'berita.index' => 'Berita kegiatan NU Care-LAZISNU Kalimantan Barat.',
            'layanan.konfirmasi' => 'Cek status donasi NU Care-LAZISNU Kalimantan Barat dengan nomor invoice.',
            'layanan.kantor' => 'Kantor layanan NU Care-LAZISNU di Kalimantan Barat.',
            'akun' => 'Cek donasi dengan nomor invoice. Masuk tidak wajib untuk berdonasi.',
            'donasi.create' => 'Salurkan zakat, infak, atau wakaf ke NU Care-LAZISNU Kalimantan Barat.',
            default => $tagline,
        };
    }

    $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $ogImage = trim($__env->yieldContent('og_image'));
    $ogWidth = null;
    $ogHeight = null;

    if ($ogImage === '') {
        $ogImage = asset('images/logo-lazisnu.png');
    } elseif (! str_starts_with($ogImage, 'http://') && ! str_starts_with($ogImage, 'https://')) {
        $ogImage = url($ogImage);
        $ogWidth = trim($__env->yieldContent('og_image_width')) ?: null;
        $ogHeight = trim($__env->yieldContent('og_image_height')) ?: null;
    } else {
        $ogWidth = trim($__env->yieldContent('og_image_width')) ?: null;
        $ogHeight = trim($__env->yieldContent('og_image_height')) ?: null;
    }

    $ogType = trim($__env->yieldContent('og_type')) ?: 'website';
    $robots = trim($__env->yieldContent('robots'));
    $jsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'NGO',
                'name' => $siteName,
                'url' => url('/'),
                'logo' => asset('images/logo-lazisnu.png'),
                'description' => $tagline,
                'areaServed' => 'Kalimantan Barat',
            ],
            [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => url('/'),
                'description' => $tagline,
            ],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
@endphp
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#008C45">
        <title>{{ $documentTitle }}</title>
        <meta name="description" content="{{ $description }}">
        @if ($robots !== '')
            <meta name="robots" content="{{ $robots }}">
        @endif
        <link rel="canonical" href="{{ $canonical }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" href="{{ asset('favicon-32.png') }}" type="image/png" sizes="32x32">
        <link rel="icon" href="{{ asset('favicon-192.png') }}" type="image/png" sizes="192x192">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:locale" content="id_ID">
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:title" content="{{ $documentTitle }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:image" content="{{ $ogImage }}">
        @if ($ogWidth && $ogHeight)
            <meta property="og:image:width" content="{{ $ogWidth }}">
            <meta property="og:image:height" content="{{ $ogHeight }}">
        @endif
        <meta name="twitter:card" content="{{ $ogWidth ? 'summary_large_image' : 'summary' }}">
        <meta name="twitter:title" content="{{ $documentTitle }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $ogImage }}">
        <script type="application/ld+json">{!! $jsonLd !!}</script>
        @stack('head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-canvas font-sans text-ink antialiased {{ request()->routeIs('campaign.show') ? 'pb-40' : 'pb-24' }} lg:pb-0">
        @include('public.partials.header')
        <main id="isi">
            @yield('content')
        </main>
        @include('public.partials.footer')
        @include('public.partials.bottom-nav')
        @unless (request()->routeIs('campaign.show', 'donasi.*', 'ziswaf.infak'))
            <a href="{{ route('ziswaf.infak') }}" class="btn-gold fixed bottom-20 right-4 z-40 shadow-lg lg:hidden">QuickInfak</a>
        @endunless
    </body>
</html>
