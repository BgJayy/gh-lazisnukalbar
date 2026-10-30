<?php

use App\Http\Controllers\Site\CampaignController;
use App\Http\Controllers\Site\DonationController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    return response(implode("\n", [
        'User-agent: *',
        'Disallow: /panel-amil',
        'Disallow: /donasi/',
        '',
        'Sitemap: '.url('/sitemap.xml'),
        '',
    ]), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/', HomeController::class)->name('home');

Route::get('/profil/tentang', [PageController::class, 'about'])->name('profil.tentang');
Route::get('/profil/visi-misi', [PageController::class, 'vision'])->name('profil.visi');
Route::get('/profil/pengurus', [PageController::class, 'leaders'])->name('profil.pengurus');
Route::get('/profil/manajemen-eksekutif', [PageController::class, 'executives'])->name('profil.eksekutif');

Route::get('/pilar', [PageController::class, 'pillars'])->name('pilar.index');
Route::get('/pilar/{pillar:slug}', [PageController::class, 'pillar'])->name('pilar.show');

Route::get('/ziswaf', [PageController::class, 'ziswaf'])->name('ziswaf.index');
Route::get('/ziswaf/zakat', [PageController::class, 'zakat'])->name('ziswaf.zakat');
Route::get('/ziswaf/infak', [PageController::class, 'infak'])->name('ziswaf.infak');
Route::get('/ziswaf/wakaf', [PageController::class, 'wakaf'])->name('ziswaf.wakaf');

Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaign/{slug}', [CampaignController::class, 'show'])->name('campaign.show');

Route::get('/edukasi', [PageController::class, 'education'])->name('edukasi');

Route::get('/berita', [PageController::class, 'articles'])->name('berita.index');
Route::get('/berita/{slug}', [PageController::class, 'article'])->name('berita.show');

Route::get('/layanan/konfirmasi-donasi', [PageController::class, 'confirm'])->name('layanan.konfirmasi');
Route::get('/layanan/kantor', [PageController::class, 'offices'])->name('layanan.kantor');
Route::get('/layanan/rekening', [PageController::class, 'accounts'])->name('layanan.rekening');

Route::get('/akun', [PageController::class, 'account'])->name('akun');

Route::get('/donasi', [DonationController::class, 'create'])->name('donasi.create');
Route::post('/donasi', [DonationController::class, 'store'])->middleware('throttle:donation-store')->name('donasi.store');
Route::post('/donasi/cek', [DonationController::class, 'lookup'])->middleware('throttle:donation-lookup')->name('donasi.lookup');
Route::get('/donasi/{invoice}', [DonationController::class, 'show'])->where('invoice', '[A-Za-z0-9]+')->middleware('throttle:donation-show')->name('donasi.show');
Route::post('/donasi/{invoice}/bukti', [DonationController::class, 'proof'])->where('invoice', '[A-Za-z0-9]+')->middleware('throttle:donation-proof')->name('donasi.proof');
Route::get('/donasi/{invoice}/bukti', [DonationController::class, 'proofView'])->where('invoice', '[A-Za-z0-9]+')->middleware('throttle:donation-show')->name('donasi.proof.view');
