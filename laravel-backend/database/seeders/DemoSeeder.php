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
            'name' => 'الاشتراك المجاني', 'tier' => PlanTier::FREE, 'price_cents' => 0,
            'currency' => 'EGP', 'included_minutes' => 0, 'duration_days' => 30, 'is_active' => true,
        ]);
        $silver = Plan::create([
            'name' => 'الاشتراك الفضي', 'tier' => PlanTier::SILVER, 'price_cents' => 170000, // 1,700 EGP
            'currency' => 'EGP', 'included_minutes' => 7200, 'duration_days' => 30, 'is_active' => true, // 120 hours
        ]);
        Plan::create([
            'name' => 'الاشتراك الذهبي', 'tier' => PlanTier::GOLD, 'price_cents' => 230000, // 2,300 EGP
            'currency' => 'EGP', 'included_minutes' => 12000, 'duration_days' => 30, 'is_active' => true, // 200 hours
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
            'remaining_minutes' => 7200, // 120 hours
            'active_flag' => 1,
        ]);

        // A handful of other members to populate sessions/buddies.
        $members = User::factory()->count(8)->create();

        // ---- Workspaces (rich, realistic Cairo data). First has the demo QR token. ----
        $definitions = [
            [
                'qr_token' => 'ws_demo_qr', 'name' => 'Anis Central', 'status' => 'OPEN',
                'address' => 'وسط البلد، القاهرة', 'lat' => 30.0444, 'lng' => 31.2357,
                'capacity' => 80, 'amenities' => ['wifi', 'ac', 'coffee', 'quiet'],
                'hour_multiplier' => 1.00, 'day_calculation_hours' => 8,
            ],
            [
                'qr_token' => 'ws_maadi', 'name' => 'StudyHub المعادي', 'status' => 'BUSY',
                'address' => 'شارع 9، المعادي', 'lat' => 29.9602, 'lng' => 31.2569,
                'capacity' => 50, 'amenities' => ['wifi', 'ac', 'printing'],
                'hour_multiplier' => 1.50, 'day_calculation_hours' => 8,
            ],
            [
                'qr_token' => 'ws_nasr', 'name' => 'Focus Space مدينة نصر', 'status' => 'OPEN',
                'address' => 'عباس العقاد، مدينة نصر', 'lat' => 30.0566, 'lng' => 31.3300,
                'capacity' => 120, 'amenities' => ['wifi', 'ac', 'coffee', 'printing', 'quiet'],
                'hour_multiplier' => 2.00, 'day_calculation_hours' => 6,
            ],
            [
                'qr_token' => 'ws_giza', 'name' => 'Quiet Corner الجيزة', 'status' => 'FULL',
                'address' => 'شارع الهرم، الجيزة', 'lat' => 30.0131, 'lng' => 31.2089,
                'capacity' => 40, 'amenities' => ['wifi', 'quiet'],
                'hour_multiplier' => 0.50, 'day_calculation_hours' => 8,
            ],
            [
                'qr_token' => 'ws_zamalek', 'name' => 'BrainPark الزمالك', 'status' => 'OPEN',
                'address' => 'شارع 26 يوليو، الزمالك', 'lat' => 30.0614, 'lng' => 31.2200,
                'capacity' => 60, 'amenities' => ['wifi', 'ac', 'coffee'],
                'hour_multiplier' => 0.00, 'day_calculation_hours' => 8, // Free workspace!
            ],
            [
                'qr_token' => 'ws_helio', 'name' => 'The Library مصر الجديدة', 'status' => 'CLOSED',
                'address' => 'شارع الميرغني، مصر الجديدة', 'lat' => 30.0880, 'lng' => 31.3220,
                'capacity' => 70, 'amenities' => ['wifi', 'ac', 'printing', 'quiet'],
                'hour_multiplier' => 1.70, 'day_calculation_hours' => 8,
            ],
        ];

        $drinkMenu = [
            ['name' => 'قهوة', 'icon' => 'coffee', 'price_cents' => 2500],
            ['name' => 'شاي', 'icon' => 'tea', 'price_cents' => 1500],
            ['name' => 'عصير برتقال', 'icon' => 'juice', 'price_cents' => 3000],
            ['name' => 'مياه', 'icon' => 'water', 'price_cents' => 1000],
        ];

        $workspaces = collect($definitions)->map(function (array $def) use ($drinkMenu): Workspace {
            $slug = str_replace('ws_', '', $def['qr_token']);
            $workspace = Workspace::create([
                'qr_token' => $def['qr_token'],
                'name' => $def['name'],
                'description' => 'مساحة عمل ومذاكرة مشتركة بخدمات متكاملة.',
                'address' => $def['address'],
                'latitude' => $def['lat'],
                'longitude' => $def['lng'],
                'cover_image_url' => "https://picsum.photos/seed/$slug/800/500",
                'gallery_images' => [
                    "https://picsum.photos/seed/$slug-1/800/500",
                    "https://picsum.photos/seed/$slug-2/800/500",
                    "https://picsum.photos/seed/$slug-3/800/500",
                ],
                'amenities' => $def['amenities'],
                'status' => $def['status'],
                'capacity' => $def['capacity'],
                'open_time' => '08:00',
                'close_time' => '23:00',
                'day_calculation_hours' => $def['day_calculation_hours'],
                'hour_multiplier' => $def['hour_multiplier'],
                'is_active' => true,
            ]);

            foreach ($drinkMenu as $drink) {
                WorkspaceDrink::create(['workspace_id' => $workspace->id] + $drink);
            }

            return $workspace;
        });

        // ---- Buddy / study sessions across workspaces (varied hosts & subjects) ----
        $hosts = collect([$demo])->merge($members);
        $subjects = [
            ['title' => 'الفيزياء — الفصل 4', 'subject' => 'فيزياء', 'tag' => 'فيز', 'color' => 'blue'],
            ['title' => 'مراجعة التفاضل والتكامل', 'subject' => 'رياضيات', 'tag' => 'ريض', 'color' => 'green'],
            ['title' => 'English Speaking Club', 'subject' => 'لغة إنجليزية', 'tag' => 'إنج', 'color' => 'yellow'],
            ['title' => 'هياكل البيانات', 'subject' => 'حاسبات', 'tag' => 'حاس', 'color' => 'pink'],
            ['title' => 'الكيمياء العضوية', 'subject' => 'كيمياء', 'tag' => 'كيم', 'color' => 'blue'],
            ['title' => 'مراجعة الأحياء', 'subject' => 'أحياء', 'tag' => 'حيا', 'color' => 'green'],
        ];

        foreach ($subjects as $index => $meta) {
            $host = $hosts[$index % $hosts->count()];
            $workspace = $workspaces[$index % $workspaces->count()];
            $start = now()->addHours($index + 1);

            $session = StudySession::create([
                'workspace_id' => $workspace->id,
                'host_id' => $host->id,
                'title' => $meta['title'],
                'subject' => $meta['subject'],
                'description' => 'جلسة مذاكرة جماعية. الانضمام مفتوح.',
                'rules' => ['الالتزام بالهدوء', 'الحضور في الميعاد'],
                'type' => 'STUDY_GROUP',
                'status' => $index === 0 ? SessionStatus::IN_PROGRESS : SessionStatus::UPCOMING,
                'start_time' => $start,
                'end_time' => (clone $start)->addHours(2),
                'max_seats' => 6,
                'time_label' => $start->format('g:i A'),
                'tag_label' => $meta['tag'],
                'tag_color_key' => $meta['color'],
            ]);

            $session->participants()->attach(
                $members->random(min(3, $members->count()))->pluck('id')->all(),
                ['joined_at' => now()],
            );
        }
    }
}
