@extends('layouts.public')

@section('title', 'Donasi')

@php
    $selectedAmount = (int) old('amount', $amount);
    $chips = [10000, 25000, 50000, 100000, 250000];
    $selectedChannel = old('channel', $channel->value);
@endphp

@section('content')
    <x-page-header kicker="Donasi" title="Donasi" lead="Isi nominal, lalu selesaikan di QRIS dan rekening. Tidak ada payment gateway." />
    <div class="mx-auto max-w-xl px-4 py-10">
        <form method="post" action="{{ route('donasi.store') }}" class="grid gap-5 rounded-3xl border border-line bg-white p-6">
            @csrf
            <input type="hidden" name="form_token" value="{{ $formToken }}">
            @include('public.partials.alerts')
            @if ($missingCampaign)
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">Kampanye tidak ditemukan atau belum tayang.</div>
            @endif
            @if ($amountRaised)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">Nominal di bawah Rp10.000 disesuaikan ke minimum pembayaran.</div>
            @endif
            @if ($campaign)
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                <input type="hidden" name="channel" value="campaign">
                <div class="rounded-2xl bg-canvas p-4 text-sm leading-6">
                    <p class="font-semibold">{{ $campaign->title }}</p>
                    <p class="text-muted">{{ $campaign->fund_book->getLabel() }} · {{ $campaign->branch?->name }}</p>
                </div>
            @else
                <label class="grid gap-1 text-sm font-semibold">Kanal
                    <select name="channel" data-channel-select class="rounded-xl border border-line px-3 py-3 font-normal">
                        @foreach ($channels as $item)
                            <option value="{{ $item->value }}" data-book="{{ $item->requiredBook()->getLabel() }}" @selected($selectedChannel === $item->value)>{{ $item->getLabel() }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
            <p class="text-sm">Buku kas: <strong data-book-label>{{ $campaign ? $campaign->fund_book->getLabel() : $channel->requiredBook()?->getLabel() }}</strong></p>
            <div>
                <p class="text-sm font-semibold">Nominal</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($chips as $chip)
                        <button type="button" data-amount="{{ $chip }}" aria-pressed="{{ $selectedAmount === $chip ? 'true' : 'false' }}" class="rounded-full border border-line px-3 py-2 text-sm font-semibold">@rupiah($chip)</button>
                    @endforeach
                </div>
                <label class="mt-3 grid gap-1 text-sm font-semibold">Nominal lain
                    <input data-amount-input name="amount" type="number" min="10000" step="1000" value="{{ $selectedAmount }}" class="rounded-xl border border-line px-3 py-3 font-normal tabular-nums">
                </label>
            </div>
            <label class="grid gap-1 text-sm font-semibold">Nama donatur
                <input data-donor-name name="donor_name" value="{{ old('donor_name') }}" class="rounded-xl border border-line px-3 py-3 font-normal" autocomplete="name">
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input data-anon type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous'))>
                Sembunyikan nama. Tampil sebagai <strong data-donor-preview>Hamba Allah</strong>.
            </label>
            <label class="grid gap-1 text-sm font-semibold">Nomor WhatsApp
                <input name="donor_phone" value="{{ old('donor_phone') }}" placeholder="08xxxxxxxxxx" inputmode="tel" class="rounded-xl border border-line px-3 py-3 font-normal">
            </label>
            <label class="grid gap-1 text-sm font-semibold">Doa
                <input name="prayer_message" value="{{ old('prayer_message') }}" maxlength="300" class="rounded-xl border border-line px-3 py-3 font-normal">
            </label>
            <button class="btn-gold" type="submit">Lanjut bayar</button>
        </form>
    </div>
@endsection
