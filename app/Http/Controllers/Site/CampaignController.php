<?php

namespace App\Http\Controllers\Site;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Pillar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        $current = $request->string('pilar')->toString();

        $campaigns = Campaign::query()
            ->published()
            ->with(['branch', 'pillar', 'cover'])
            ->when($current !== '', function ($query) use ($current): void {
                $query->whereHas('pillar', fn ($pillar) => $pillar->where('slug', $current));
            })
            ->orderByDesc('is_urgent')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('public.campaign.index', [
            'campaigns' => $campaigns,
            'pillars' => Pillar::query()->orderBy('sort_order')->orderBy('name')->get(),
            'current' => $current,
        ]);
    }

    public function show(string $slug): View
    {
        $campaign = Campaign::query()
            ->published()
            ->with(['branch', 'pillar', 'cover'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.campaign.show', [
            'campaign' => $campaign,
            'supporters' => $campaign->donations()
                ->where('payment_status', PaymentStatus::Paid)
                ->latest()
                ->limit(12)
                ->get(),
            'related' => Campaign::query()
                ->published()
                ->with(['branch', 'pillar', 'cover'])
                ->where('pillar_id', $campaign->pillar_id)
                ->whereKeyNot($campaign->id)
                ->latest()
                ->limit(3)
                ->get(),
        ]);
    }
}
