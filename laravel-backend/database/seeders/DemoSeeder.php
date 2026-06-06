<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Availability;
use App\Enums\Gender;
use App\Enums\PlanTier;
use App\Enums\SessionStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\UserRole;
use App\Models\Badge;
use App\Models\Plan;
use App\Models\StudySession;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceDrink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Plans (free/silver/gold) ----
        $free = Plan::create([
            'name' => 'Free', 'tier' => PlanTier::FREE, 'price_cents' => 0,
            'currency' => 'EGP', 'included_minutes' => 300, 'duration_days' => 30, 'is_active' => true,
        ]);
        $silver = Plan::create([
            'name' => 'Silver Monthly', 'tier' => PlanTier::SILVER, 'price_cents' => 19900,
            'currency' => 'EGP', 'included_minutes' => 2400, 'duration_days' => 30, 'is_active' => true,
        ]);
        Plan::create([
            'name' => 'Gold Monthly', 'tier' => PlanTier::GOLD, 'price_cents' => 29900,
            'currency' => 'EGP', 'included_minutes' => 6000, 'duration_days' => 30, 'is_active' => true,
        ]);

        // ---- Badges catalog ----
        foreach (['streak' => 'Streak', 'hours' => 'Hours', 'sessions' => 'Sessions', 'top' => 'Top'] as $key => $label) {
            Badge::create(['key' => $key, 'label' => $label, 'icon_key' => $key]);
        }

        // ---- Demo login user (phone + password, no OTP) ----
        $demo = User::create([
            'full_name' => 'أنس التجريبي',
            'phone_number' => '01000000000',
            'email' => 'demo@anis.test',
            'whatsapp_number' => '01000000000',
            'password' => Hash::make('password'),
            'role' => UserRole::USER,
            'gender' => Gender::MALE,
            'is_guest' => false,
            'initials' => 'أ.ت',
            'university' => 'جامعة القاهرة',
            'study_field' => 'هندسة حاسبات',
            'interests' => ['برمجة', 'رياضيات'],
            'avatar_color_key' => 'blue',
            'availability' => Availability::ONLINE,
            'rating' => 4.8,
            'wallet_balance' => 150.00,
            'total_study_hours' => 120,
            'streak_days' => 12,
            'total_sessions' => 18,
        ]);

        Subscription::create([
            'user_id' => $demo->id,
            'plan_id' => $silver->id,
            'status' => SubscriptionStatus::ACTIVE,
            'started_at' => now(),
            'expires_at' => now()->addDays(30),
            'remaining_minutes' => 2400,
            'active_flag' => 1,
        ]);

        // A handful of other members to populate sessions/buddies.
        $members = User::factory()->count(6)->create();

        // ---- Workspaces (+ drinks). First one has a known QR token for manual testing. ----
        $primary = Workspace::factory()->create(['qr_token' => 'ws_demo_qr', 'name' => 'Anis Central']);
        $workspaces = collect([$primary])->merge(Workspace::factory()->count(3)->create());
        foreach ($workspaces as $workspace) {
            WorkspaceDrink::factory()->count(3)->create(['workspace_id' => $workspace->id]);
        }

        // ---- Sessions hosted by the demo user, with participants ----
        $first = $workspaces->first();
        StudySession::factory()->count(3)->create([
            'workspace_id' => $first->id,
            'host_id' => $demo->id,
            'status' => SessionStatus::UPCOMING,
        ])->each(function (StudySession $session) use ($members) {
            $session->participants()->attach(
                $members->random(3)->pluck('id')->all(),
                ['joined_at' => now()]
            );
        });
    }
}
