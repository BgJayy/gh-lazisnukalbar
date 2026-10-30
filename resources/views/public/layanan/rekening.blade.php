@extends('layouts.public')

@section('title', 'Daftar Rekening')
@section('description', 'Rekening resmi NU Care-LAZISNU Kalimantan Barat untuk zakat, infak, sedekah, wakaf, dan campaign.')

@section('content')
    <section class="bg-nu text-white">
        <div class="mx-auto max-w-5xl px-4 py-14 text-center">
            <p class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">Informasi Rekening</p>
            <h1 class="mt-4 text-4xl font-bold">Daftar Rekening</h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-white/90 md:text-base">Daftar rekening resmi NU Care-LAZISNU Kalimantan Barat untuk penyaluran zakat, infak, sedekah, dan wakaf.</p>
        </div>
    </section>

    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8" data-account-directory>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <label class="sr-only" for="cari-rekening">Cari bank atau nomor rekening</label>
            <input id="cari-rekening" data-account-search type="search" placeholder="Cari bank atau nomor rekening..." class="w-full rounded-xl border border-line bg-white px-4 py-3 text-sm sm:max-w-md">
            <div class="flex flex-wrap gap-2">
                @foreach (['semua' => 'Semua', 'zakat' => 'Zakat', 'infak' => 'Infak', 'wakaf' => 'Wakaf'] as $key => $label)
                    <button type="button" data-account-filter="{{ $key }}" class="rounded-lg border px-3 py-2 text-xs font-semibold {{ $key === 'semua' ? 'border-nu bg-nu text-white' : 'border-line bg-white' }}" aria-pressed="{{ $key === 'semua' ? 'true' : 'false' }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-line bg-white">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-nu text-xs font-semibold uppercase tracking-wide text-white">
                    <tr>
                        <th class="px-4 py-3">Nama bank</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3">Nama rekening</th>
                        <th class="px-4 py-3">Nomor rekening</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Support\OfficialAccounts::all() as $account)
                        <tr data-account-row data-search="{{ strtolower($account['bank'].' '.$account['number'].' '.$account['holder']) }}" class="border-t border-line">
                            <td class="px-4 py-4 font-semibold">{{ $account['bank'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <span class="rounded-md bg-nu-soft px-2 py-1 text-[11px] font-semibold text-nu">Zakat</span>
                                    <span class="rounded-md bg-nu-soft px-2 py-1 text-[11px] font-semibold text-nu">Infak</span>
                                    <span class="rounded-md bg-nu-soft px-2 py-1 text-[11px] font-semibold text-nu">Wakaf</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">{{ $account['holder'] }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono tabular-nums">{{ $account['number'] }}</span>
                                    <button type="button" data-copy="{{ $account['number'] }}" class="text-xs font-semibold text-nu">Salin</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p data-account-empty hidden class="text-sm text-muted">Rekening yang dicari tidak ada.</p>
        <p class="text-sm leading-6 text-muted">Kedua rekening ini digunakan untuk zakat, infak, wakaf, dan seluruh campaign.</p>

        <x-qris-panel />

        <section class="rounded-2xl border border-line bg-white p-5">
            <h2 class="font-bold">Informasi penting</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-6 text-muted">
                <li>Pastikan transfer ke rekening resmi yang tertera di atas.</li>
                <li>Setelah transfer, unggah bukti pada halaman selesaikan donasi, atau cek status lewat <a class="font-semibold text-nu" href="{{ route('layanan.konfirmasi') }}">Konfirmasi Donasi</a>.</li>
            </ul>
        </section>
    </div>

    <script>
        const directory = document.querySelector('[data-account-directory]');
        if (directory) {
            const search = directory.querySelector('[data-account-search]');
            const rows = [...directory.querySelectorAll('[data-account-row]')];
            const empty = directory.querySelector('[data-account-empty]');
            const buttons = [...directory.querySelectorAll('[data-account-filter]')];

            const apply = () => {
                const query = (search.value || '').trim().toLowerCase();
                let visible = 0;
                rows.forEach((row) => {
                    const matches = row.dataset.search.includes(query);
                    row.hidden = ! matches;
                    if (matches) visible++;
                });
                empty.hidden = visible > 0;
            };

            search.addEventListener('input', apply);
            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    buttons.forEach((item) => {
                        const active = item === button;
                        item.setAttribute('aria-pressed', active ? 'true' : 'false');
                        item.classList.toggle('border-nu', active);
                        item.classList.toggle('bg-nu', active);
                        item.classList.toggle('text-white', active);
                        item.classList.toggle('border-line', ! active);
                        item.classList.toggle('bg-white', ! active);
                    });
                    apply();
                });
            });
        }
    </script>
@endsection
