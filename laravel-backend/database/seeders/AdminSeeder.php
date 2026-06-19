<?php

namespace Database\Seeders;

use App\Enums\Availability;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phone = env('ADMIN_PHONE');
        $password = env('ADMIN_PASSWORD');
        $name = env('ADMIN_NAME', 'System Admin');
        $whatsapp = env('ADMIN_WHATSAPP', $phone);

        if (! $phone || ! $password) {
            $this->command->warn('ADMIN_PHONE or ADMIN_PASSWORD is not set. Skipping AdminSeeder.');

            return;
        }

        User::updateOrCreate(
            ['phone_number' => $phone],
            [
                'full_name' => $name,
                'whatsapp_number' => $whatsapp,
                'password' => Hash::make($password),
                'role' => UserRole::ADMIN->value,
                'availability' => Availability::OFFLINE->value,
                'profile_completed_at' => now(),
                'is_guest' => false,
                'admin_permissions' => ['*'],
                'admin_mfa_secret' => env('ADMIN_MFA_SECRET'),
                'admin_mfa_enabled' => filled(env('ADMIN_MFA_SECRET')),
            ]
        );

        $this->command->info('Admin user seeded successfully.');
    }
}
