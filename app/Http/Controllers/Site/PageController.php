<?php

namespace App\Http\Controllers\Site;

use App\Enums\BranchType;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Branch;
use App\Models\Pillar;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('public.profil.tentang');
    }

    public function vision(): View
    {
        return view('public.profil.visi-misi');
    }

    public function leaders(): View
    {
        return view('public.profil.pengurus', [
            'branches' => $this->branches(),
        ]);
    }

    public function executives(): View
    {
        return view('public.profil.eksekutif');
    }

    public function education(): View
    {
        return view('public.edukasi');
    }

    public function pillars(): View
    {
        return view('public.pilar.index', [
            'pillars' => Pillar::query()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function pillar(Pillar $pillar): View
    {
        return view('public.pilar.show', [
            'pillar' => $pillar,
            'campaigns' => $pillar->campaigns()
                ->published()
                ->with(['branch', 'pillar', 'cover'])
                ->orderByDesc('is_urgent')
                ->latest()
                ->get(),
        ]);
    }

    public function ziswaf(): View
    {
        return $this->ziswafScreen('zakat');
    }

    public function zakat(): View
    {
        return $this->ziswafScreen('zakat');
    }

    public function infak(): View
    {
        return $this->ziswafScreen('infak');
    }

    public function wakaf(): View
    {
        return $this->ziswafScreen('wakaf');
    }

    private function ziswafScreen(string $tab): View
    {
        $value = Setting::query()->where('key', Setting::GOLD_PRICE)->value('value');
        $goldPrice = is_numeric($value) && (float) $value > 0 ? (int) round((float) $value) : null;

        return view('public.ziswaf.index', [
            'tab' => $tab,
            'goldPrice' => $goldPrice,
        ]);
    }

    public function articles(): View
    {
        return view('public.berita.index', [
            'articles' => Article::query()->latestPublished()->get(),
        ]);
    }

    public function article(string $slug): View
    {
        $article = Article::query()->where('slug', $slug)->firstOrFail();

        return view('public.berita.show', [
            'article' => $article,
            'articles' => Article::query()->latestPublished()->whereKeyNot($article->id)->limit(6)->get(),
        ]);
    }

    public function confirm(): View
    {
        return view('public.layanan.konfirmasi');
    }

    public function offices(): View
    {
        return view('public.layanan.kantor', [
            'branches' => $this->branches(),
        ]);
    }

    public function accounts(): View
    {
        return view('public.layanan.rekening');
    }

    public function account(): View
    {
        return view('public.akun');
    }

    /**
     * @return Collection<int, Branch>
     */
    private function branches(): Collection
    {
        return Branch::query()
            ->where('is_active', true)
            ->get()
            ->sortBy(fn (Branch $branch): string => ($branch->type === BranchType::Pw ? '0' : '1').$branch->name)
            ->values();
    }
}
