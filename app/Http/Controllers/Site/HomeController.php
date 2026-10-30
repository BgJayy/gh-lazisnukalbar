<?php

namespace App\Http\Controllers\Site;

use App\Enums\BranchType;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Pillar;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $base = fn () => Campaign::query()->published()->with(['branch', 'pillar', 'cover']);

        $hero = $base()->where('is_urgent', true)->latest()->first()
            ?? $base()->latest()->first();

        $paid = Donation::query()->where('payment_status', PaymentStatus::Paid);

        return view('public.home', [
            'hero' => $hero,
            'campaigns' => $base()->orderByDesc('is_urgent')->latest()->limit(6)->get(),
            'pillars' => Pillar::query()->orderBy('sort_order')->orderBy('name')->get(),
            'articles' => Article::query()->latestPublished()->limit(3)->get(),
            'stats' => [
                'collected' => (float) (clone $paid)->sum('amount'),
                'donors' => (clone $paid)->count(),
                'campaigns' => Campaign::query()->published()->count(),
                'branches' => Branch::query()->where('type', BranchType::Pc)->where('is_active', true)->count(),
            ],
        ]);
    }
}
