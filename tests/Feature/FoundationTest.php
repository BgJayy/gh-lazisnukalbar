<?php

namespace Tests\Feature;

use App\Enums\DonationChannel;
use App\Enums\FundBook;
use App\Enums\PaymentStatus;
use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Setting;
use App\Models\User;
use App\Support\Roles;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_paid_donation_updates_campaign_total_and_writes_audit(): void
    {
        $campaign = Campaign::factory()->create([
            'fund_book' => FundBook::InfakSedekah,
        ]);

        $donation = Donation::query()->create([
            'branch_id' => $campaign->branch_id,
            'campaign_id' => $campaign->id,
            'donor_name' => 'Hamba Allah',
            'is_anonymous' => true,
            'fund_book' => FundBook::InfakSedekah,
            'channel' => DonationChannel::Campaign,
            'amount' => 150000,
            'payment_status' => PaymentStatus::Pending,
        ]);

        $this->assertSame('0.00', $campaign->fresh()->collected_amount);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => Donation::class,
            'auditable_id' => $donation->id,
            'event' => 'created',
        ]);

        $donation->update(['payment_status' => PaymentStatus::Paid]);

        $this->assertSame('150000.00', $campaign->fresh()->collected_amount);
        $this->assertNotNull($donation->fresh()->paid_at);
        $this->assertSame(1, AuditLog::query()->where('event', 'status_changed')->count());
    }

    public function test_zakat_channel_cannot_use_infak_book(): void
    {
        $branch = Branch::factory()->create();

        $this->expectException(ValidationException::class);

        Donation::query()->create([
            'branch_id' => $branch->id,
            'donor_name' => 'Ahmad',
            'fund_book' => FundBook::InfakSedekah,
            'channel' => DonationChannel::ZakatPenghasilan,
            'amount' => 100000,
        ]);
    }

    public function test_campaign_rejects_zakat_unless_it_accepts_zakat(): void
    {
        $campaign = Campaign::factory()->create([
            'fund_book' => FundBook::InfakSedekah,
            'accepts_zakat' => false,
        ]);

        $this->expectException(ValidationException::class);

        Donation::query()->create([
            'branch_id' => $campaign->branch_id,
            'campaign_id' => $campaign->id,
            'donor_name' => 'Ahmad',
            'fund_book' => FundBook::Zakat,
            'channel' => DonationChannel::Campaign,
            'amount' => 100000,
        ]);
    }

    public function test_wakaf_is_its_own_book(): void
    {
        $branch = Branch::factory()->create();

        $donation = Donation::query()->create([
            'branch_id' => $branch->id,
            'donor_name' => 'Wakif',
            'fund_book' => FundBook::Wakaf,
            'channel' => DonationChannel::Wakaf,
            'amount' => 500000,
        ]);

        $this->assertSame(FundBook::Wakaf, $donation->fund_book);
    }

    public function test_settings_reject_secret_keys(): void
    {
        $this->expectException(ValidationException::class);

        Setting::query()->create([
            'key' => 'midtrans_server_key',
            'value' => 'secret',
        ]);
    }

    public function test_branch_admin_only_sees_own_campaigns(): void
    {
        $own = Campaign::factory()->create();
        Campaign::factory()->create();

        $admin = User::factory()->create(['branch_id' => $own->branch_id]);
        $admin->assignRole(Roles::BRANCH_ADMIN);

        $this->actingAs($admin);

        $visible = Campaign::query()->visibleTo()->pluck('id');

        $this->assertTrue($visible->contains($own->id));
        $this->assertCount(1, $visible);
        $this->assertTrue($admin->canAccessPanel(filament()->getDefaultPanel()));
    }

    public function test_donor_cannot_open_admin_panel(): void
    {
        $donor = User::factory()->create();
        $donor->assignRole(Roles::DONOR);

        $this->assertFalse($donor->canAccessPanel(filament()->getDefaultPanel()));
        $this->actingAs($donor)->get('/admin')->assertForbidden();
    }

    public function test_super_admin_can_open_panel_and_branch_admin_cannot_manage_users(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Roles::SUPER_ADMIN);

        $this->actingAs($admin)->get('/admin')->assertRedirect();

        $this->enableAppAuthentication($admin);

        $branchAdmin = User::factory()->create([
            'branch_id' => Branch::factory(),
        ]);
        $branchAdmin->assignRole(Roles::BRANCH_ADMIN);
        $this->enableAppAuthentication($branchAdmin);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/branches')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertOk();

        $this->actingAs($branchAdmin)->get('/admin')->assertOk();
        $this->actingAs($branchAdmin)->get('/admin/users')->assertForbidden();
        $this->actingAs($branchAdmin)->get('/admin/campaigns')->assertOk();
    }

    private function enableAppAuthentication(User $user): void
    {
        $user->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        $user->saveAppAuthenticationRecoveryCodes(['recovery-code']);
    }
}
