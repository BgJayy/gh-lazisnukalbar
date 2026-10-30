<?php

namespace App\Http\Controllers\Site;

use App\Enums\DonationChannel;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function create(Request $request): View
    {
        $campaign = null;
        $missingCampaign = false;

        if ($slug = $request->string('campaign')->toString()) {
            $campaign = Campaign::query()
                ->published()
                ->with(['branch', 'pillar'])
                ->where('slug', $slug)
                ->first();
            $missingCampaign = $campaign === null;
        }

        $channel = $campaign
            ? DonationChannel::Campaign
            : DonationChannel::tryFrom($request->string('channel')->toString());

        if (! $campaign && ($channel === null || $channel === DonationChannel::Campaign)) {
            $channel = DonationChannel::Infak;
        }

        $amount = $request->has('amount') ? $request->integer('amount') : 100000;
        $amountRaised = $request->has('amount') && $amount < 10000;

        if ($amount < 10000) {
            $amount = $amountRaised ? 10000 : 100000;
        }

        $formToken = Str::random(40);
        session(['donation_form_token' => $formToken]);

        return view('public.donasi.form', [
            'campaign' => $campaign,
            'channel' => $channel,
            'amount' => $amount,
            'amountRaised' => $amountRaised,
            'missingCampaign' => $missingCampaign,
            'formToken' => $formToken,
            'channels' => array_values(array_filter(
                DonationChannel::cases(),
                fn (DonationChannel $item): bool => $item !== DonationChannel::Campaign,
            )),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $phone = trim($request->string('donor_phone')->toString());
        $prayer = trim($request->string('prayer_message')->toString());

        $request->merge([
            'donor_name' => trim($request->string('donor_name')->toString()),
            'donor_phone' => $phone === '' ? null : $phone,
            'prayer_message' => $prayer === '' ? null : $prayer,
        ]);

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:10000', 'max:1000000000'],
            'donor_name' => [$request->boolean('is_anonymous') ? 'nullable' : 'required', 'string', 'max:120'],
            'donor_phone' => ['nullable', 'regex:/^[0-9+\s-]{8,20}$/'],
            'prayer_message' => ['nullable', 'string', 'max:300'],
            'channel' => ['nullable', 'string'],
            'campaign_id' => ['nullable', 'integer'],
        ], [
            'amount.min' => 'Nominal minimal Rp10.000.',
            'donor_name.required' => 'Nama donatur wajib diisi, atau aktifkan sembunyikan nama.',
            'donor_phone.regex' => 'Nomor WhatsApp belum sesuai.',
        ]);

        if ($request->filled('campaign_id')) {
            $campaign = Campaign::query()->published()->find($request->integer('campaign_id'));

            if (! $campaign) {
                throw ValidationException::withMessages([
                    'campaign_id' => 'Kampanye tidak ditemukan atau belum tayang.',
                ]);
            }

            $channel = DonationChannel::Campaign;
            $book = $campaign->fund_book;
            $branchId = $campaign->branch_id;
            $campaignId = $campaign->id;
        } else {
            $channel = DonationChannel::tryFrom((string) $request->input('channel'));

            if (! $channel || $channel === DonationChannel::Campaign) {
                throw ValidationException::withMessages([
                    'channel' => 'Pilih kanal donasi.',
                ]);
            }

            $book = $channel->requiredBook();
            $branchId = Branch::query()->where('slug', 'pw-kalbar')->value('id');
            $campaignId = null;

            if (! $branchId) {
                throw ValidationException::withMessages([
                    'channel' => 'Cabang penerima belum tersedia.',
                ]);
            }
        }

        $this->consumeFormToken($request);

        $name = $data['donor_name'] ?: 'Hamba Allah';

        $donation = Donation::query()->create([
            'branch_id' => $branchId,
            'campaign_id' => $campaignId,
            'donor_name' => $name,
            'donor_phone' => $data['donor_phone'] ?? null,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'prayer_message' => $data['prayer_message'] ?? null,
            'fund_book' => $book,
            'channel' => $channel,
            'amount' => $data['amount'],
            'payment_method' => PaymentMethod::ManualTransfer,
            'payment_status' => PaymentStatus::Pending,
        ]);

        return redirect()->route('donasi.show', $donation->invoice_number);
    }

    public function show(string $invoice): View
    {
        $donation = $this->findInvoice($invoice);

        return view('public.donasi.show', [
            'donation' => $donation,
        ]);
    }

    public function lookup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_number' => ['required', 'string', 'max:40'],
        ], [
            'invoice_number.required' => 'Nomor invoice wajib diisi.',
        ]);

        $invoice = strtoupper(trim($data['invoice_number']));
        $exists = Donation::query()->where('invoice_number', $invoice)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'invoice_number' => 'Invoice tidak ditemukan. Periksa kembali kode yang Anda terima.',
            ]);
        }

        return redirect()->route('donasi.show', $invoice);
    }

    public function proof(Request $request, string $invoice): RedirectResponse
    {
        $donation = $this->findInvoice($invoice);

        if ($donation->payment_status !== PaymentStatus::Pending) {
            return redirect()
                ->route('donasi.show', $donation->invoice_number)
                ->withErrors(['proof' => 'Bukti hanya bisa diunggah saat status masih Menunggu.']);
        }

        if (filled($donation->proof_of_transfer)) {
            return redirect()
                ->route('donasi.show', $donation->invoice_number)
                ->withErrors(['proof' => 'Bukti transfer sudah tersimpan dan tidak bisa diganti dari halaman ini.']);
        }

        $request->validate([
            'proof' => ['required', 'image', 'max:2048'],
        ], [
            'proof.required' => 'Unggah bukti transfer terlebih dahulu.',
            'proof.image' => 'Bukti transfer harus berupa gambar.',
            'proof.max' => 'Ukuran bukti transfer maksimal 2 MB.',
        ]);

        $donation->proof_of_transfer = $request->file('proof')->store('donation-proofs', 'public');
        $donation->save();

        return redirect()
            ->route('donasi.show', $donation->invoice_number)
            ->with('status', 'Bukti transfer sudah diterima. Status tetap Menunggu sampai bendahara menyetujui.');
    }

    private function consumeFormToken(Request $request): void
    {
        $token = $request->string('form_token')->toString();
        $known = (string) session('donation_form_token', '');
        $message = 'Formulir ini sudah terkirim. Muat ulang halaman bila ingin membuat donasi baru.';

        if ($known === '' || ! hash_equals($known, $token)) {
            throw ValidationException::withMessages([
                'amount' => $message,
            ]);
        }

        if (! Cache::add('donation-form:'.hash('sha256', $token), true, now()->addHour())) {
            throw ValidationException::withMessages([
                'amount' => $message,
            ]);
        }

        session()->forget('donation_form_token');
    }

    private function findInvoice(string $invoice): Donation
    {
        return Donation::query()
            ->with(['campaign.branch', 'campaign.pillar', 'branch'])
            ->where('invoice_number', strtoupper($invoice))
            ->firstOrFail();
    }
}
