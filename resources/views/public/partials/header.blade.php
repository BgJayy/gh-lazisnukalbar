<header class="sticky top-0 z-50 border-b border-line bg-white">
    <div class="hidden bg-nu text-white lg:block">
        <p class="mx-auto max-w-6xl px-4 py-2 text-xs">Dana zakat, infak/sedekah, dan wakaf dicatat pada buku kas yang terpisah.</p>
    </div>
    <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 py-3">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2">
            @if (is_file(public_path('images/logo-lazisnu.png')))
                <img src="{{ asset('images/logo-lazisnu.png') }}" alt="NU Care-LAZISNU" class="h-11 w-auto max-w-40 shrink-0 object-contain">
            @else
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-nu text-sm font-bold text-white">NU</span>
            @endif
            <span class="min-w-0">
                <span class="block truncate text-sm font-bold leading-tight">NU Care-LAZISNU</span>
                <span class="block truncate text-xs text-muted">Kalimantan Barat</span>
            </span>
        </a>

        <nav class="ml-6 hidden items-center gap-1 lg:flex" aria-label="Utama">
            <div class="group relative">
                <a href="{{ route('profil.tentang') }}" class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('profil.*') ? 'text-nu' : 'text-ink' }}">Profil</a>
                <div class="invisible absolute left-0 top-full z-50 w-64 pt-2 opacity-0 group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100">
                    <div class="rounded-2xl border border-line bg-white p-2 shadow-lg">
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('profil.tentang') }}">Tentang LAZISNU Kalbar</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('profil.visi') }}">Visi dan Misi</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('profil.pengurus') }}">Susunan Pengurus</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('profil.eksekutif') }}">Manajemen Eksekutif</a>
                    </div>
                </div>
            </div>
            <div class="group relative">
                <a href="{{ route('pilar.index') }}" class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('pilar.*') ? 'text-nu' : 'text-ink' }}">Pilar Program</a>
                <div class="invisible absolute left-0 top-full z-50 w-72 pt-2 opacity-0 group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100">
                    <div class="rounded-2xl border border-line bg-white p-2 shadow-lg">
                        @foreach ($navPillars ?? [] as $pillar)
                            <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('pilar.show', $pillar) }}">{{ $pillar->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="group relative">
                <a href="{{ route('ziswaf.index') }}" class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('ziswaf.*') ? 'text-nu' : 'text-ink' }}">Ziswaf</a>
                <div class="invisible absolute left-0 top-full z-50 w-56 pt-2 opacity-0 group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100">
                    <div class="rounded-2xl border border-line bg-white p-2 shadow-lg">
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('ziswaf.zakat') }}">Zakat</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('ziswaf.infak') }}">Infak</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('ziswaf.wakaf') }}">Wakaf</a>
                    </div>
                </div>
            </div>
            <a href="{{ route('campaign.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('campaign.*') ? 'text-nu' : 'text-ink' }}">Campaign</a>
            <a href="{{ route('berita.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('berita.*') ? 'text-nu' : 'text-ink' }}">Berita</a>
            <a href="{{ route('edukasi') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('edukasi') ? 'text-nu' : 'text-ink' }}">Edukasi</a>
            <div class="group relative">
                <a href="{{ route('layanan.konfirmasi') }}" class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('layanan.*') ? 'text-nu' : 'text-ink' }}">Layanan</a>
                <div class="invisible absolute left-0 top-full z-50 w-64 pt-2 opacity-0 group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100">
                    <div class="rounded-2xl border border-line bg-white p-2 shadow-lg">
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('layanan.konfirmasi') }}">Konfirmasi Donasi</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('layanan.kantor') }}">Kantor Layanan</a>
                        <a class="block rounded-xl px-3 py-2 text-sm hover:bg-canvas" href="{{ route('layanan.rekening') }}">Rekening Lengkap</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="ml-auto hidden items-center gap-2 lg:flex">
            <a href="{{ url('/admin/login') }}" class="btn-line px-4 py-2">Masuk</a>
            <a href="{{ route('donasi.create') }}" class="btn-gold px-4 py-2">Donasi</a>
        </div>

        <details id="menu-mobile" class="relative ml-auto lg:hidden">
            <summary class="cursor-pointer list-none rounded-xl border border-line px-3 py-2 text-sm font-semibold [&::-webkit-details-marker]:hidden">Menu</summary>
            <div class="fixed inset-x-0 top-0 bottom-14 z-40">
                <button type="button" class="absolute inset-0 bg-slate-950/55" aria-label="Tutup menu" onclick="this.closest('details').open = false"></button>
                <div class="absolute inset-y-0 right-0 flex w-[min(82%,22rem)] flex-col bg-navy text-white">
                    <div class="flex items-center justify-between border-b border-white/10 px-5 py-4">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            @if (is_file(public_path('images/logo-lazisnu-white.png')))
                                <img src="{{ asset('images/logo-lazisnu-white.png') }}" alt="NU Care-LAZISNU" class="h-12 w-auto">
                            @endif
                        </a>
                        <button type="button" class="grid h-10 w-10 place-items-center text-2xl leading-none" aria-label="Tutup" onclick="this.closest('details').open = false">&times;</button>
                    </div>
                    <nav class="flex-1 overflow-auto px-5 py-2" aria-label="Menu ponsel">
                        <details open class="group border-b border-white/10 py-3">
                            <summary class="flex cursor-pointer list-none items-center justify-between text-base font-semibold [&::-webkit-details-marker]:hidden">
                                Profil
                                <span class="text-white/70 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-4 text-sm text-white/85">
                                <a href="{{ route('profil.tentang') }}">Tentang NU Care-LAZISNU</a>
                                <a href="{{ route('profil.visi') }}">Visi dan Misi</a>
                                <a href="{{ route('profil.pengurus') }}">Susunan Pengurus</a>
                                <a href="{{ route('profil.eksekutif') }}">Manajemen Eksekutif</a>
                            </div>
                        </details>
                        <details class="group border-b border-white/10 py-3">
                            <summary class="flex cursor-pointer list-none items-center justify-between text-base font-semibold [&::-webkit-details-marker]:hidden">
                                Pilar Program
                                <span class="text-white/70 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <div class="mt-3 grid gap-3 text-sm text-white/85">
                                @foreach ($navPillars ?? [] as $pillar)
                                    <a href="{{ route('pilar.show', $pillar) }}">{{ $pillar->name }}</a>
                                @endforeach
                            </div>
                        </details>
                        <details class="group border-b border-white/10 py-3">
                            <summary class="flex cursor-pointer list-none items-center justify-between text-base font-semibold [&::-webkit-details-marker]:hidden">
                                Ziswaf
                                <span class="text-white/70 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <div class="mt-3 grid gap-3 text-sm text-white/85">
                                <a href="{{ route('ziswaf.zakat') }}">Zakat</a>
                                <a href="{{ route('ziswaf.infak') }}">Infak</a>
                                <a href="{{ route('ziswaf.wakaf') }}">Wakaf</a>
                            </div>
                        </details>
                        <a class="block border-b border-white/10 py-3 text-base font-semibold" href="{{ route('campaign.index') }}">Campaign</a>
                        <a class="block border-b border-white/10 py-3 text-base font-semibold" href="{{ route('berita.index') }}">Berita</a>
                        <details class="group border-b border-white/10 py-3">
                            <summary class="flex cursor-pointer list-none items-center justify-between text-base font-semibold [&::-webkit-details-marker]:hidden">
                                Layanan
                                <span class="text-white/70 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <div class="mt-3 grid gap-3 text-sm text-white/85">
                                <a href="{{ route('layanan.konfirmasi') }}">Konfirmasi Donasi</a>
                                <a href="{{ route('layanan.kantor') }}">Kantor Layanan</a>
                                <a href="{{ route('layanan.rekening') }}">Rekening Lengkap</a>
                            </div>
                        </details>
                        <a class="block py-3 text-base font-semibold" href="{{ route('edukasi') }}">Edukasi</a>
                    </nav>
                </div>
            </div>
        </details>
    </div>
</header>
