@props(['account'])

<div class="flex items-center justify-between gap-4 rounded-2xl border border-line bg-white p-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-wide text-nu">{{ $account->fund_book->getLabel() }}</p>
        <p class="mt-1 font-semibold">{{ $account->bank_name }}</p>
        <p class="font-mono text-lg tabular-nums tracking-wide">{{ $account->account_number }}</p>
        <p class="text-sm text-muted">a.n. {{ $account->account_holder }}</p>
    </div>
    <button type="button" data-copy="{{ $account->account_number }}" class="btn-line shrink-0 px-3 py-2">Salin</button>
</div>
