<div class="rounded-3xl border border-line bg-white p-5 text-center">
    @if (is_file(public_path('images/qris-lazisnu.jpg')))
        <img src="{{ asset('images/qris-lazisnu.jpg') }}" alt="QRIS NU Care Lazisnu Kalbar" class="mx-auto w-full max-w-sm rounded-xl border border-line">
        <p class="mt-4 font-semibold">QRIS NU Care Lazisnu Kalbar</p>
        <p class="mt-1 text-sm text-muted">Scan dengan aplikasi bank atau e-wallet Anda.</p>
    @elseif (is_file(public_path('images/qris-lazisnu.png')))
        <img src="{{ asset('images/qris-lazisnu.png') }}" alt="QRIS NU Care Lazisnu Kalbar" class="mx-auto w-full max-w-sm rounded-xl border border-line object-contain">
        <p class="mt-4 font-semibold">QRIS NU Care Lazisnu Kalbar</p>
        <p class="mt-1 text-sm text-muted">Scan dengan aplikasi bank atau e-wallet Anda.</p>
    @else
        <div class="mx-auto grid h-52 w-52 place-items-center rounded-xl border-2 border-dashed border-nu/40 bg-nu-soft">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-nu">QRIS</p>
                <p class="mt-2 text-sm font-semibold">LAZISNU Kalbar</p>
            </div>
        </div>
        <p class="mt-4 font-semibold">QRIS LAZISNU Kalbar</p>
        <p class="mt-1 text-sm leading-6 text-muted">Gambar QRIS resmi belum dipasang. Transfer ke rekening di bawah sampai berkas QRIS lembaga tersedia.</p>
    @endif
</div>
