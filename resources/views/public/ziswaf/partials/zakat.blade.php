<section id="kalkulator" data-zakat-calculator>
    <h1 class="text-2xl font-bold">Berapa <span class="text-nu">zakat</span> Anda?</h1>
    <p data-zakat-lead class="mt-2 text-sm leading-6 text-muted">Pilih jenis zakat lalu masukkan nominal harta. Kalkulator menghitung zakat profesi, maal, uang, dan emas. Jenis lain memakai nominal yang sudah Anda ketahui.</p>

    <label class="mt-5 grid gap-2 text-sm font-semibold">Jenis Zakat
        <select data-zakat-type class="rounded-xl border border-nu px-3 py-3 font-normal">
            <option value="profesi">Zakat Profesi</option>
            <option value="maal">Zakat Maal</option>
            <option value="fitrah">Zakat Fitrah</option>
            <option value="emas">Zakat Emas, Perak, dan Logam mulia</option>
            <option value="perniagaan">Zakat Perniagaan</option>
            <option value="pertambangan">Zakat Pertambangan</option>
            <option value="pertanian">Zakat Pertanian, Perkebunan, dan Kehutanan</option>
            <option value="peternakan">Zakat Peternakan dan Perikanan</option>
            <option value="uang">Zakat uang dan surat berharga lainnya</option>
        </select>
    </label>

    @if ($goldPrice)
        <input data-gold type="hidden" value="{{ $goldPrice }}">
    @else
        <label class="mt-4 grid gap-2 text-sm font-semibold">Harga emas per gram
            <input data-gold type="text" inputmode="numeric" class="rounded-xl border border-line px-3 py-3 font-normal tabular-nums">
        </label>
        <p class="mt-2 text-sm text-muted">Harga emas belum ditetapkan bendahara. Angka ini hanya untuk menghitung nisab.</p>
    @endif

    <div class="mt-4 flex items-center justify-between gap-3">
        <p data-zakat-field-label class="text-sm font-semibold">Penghasilan bulan ini</p>
        <label data-zakat-known-wrap class="flex items-center gap-2 text-xs font-semibold text-muted">
            <input data-zakat-known type="checkbox">
            Saya tahu nominal zakatnya
        </label>
    </div>

    <input data-zakat-input data-zakat-field="income" type="text" inputmode="numeric" placeholder="0" class="mt-2 w-full rounded-xl border border-line px-3 py-3 tabular-nums">
    <input data-zakat-input data-zakat-field="wealth" type="text" inputmode="numeric" placeholder="0" hidden class="mt-2 w-full rounded-xl border border-line px-3 py-3 tabular-nums">
    <input data-zakat-input data-zakat-field="emas" type="text" inputmode="numeric" placeholder="0" hidden class="mt-2 w-full rounded-xl border border-line px-3 py-3 tabular-nums">
    <input data-zakat-input data-zakat-field="manual" type="text" inputmode="numeric" placeholder="0" hidden class="mt-2 w-full rounded-xl border border-line px-3 py-3 tabular-nums">

    <div class="mt-5 flex items-center justify-between gap-4 rounded-xl bg-nu-soft px-4 py-4">
        <p class="text-xs font-semibold tracking-wide text-nu">ZAKAT YANG HARUS DIBAYARKAN</p>
        <p class="text-lg font-bold tabular-nums">Rp <span data-zakat-result>0</span></p>
    </div>
    <p data-zakat-note class="mt-2 text-sm text-muted"></p>
    <a data-zakat-pay data-base="{{ route('donasi.create') }}" hidden class="btn-nu mt-5 w-full">Tunaikan Zakat</a>
</section>
