<footer class="bg-nu text-white">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 md:grid-cols-4">
        <div class="md:col-span-1">
            <p class="text-sm font-bold">NU Care-LAZISNU Kalimantan Barat</p>
            <p class="mt-3 text-sm leading-6 text-white/80">Menghimpun dan mengelola zakat, infak, sedekah, serta wakaf untuk masyarakat di 14 kabupaten dan kota.</p>
        </div>
        <div>
            <p class="text-sm font-semibold">Profil</p>
            <div class="mt-3 grid gap-2 text-sm text-white/80">
                <a class="hover:text-white" href="{{ route('profil.tentang') }}">Tentang</a>
                <a class="hover:text-white" href="{{ route('profil.visi') }}">Visi dan Misi</a>
                <a class="hover:text-white" href="{{ route('profil.pengurus') }}">Susunan Pengurus</a>
                <a class="hover:text-white" href="{{ route('pilar.index') }}">Pilar Program</a>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold">Ziswaf</p>
            <div class="mt-3 grid gap-2 text-sm text-white/80">
                <a class="hover:text-white" href="{{ route('ziswaf.zakat') }}">Zakat</a>
                <a class="hover:text-white" href="{{ route('ziswaf.infak') }}">Infak</a>
                <a class="hover:text-white" href="{{ route('ziswaf.wakaf') }}">Wakaf</a>
                <a class="hover:text-white" href="{{ route('campaign.index') }}">Campaign</a>
                <a class="hover:text-white" href="{{ route('berita.index') }}">Berita</a>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold">Layanan</p>
            <div class="mt-3 grid gap-2 text-sm text-white/80">
                <a class="hover:text-white" href="{{ route('layanan.konfirmasi') }}">Konfirmasi Donasi</a>
                <a class="hover:text-white" href="{{ route('layanan.kantor') }}">Kantor Layanan</a>
                <a class="hover:text-white" href="{{ route('layanan.rekening') }}">Rekening Lengkap</a>
                <a class="hover:text-white" href="{{ route('akun') }}">Cek status donasi</a>
            </div>
            <p class="mt-4 text-sm leading-6 text-white/80">Pontianak, Kalimantan Barat</p>
        </div>
    </div>
    <div class="border-t border-white/20">
        <p class="mx-auto max-w-6xl px-4 py-4 text-xs leading-5 text-white/80">Pengelolaan zakat tunduk pada UU Pengelolaan Zakat. © {{ now()->year }} NU Care-LAZISNU Kalimantan Barat.</p>
    </div>
</footer>
