<section>
    <h1 class="text-2xl font-bold">Wariskan amal jariyah</h1>
    <p class="mt-2 text-sm leading-6 text-muted">Wakaf tunai untuk kemaslahatan umat. Pahalanya terus mengalir, dan dicatat pada buku kas wakaf.</p>
    <div data-ziswaf-pay data-base="{{ route('donasi.create') }}">
        <label class="mt-5 grid gap-2 text-sm font-semibold">Wakaf Untuk
            <select data-pay-channel class="rounded-xl border border-nu px-3 py-3 font-normal">
                <option value="wakaf">Wakaf NU Care-LAZISNU Kalimantan Barat</option>
            </select>
        </label>
        <label class="mt-4 grid gap-2 text-sm font-semibold">Nominal Wakaf
            <input data-pay-amount type="text" inputmode="numeric" placeholder="0" class="rounded-xl border border-line px-3 py-3 font-normal tabular-nums">
        </label>
        <p data-pay-note hidden class="mt-2 text-sm text-muted"></p>
        <a data-pay-link class="btn-nu pointer-events-none mt-5 w-full opacity-50" aria-disabled="true">Tunaikan Wakaf</a>
    </div>
</section>
