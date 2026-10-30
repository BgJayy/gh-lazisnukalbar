<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Database\Seeder;
use RuntimeException;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_ADMIN_PASSWORD');

        if (blank($password)) {
            throw new RuntimeException('Set SEED_ADMIN_PASSWORD before seeding staff users.');
        }

        $pontianak = Branch::query()->where('slug', 'kota-pontianak')->firstOrFail();

        $this->user('Super Admin', 'admin@lazisnukalbar.test', $password, Roles::SUPER_ADMIN);
        $this->user('Bendahara PW', 'finance@lazisnukalbar.test', $password, Roles::FINANCE);
        $this->user('Editor Media', 'editor@lazisnukalbar.test', $password, Roles::EDITOR);
        $this->user('Admin PC Pontianak', 'pontianak@lazisnukalbar.test', $password, Roles::BRANCH_ADMIN, $pontianak->id);
    }

    private function user(string $name, string $email, string $password, string $role, ?int $branchId = null): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'branch_id' => $branchId,
                'email_verified_at' => now(),
            ],
        );

        $user->syncRoles([$role]);
    }
}
