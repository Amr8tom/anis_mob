<?php

namespace Database\Seeders;

use App\Models\Workspace;
use App\Models\WorkspaceOwner;
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
            $owner = WorkspaceOwner::updateOrCreate(
                ['phone_number' => $data['phone_number']],
                [
                    'full_name' => $data['full_name'],
                    'password' => Hash::make($ownerPassword),
                    'status' => 'active',
                ]
            );

            if (isset($workspaces[$index])) {
                $workspace = $workspaces[$index];
                $workspace->update([
                    'workspace_owner_id' => $owner->id,
                    'admin_phone' => $data['whatsapp_number'],
                ]);
            }
        }
        
        $this->command->info('Workspace owners seeded and assigned successfully.');
    }
}
