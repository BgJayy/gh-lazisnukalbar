@extends('layouts.public')

@section('title', 'Selesaikan donasi '.$donation->invoice_number)
@section('robots', 'noindex, nofollow')

@php
    $statusClass = match ($donation->payment_status) {
        \App\Enums\PaymentStatus::Paid => 'bg-nu-soft text-nu-dark',
        \App\Enums\PaymentStatus::Expired => 'bg-red-50 text-red-800',
        default => 'bg-amber-50 text-amber-800',
    };
@endphp

@section('content')
    <x-page-header kicker="Donasi" title="Selesaikan donasi" lead="Scan QRIS atau transfer ke salah satu rekening di bawah. Keduanya berlaku untuk zakat, infak, wakaf, dan campaign." />
    <div class="mx-auto grid max-w-3xl gap-4 px-4 py-10">
        @include('public.partials.alerts')
        <section class="rounded-3xl border border-line bg-white p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="font-mono text-sm">{{ $donation->invoice_number }}</p>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $donation->payment_status->getLabel() }}</span>
            </div>
            <button type="button" data-copy="{{ $donation->invoice_number }}" class="btn-line mt-3 px-3 py-2">Salin invoice</button>
            <dl class="mt-4 grid gap-2 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-muted">Nominal</dt><dd class="font-semibold tabular-nums">@rupiah($donation->amount)</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Buku kas</dt><dd>{{ $donation->fund_book->getLabel() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Kanal</dt><dd>{{ $donation->channel->getLabel() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-muted">Nama</dt><dd>{{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name }}</dd></div>
                @if ($donation->campaign)
                    <div class="flex justify-between gap-4"><dt class="text-muted">Kampanye</dt><dd class="text-right">{{ $donation->campaign->title }}</dd></div>
                @endif
            </dl>
            @if ($donation->payment_status === \App\Enums\PaymentStatus::Pending)
                <p class="mt-4 text-sm leading-6 text-muted">Bendahara akan memeriksa bukti transfer. Status menjadi Lunas setelah disetujui.</p>
            @elseif ($donation->payment_status === \App\Enums\PaymentStatus::Paid)
                <p class="mt-4 text-sm leading-6 text-muted">Donasi ini sudah disetujui bendahara.</p>
            @else
                <p class="mt-4 text-sm leading-6 text-muted">Donasi ini kedaluwarsa. Buat donasi baru bila masih ingin berpartisipasi.</p>
            @endif
        </section>

        <x-qris-panel />

        <section class="grid gap-3">
            <h2 class="text-lg font-bold">Transfer bank</h2>
            @foreach (\App\Support\OfficialAccounts::all() as $account)
                <div class="flex items-center justify-between gap-4 rounded-2xl border border-line bg-white p-4">
                    <div>
                        <p class="font-semibold">{{ $account['bank'] }}</p>
                        <p class="font-mono text-lg tabular-nums tracking-wide">{{ $account['number'] }}</p>
                        <p class="text-sm text-muted">a.n. {{ $account['holder'] }}</p>
                    </div>
                    <button type="button" data-copy="{{ $account['number'] }}" class="btn-line shrink-0 px-3 py-2">Salin</button>
                </div>
            @endforeach
            <p class="text-sm leading-6 text-muted">Kedua rekening digunakan untuk zakat, infak, wakaf, dan seluruh campaign.</p>
        </section>

        @if ($donation->payment_status === \App\Enums\PaymentStatus::Pending)
            <form method="post" action="{{ route('donasi.proof', $donation->invoice_number) }}" enctype="multipart/form-data" class="grid gap-3 rounded-3xl border border-line bg-white p-5">
                @csrf
                @if ($donation->proof_of_transfer)
                    <img src="{{ route('donasi.proof.view', $donation->invoice_number) }}" alt="Bukti transfer" class="h-32 w-32 rounded-xl border border-line object-cover">
                    <p class="text-sm leading-6 text-muted">Bukti sudah tersimpan. Status tetap Menunggu sampai bendahara menyetujui.</p>
                @else
                    <label class="grid gap-1 text-sm font-semibold">Unggah bukti transfer
                        <input type="file" name="proof" accept="image/*" required class="font-normal">
                    </label>
                    <button class="btn-gold" type="submit">Saya sudah transfer</button>
                @endif
            </form>
        @endif

        @if ($donation->campaign)
            <a class="btn-line" href="{{ route('campaign.show', $donation->campaign->slug) }}">Lihat kampanye</a>
        @endif
    </div>
@endsection
