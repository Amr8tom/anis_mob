<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Availability;
use App\Enums\Gender;
use App\Enums\PlanTier;
use App\Enums\SubscriptionStatus;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceWalkIn;
use App\Models\WorkspaceVisit;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DummyVisitorsSeeder extends Seeder
{
    public function run(): void
    {
        $workspaceId = '019ea7fd-79b1-73af-b4f4-b27a3d945d82'; // testspace1
        $workspace = Workspace::find($workspaceId);

        if (!$workspace) {
            $this->command->error('Workspace testspace1 not found.');
            return;
        }

        $ownerId = $workspace->owner_id ?? '019ea7fd-7990-710b-9a64-31a3f8adf7e6';

        $faker = Faker::create('ar_SA'); // Use Arabic faker for names
        $fakerEn = Faker::create();

        $planIds = [
            'FREE' => '019ea362-43ec-73c8-a783-732e425f83b1',
            'SILVER' => '019ea362-43ef-71ae-b833-ebdb51effe74',
            'GOLD' => '019ea362-43ef-71ae-b833-ebdb52b1755c',
        ];

        $this->command->info('Generating 1000 dummy clients and their visits...');

        // Track used phone numbers to prevent duplicates
        $usedPhones = DB::table('users')->pluck('phone_number')->concat(
            DB::table('workspace_walk_ins')->pluck('phone_number')
        )->unique()->mapWithKeys(fn($item) => [$item => $item])->toArray();

        $generateUniquePhone = function () use (&$usedPhones, $fakerEn) {
            do {
                $phone = '01' . $fakerEn->numerify('#########'); // 11 digits Egyptian style: 01xxxxxxxxx
            } while (isset($usedPhones[$phone]));
            $usedPhones[$phone] = $phone;
            return $phone;
        };

        $usersToInsert = [];
        $subscriptionsToInsert = [];
        $walkInsToInsert = [];
        $visitsToInsert = [];

        $hashedPassword = Hash::make('password123');
        $now = Carbon::now();

        // 1. Prepare data for 1000 clients
        for ($i = 0; $i < 1000; $i++) {
            $isAppUser = $fakerEn->boolean(70); // 70% registered app users, 30% walk-ins
            $name = $faker->name();
            $phone = $generateUniquePhone();

            if ($isAppUser) {
                $userId = (string) Str::uuid7();
                $initials = mb_substr($name, 0, 1) . (mb_strlen($name) > 5 ? mb_substr($name, mb_strpos($name, ' ') + 1, 1) : '');
                
                // Pick a plan tier distribution
                $tierRand = $fakerEn->numberBetween(1, 100);
                if ($tierRand <= 50) {
                    $planTier = 'FREE';
                } elseif ($tierRand <= 80) {
                    $planTier = 'SILVER';
                } else {
                    $planTier = 'GOLD';
                }

                $usersToInsert[] = [
                    'id' => $userId,
                    'full_name' => $name,
                    'phone_number' => $phone,
                    'email' => 'user_' . $i . '_' . Str::random(5) . '@anis.test',
                    'whatsapp_number' => $phone,
                    'password' => $hashedPassword,
                    'role' => UserRole::USER->value,
                    'gender' => $fakerEn->randomElement([Gender::MALE->value, Gender::FEMALE->value]),
                    'is_guest' => false,
                    'initials' => $initials,
                    'avatar_color_key' => $fakerEn->randomElement(['blue', 'green', 'red', 'gold', 'purple']),
                    'availability' => Availability::OFFLINE->value,
                    'rating' => $fakerEn->randomFloat(2, 3.5, 5.0),
                    'total_study_hours' => $fakerEn->numberBetween(10, 300),
                    'streak_days' => $fakerEn->numberBetween(0, 50),
                    'total_sessions' => $fakerEn->numberBetween(2, 100),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Create a subscription for paid tiers
                if ($planTier !== 'FREE') {
                    $subId = (string) Str::uuid7();
                    $subscriptionsToInsert[] = [
                        'id' => $subId,
                        'user_id' => $userId,
                        'plan_id' => $planIds[$planTier],
                        'status' => SubscriptionStatus::ACTIVE->value,
                        'started_at' => $now->copy()->subDays($fakerEn->numberBetween(1, 25)),
                        'expires_at' => $now->copy()->addDays($fakerEn->numberBetween(5, 30)),
                        'remaining_minutes' => $planTier === 'SILVER' ? $fakerEn->numberBetween(500, 7200) : $fakerEn->numberBetween(1000, 12000),
                        'active_flag' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Generate visits
                $numVisits = $fakerEn->numberBetween(1, 15);
                for ($v = 0; $v < $numVisits; $v++) {
                    $checkIn = $now->copy()->subDays($fakerEn->numberBetween(0, 45))->subHours($fakerEn->numberBetween(1, 23))->subMinutes($fakerEn->numberBetween(0, 59));
                    $duration = $fakerEn->numberBetween(30, 420);
                    $checkOut = $checkIn->copy()->addMinutes($duration);

                    $visitsToInsert[] = [
                        'id' => (string) Str::uuid7(),
                        'user_id' => $userId,
                        'walk_in_id' => null,
                        'workspace_id' => $workspaceId,
                        'subscription_id' => null, // keeping simple
                        'plan_tier_snapshot' => $planTier,
                        'status' => 'CHECKED_OUT',
                        'source' => 'QR',
                        'registered_by' => null,
                        'check_in_at' => $checkIn,
                        'check_out_at' => $checkOut,
                        'duration_minutes' => $duration,
                        'billable_minutes' => $duration,
                        'deducted_minutes' => $duration,
                        'hour_multiplier_applied' => 1.00,
                        'active_flag' => null,
                        'created_at' => $checkIn,
                        'updated_at' => $checkOut,
                    ];
                }
            } else {
                // Walk-in
                $walkInId = (string) Str::uuid7();
                $walkInsToInsert[] = [
                    'id' => $walkInId,
                    'workspace_id' => $workspaceId,
                    'full_name' => $name,
                    'phone_number' => $phone,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Generate visits
                $numVisits = $fakerEn->numberBetween(1, 8);
                for ($v = 0; $v < $numVisits; $v++) {
                    $checkIn = $now->copy()->subDays($fakerEn->numberBetween(0, 45))->subHours($fakerEn->numberBetween(1, 23))->subMinutes($fakerEn->numberBetween(0, 59));
                    $duration = $fakerEn->numberBetween(30, 420);
                    $checkOut = $checkIn->copy()->addMinutes($duration);

                    // Walk-ins can also be associated with different plan tier snapshots for testing view reports
                    $planTier = $fakerEn->randomElement(['FREE', 'SILVER', 'GOLD']);

                    $visitsToInsert[] = [
                        'id' => (string) Str::uuid7(),
                        'user_id' => null,
                        'walk_in_id' => $walkInId,
                        'workspace_id' => $workspaceId,
                        'subscription_id' => null,
                        'plan_tier_snapshot' => $planTier,
                        'status' => 'CHECKED_OUT',
                        'source' => 'OWNER',
                        'registered_by' => $ownerId,
                        'check_in_at' => $checkIn,
                        'check_out_at' => $checkOut,
                        'duration_minutes' => $duration,
                        'billable_minutes' => $duration,
                        'deducted_minutes' => $duration,
                        'hour_multiplier_applied' => 1.00,
                        'active_flag' => null,
                        'created_at' => $checkIn,
                        'updated_at' => $checkOut,
                    ];
                }
            }
        }

        // 2. Perform chunked bulk insertions for speed and memory efficiency
        $this->command->info('Inserting records into database...');

        DB::transaction(function () use ($usersToInsert, $subscriptionsToInsert, $walkInsToInsert, $visitsToInsert) {
            foreach (array_chunk($usersToInsert, 200) as $chunk) {
                DB::table('users')->insert($chunk);
            }
            foreach (array_chunk($subscriptionsToInsert, 200) as $chunk) {
                DB::table('subscriptions')->insert($chunk);
            }
            foreach (array_chunk($walkInsToInsert, 200) as $chunk) {
                DB::table('workspace_walk_ins')->insert($chunk);
            }
            foreach (array_chunk($visitsToInsert, 500) as $chunk) {
                DB::table('workspace_visits')->insert($chunk);
            }
        });

        $this->command->info('Seeding finished successfully! Added ' . count($usersToInsert) . ' app users, ' . count($walkInsToInsert) . ' walk-in visitors, and ' . count($visitsToInsert) . ' total visits.');
    }
}
