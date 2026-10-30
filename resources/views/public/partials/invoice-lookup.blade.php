<form method="post" action="{{ route('donasi.lookup') }}" class="grid gap-3">
    @csrf
    <label class="grid gap-1 text-sm font-semibold" for="invoice_number">Nomor invoice
        <input id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="LZ..." class="rounded-xl border border-line px-3 py-3 font-normal" autocomplete="off">
    </label>
    <button class="btn-nu" type="submit">Cek status</button>
</form>
