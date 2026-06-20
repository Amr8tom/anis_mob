<?php

namespace Database\Seeders;

use App\Enums\Availability;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkspaceOwnerSeeder extends Seeder
{
    public function run(): void
    {
        $ownerPassword = 'owner123';

        $ownerData = [
            ['full_name' => 'محمد صاحب المركزية', 'phone_number' => '01200000001', 'whatsapp_number' => '01200000001'],
            ['full_name' => 'فاطمة صاحبة المعادي', 'phone_number' => '01200000002', 'whatsapp_number' => '01200000002'],
            ['full_name' => 'يوسف صاحب مدينة نصر', 'phone_number' => '01200000003', 'whatsapp_number' => '01200000003'],
            ['full_name' => 'نور صاحبة الجيزة',   'phone_number' => '01200000004', 'whatsapp_number' => '01200000004'],
            ['full_name' => 'خالد صاحب الزمالك',   'phone_number' => '01200000005', 'whatsapp_number' => '01200000005'],
            ['full_name' => 'ليلى صاحبة مصر الجديدة', 'phone_number' => '01200000006', 'whatsapp_number' => '01200000006'],
        ];

        $workspaces = Workspace::orderBy('id')->get();

        foreach ($ownerData as $index => $data) {
            $owner = User::updateOrCreate(
                ['phone_number' => $data['phone_number']],
                [
                    'full_name' => $data['full_name'],
                    'whatsapp_number' => $data['whatsapp_number'],
                    'password' => Hash::make($ownerPassword),
                    'role' => UserRole::WORKSPACE_OWNER,
                    'is_guest' => false,
                    'availability' => Availability::OFFLINE,
                    'profile_completed_at' => now(),
                ]
            );

            if (isset($workspaces[$index])) {
                $workspace = $workspaces[$index];
                $workspace->update([
                    'owner_id' => $owner->id,
                    'admin_phone' => $owner->whatsapp_number,
                ]);
            }
        }
        
        $this->command->info('Workspace owners seeded and assigned successfully.');
    }
}
