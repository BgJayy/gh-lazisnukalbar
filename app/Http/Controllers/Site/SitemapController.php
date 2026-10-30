<?php

namespace App\Http\Controllers\Site;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\Pillar;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController
{
    public function __invoke(): Response
    {
        $urls = [];

        foreach ([
            'home',
            'profil.tentang',
            'profil.visi',
            'profil.pengurus',
            'profil.eksekutif',
            'pilar.index',
            'ziswaf.zakat',
            'ziswaf.infak',
            'ziswaf.wakaf',
            'campaign.index',
            'edukasi',
            'berita.index',
            'layanan.konfirmasi',
            'layanan.kantor',
            'layanan.rekening',
            'donasi.create',
            'akun',
        ] as $name) {
            $urls[] = ['loc' => route($name)];
        }

        foreach (Pillar::query()->orderBy('sort_order')->orderBy('name')->get() as $pillar) {
            $urls[] = [
                'loc' => route('pilar.show', $pillar),
                'lastmod' => $this->stamp($pillar->updated_at),
            ];
        }

        foreach (Campaign::query()->published()->orderByDesc('updated_at')->get() as $campaign) {
            $urls[] = [
                'loc' => route('campaign.show', $campaign->slug),
                'lastmod' => $this->stamp($campaign->updated_at),
            ];
        }

        foreach (Article::query()->latestPublished()->get() as $article) {
            $urls[] = [
                'loc' => route('berita.show', $article->slug),
                'lastmod' => $this->stamp($article->updated_at),
            ];
        }

        return response()
            ->view('public.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function stamp(mixed $value): ?string
    {
        return $value instanceof Carbon ? $value->toAtomString() : null;
    }
}
