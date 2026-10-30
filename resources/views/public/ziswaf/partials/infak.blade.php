<section>
    <span id="fidyah"></span>
    <span id="qurban"></span>
    <h1 class="text-2xl font-bold">Salurkan <span class="text-nu">infak</span> Anda</h1>
    <p class="mt-2 text-sm leading-6 text-muted">Setiap rupiah Anda, Insya Allah berkah dan menjadi amal jariyah.</p>
    <div data-ziswaf-pay data-base="{{ route('donasi.create') }}">
        <label class="mt-5 grid gap-2 text-sm font-semibold">Infak Untuk
            <select data-pay-channel class="rounded-xl border border-nu px-3 py-3 font-normal">
                <option value="infak">Infak</option>
                <option value="sedekah">Sedekah</option>
                <option value="fidyah">Fidyah</option>
                <option value="qurban">Qurban</option>
            </select>
        </label>
        <label class="mt-4 grid gap-2 text-sm font-semibold">Nominal Infak
            <input data-pay-amount type="text" inputmode="numeric" placeholder="0" class="rounded-xl border border-line px-3 py-3 font-normal tabular-nums">
        </label>
        <p data-pay-note hidden class="mt-2 text-sm text-muted"></p>
        <a data-pay-link class="btn-nu pointer-events-none mt-5 w-full opacity-50" aria-disabled="true">Tunaikan Infak</a>
    </div>
</section>
