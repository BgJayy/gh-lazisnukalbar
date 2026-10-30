<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Enums\DonationChannel;
use App\Enums\FundBook;
use App\Enums\PaymentStatus;
use App\Models\Article;
use App\Models\BankAccount;
use App\Models\Campaign;
use App\Models\Donation;
use App\Support\VisitorAddress;
use Database\Seeders\BranchSeeder;
use Database\Seeders\PillarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_without_a_payment_gateway(): void
    {
        $this->seed([BranchSeeder::class, PillarSeeder::class]);
        $article = Article::query()->create([
            'slug' => 'berita-contoh',
            'title' => 'Berita contoh',
            'excerpt' => 'Ringkasan berita contoh.',
            'body' => '<p>Isi berita contoh.</p>',
            'category' => 'Berita',
            'published_at' => '2026-08-22 07:24:28',
        ]);

        $paths = [
            '/',
            '/profil/tentang',
            '/profil/visi-misi',
            '/profil/pengurus',
            '/profil/manajemen-eksekutif',
            '/pilar',
            '/pilar/nu-care-cerdas',
            '/pilar/nu-care-berdaya',
            '/pilar/nu-care-sehat',
            '/pilar/nu-care-damai',
            '/pilar/nu-care-hijau',
            '/ziswaf',
            '/ziswaf/zakat',
            '/ziswaf/infak',
            '/ziswaf/wakaf',
            '/campaign',
            '/edukasi',
            '/berita',
            '/berita/'.$article->slug,
            '/layanan/konfirmasi-donasi',
            '/layanan/kantor',
            '/layanan/rekening',
            '/donasi',
            '/akun',
        ];

        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/')
            ->assertSee('NU Care-LAZISNU')
            ->assertSee('Konfirmasi Donasi')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertDontSee('Midtrans')
            ->assertDontSee('Xendit');

        $this->get('/berita/tidak-ada')->assertNotFound();
        $this->get('/')->assertSee('NU Care Cerdas')->assertSee('NU Care Hijau')->assertDontSee('Nilai layanan');
        $this->get('/profil/tentang')
            ->assertSee('Sekilas NU Care - LAZISNU Kalbar')
            ->assertSee('ABDUL KHOLIQ, S.Sos')
            ->assertSee('Ketua NU Care-LAZISNU PWNU KALBAR')
            ->assertSee('HABIB ALI HASAN AL BAHAR')
            ->assertSee('Ketua NU Care-LAZISNU PBNU')
            ->assertDontSee('Siapa kami')
            ->assertDontSee('Cara dana dicatat')
            ->assertDontSee('Dasar layanan')
            ->assertDontSee('Berdiri sejak');
        $this->get('/ziswaf')
            ->assertSee('Jenis Zakat')
            ->assertSee('Tunaikan Zakat')
            ->assertSee('Zakat Profesi')
            ->assertDontSee('PWNU Jawa Barat');
        $this->get('/ziswaf/zakat')->assertSee('id="kalkulator"', false);
        $this->get('/ziswaf/infak')
            ->assertSee('Nominal Infak')
            ->assertSee('Tunaikan Infak')
            ->assertSee('id="fidyah"', false)
            ->assertSee('id="qurban"', false);
        $this->get('/ziswaf/wakaf')
            ->assertSee('Tunaikan Wakaf')
            ->assertSee('Wakaf NU Care-LAZISNU Kalimantan Barat');
        $this->get('/layanan/rekening')
            ->assertSee('Daftar Rekening')
            ->assertSee('1926202002')
            ->assertSee('1460019889455')
            ->assertSee('NU Care Lazisnu PWNU Kalbar')
            ->assertSee('QRIS NU Care Lazisnu Kalbar');
        $this->get('/profil/visi-misi')
            ->assertSee('Menjadi Lembaga Filantropi Islam Terkemuka')
            ->assertSee('NU CARE Venture')
            ->assertDontSee('Nilai layanan');
        $this->get('/pilar/nu-care-cerdas')->assertSee('Beasiswa Santri Tidak Mampu');
        $this->get('/campaign')->assertSee('NU Care Damai');
    }

    public function test_campaign_donation_stays_pending_and_uses_the_campaign_book(): void
    {
        $campaign = Campaign::factory()->create([
            'title' => 'Tanggap Bencana Banjir Kubu Raya',
            'status' => CampaignStatus::Published,
            'is_urgent' => true,
            'fund_book' => FundBook::InfakSedekah,
            'target_amount' => 250000000,
            'end_date' => now()->addDays(12)->toDateString(),
            'story_content' => "Banjir di Kubu Raya.\nWarga membutuhkan bantuan.",
        ]);

        $this->get(route('campaign.show', $campaign->slug))
            ->assertOk()
            ->assertSee('Tanggap Bencana Banjir Kubu Raya')
            ->assertSee('Donasi Sekarang')
            ->assertDontSee('QRIS');

        $this->post(route('donasi.store'), [
            'form_token' => $this->donationFormToken(),
            'campaign_id' => $campaign->id,
            'channel' => DonationChannel::Infak->value,
            'amount' => 50000,
            'donor_name' => 'Siti Aminah',
            'is_anonymous' => '1',
            'payment_status' => PaymentStatus::Paid->value,
            'fund_book' => FundBook::Zakat->value,
        ])->assertRedirect();

        $donation = Donation::query()->first();

        $this->assertNotNull($donation);
        $this->assertSame($campaign->id, $donation->campaign_id);
        $this->assertSame($campaign->branch_id, $donation->branch_id);
        $this->assertSame(DonationChannel::Campaign, $donation->channel);
        $this->assertSame(FundBook::InfakSedekah, $donation->fund_book);
        $this->assertSame(PaymentStatus::Pending, $donation->payment_status);
        $this->assertNull($donation->paid_at);
        $this->assertSame('0.00', $campaign->fresh()->collected_amount);
    }

    public function test_infak_payment_screen_hides_the_zakat_account(): void
    {
        $this->seed(BranchSeeder::class);

        BankAccount::query()->create([
            'bank_name' => 'Bank Kalbar Syariah',
            'account_number' => '1234567890',
            'account_holder' => 'LAZISNU Kalbar',
            'fund_book' => FundBook::InfakSedekah,
            'is_active' => true,
        ]);
        BankAccount::query()->create([
            'bank_name' => 'BSI',
            'account_number' => '0987654321',
            'account_holder' => 'LAZISNU Kalbar',
            'fund_book' => FundBook::Zakat,
            'is_active' => true,
        ]);

        $this->post(route('donasi.store'), [
            'form_token' => $this->donationFormToken(),
            'channel' => DonationChannel::Infak->value,
            'amount' => 100000,
            'donor_name' => 'Siti',
            'is_anonymous' => '1',
        ])->assertRedirect();

        $donation = Donation::query()->firstOrFail();

        $this->get(route('donasi.show', $donation->invoice_number))
            ->assertOk()
            ->assertSee('Menunggu')
            ->assertSee('QRIS NU Care Lazisnu Kalbar')
            ->assertSee('Saya sudah transfer')
            ->assertSee('1926202002')
            ->assertSee('1460019889455')
            ->assertDontSee('1234567890')
            ->assertDontSee('0987654321')
            ->assertDontSee('Pembayaran berhasil');

        Storage::fake('public');

        $this->post(route('donasi.proof', $donation->invoice_number), [])
            ->assertSessionHasErrors('proof');

        $this->post(route('donasi.proof', $donation->invoice_number), [
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect(route('donasi.show', $donation->invoice_number));

        $donation->refresh();

        $this->assertSame(PaymentStatus::Pending, $donation->payment_status);
        $this->assertNotNull($donation->proof_of_transfer);
        $this->assertMatchesRegularExpression('/^LZ\d{12}[A-Z0-9]{16}$/', $donation->invoice_number);

        $storedProof = $donation->proof_of_transfer;

        $this->post(route('donasi.proof', $donation->invoice_number), [
            'proof' => UploadedFile::fake()->image('bukti-baru.jpg'),
        ])->assertSessionHasErrors('proof');

        $this->assertSame($storedProof, $donation->fresh()->proof_of_transfer);

        $this->post(route('donasi.lookup'), [
            'invoice_number' => strtolower($donation->invoice_number),
        ])->assertRedirect(route('donasi.show', $donation->invoice_number));
    }

    public function test_draft_campaign_is_hidden(): void
    {
        $campaign = Campaign::factory()->create([
            'status' => CampaignStatus::Draft,
        ]);

        $this->get('/campaign/'.$campaign->slug)->assertNotFound();
    }

    public function test_small_donation_amount_is_raised_to_the_minimum(): void
    {
        $this->seed(BranchSeeder::class);

        $this->get('/donasi?channel=infak&amount=5000')
            ->assertOk()
            ->assertSee('value="10000"', false)
            ->assertSee('disesuaikan ke minimum pembayaran');

        $this->get('/donasi?channel=quick_infak')
            ->assertOk()
            ->assertSee('value="100000"', false)
            ->assertDontSee('disesuaikan ke minimum pembayaran');
    }

    public function test_article_body_drops_scripts_and_keeps_safe_images(): void
    {
        $article = Article::query()->create([
            'slug' => 'berita-bersih',
            'title' => 'Berita bersih',
            'excerpt' => 'Ringkasan.',
            'body' => '<p style="color:red" onclick="alert(1)">*Kholiq, S.Sos* Foto kegiatan</p><img src="/storage/articles/kegiatan.jpg" onerror="alert(1)" alt="kegiatan"><script>alert(1)</script>',
            'category' => 'Berita',
            'published_at' => '2026-08-22 07:24:28',
        ]);

        $this->get('/berita/'.$article->slug)
            ->assertOk()
            ->assertSee('Foto kegiatan')
            ->assertSee('Kholiq, S.Sos')
            ->assertSee('<strong>Kholiq, S.Sos</strong>', false)
            ->assertDontSee('*Kholiq', false)
            ->assertDontSee('color:red', false)
            ->assertSee('/storage/articles/kegiatan.jpg', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('onerror', false);
    }

    public function test_a_donation_form_cannot_be_submitted_twice(): void
    {
        $this->seed(BranchSeeder::class);
        $token = $this->donationFormToken();
        $payload = [
            'form_token' => $token,
            'channel' => DonationChannel::Infak->value,
            'amount' => 100000,
            'donor_name' => 'Siti',
            'is_anonymous' => '1',
        ];

        $this->post(route('donasi.store'), $payload)->assertRedirect();
        $this->post(route('donasi.store'), $payload)->assertSessionHasErrors('amount');

        $this->assertSame(1, Donation::query()->count());
    }

    public function test_rate_limits_use_the_nearest_public_visitor_address(): void
    {
        $request = Request::create('/', 'GET');
        $request->headers->set('X-Forwarded-For', '203.0.113.8, 198.51.100.20');

        $this->assertSame('198.51.100.20', VisitorAddress::forRateLimit($request));

        $request->headers->set('X-Forwarded-For', '198.51.100.20, 10.1.1.1');

        $this->assertSame('198.51.100.20', VisitorAddress::forRateLimit($request));
    }

    public function test_pages_share_the_site_description_favicon_and_sitemap(): void
    {
        $this->seed([BranchSeeder::class, PillarSeeder::class]);
        $article = Article::query()->create([
            'slug' => 'berita-peta',
            'title' => 'Berita peta situs',
            'excerpt' => 'Ringkasan berita peta situs.',
            'body' => '<p>Isi berita.</p>',
            'category' => 'Berita',
            'published_at' => '2026-08-22 07:24:28',
        ]);
        $published = Campaign::factory()->create([
            'title' => 'Campaign tayang peta',
            'status' => CampaignStatus::Published,
        ]);
        $draft = Campaign::factory()->create([
            'title' => 'Campaign draf tersembunyi',
            'slug' => 'campaign-draf-tersembunyi',
            'status' => CampaignStatus::Draft,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>NU Care-LAZISNU Kalimantan Barat</title>', false)
            ->assertSee('Merawat kepedulian dari Pontianak sampai pelosok', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('favicon-32.png', false)
            ->assertSee('property="og:description"', false)
            ->assertSee('https://schema.org', false)
            ->assertSee('href="'.route('ziswaf.infak').'" class="btn-gold fixed', false);

        $this->get('/layanan/rekening')
            ->assertSee('<title>Daftar Rekening — NU Care-LAZISNU Kalimantan Barat</title>', false);

        $this->get('/ziswaf')
            ->assertSee('rel="canonical" href="'.route('ziswaf.zakat').'"', false);
        $this->get('/ziswaf/infak')
            ->assertSee('<title>Infak — NU Care-LAZISNU Kalimantan Barat</title>', false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false)
            ->assertSee('Disallow: /donasi/', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('home'), false)
            ->assertSee(route('pilar.show', 'nu-care-cerdas'), false)
            ->assertSee(route('berita.show', $article->slug), false)
            ->assertSee(route('campaign.show', $published->slug), false)
            ->assertDontSee(route('campaign.show', $draft->slug), false)
            ->assertDontSee('/donasi/', false);

        $this->post(route('donasi.store'), [
            'form_token' => $this->donationFormToken(),
            'channel' => DonationChannel::Infak->value,
            'amount' => 50000,
            'donor_name' => 'Siti',
            'is_anonymous' => '1',
        ])->assertRedirect();

        $this->get(route('donasi.show', Donation::query()->first()->invoice_number))
            ->assertOk()
            ->assertSee('name="robots" content="noindex, nofollow"', false);
    }

    private function donationFormToken(): string
    {
        $this->get('/donasi');

        return (string) session('donation_form_token');
    }
}
