<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StudySession;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorkspaceNotificationTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $ownerPhone = '01200000003';
        $owner = WorkspaceOwner::where('phone_number', $ownerPhone)->first();

        if (!$owner) {
            $this->command->error("Owner with phone {$ownerPhone} not found.");
            return;
        }

        $workspace = Workspace::where('workspace_owner_id', $owner->id)->first();

        if (!$workspace) {
            $this->command->error("Workspace for owner {$ownerPhone} not found.");
            return;
        }

        $this->command->info("Found Workspace: {$workspace->name}");

        // Create 5 App Users with visits
        $users = User::factory()->count(5)->create();
        foreach ($users as $index => $user) {
            WorkspaceVisit::factory()->create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
            ]);
            // Give them devices so they show up as having a device
            $user->devices()->create([
                'token' => Str::random(40),
                'platform' => 'android'
            ]);
        }
        $this->command->info("Created 5 App Users with visits and devices.");

        // Create 2 Walk-in Visitors (no user_id)
        for ($i = 0; $i < 2; $i++) {
            $walkIn = WorkspaceWalkIn::create([
                'workspace_id' => $workspace->id,
                'full_name' => "Walk In " . Str::random(4),
                'phone_number' => '012' . rand(10000000, 99999999),
            ]);
            WorkspaceVisit::factory()->create([
                'workspace_id' => $workspace->id,
                'user_id' => null,
                'walk_in_id' => $walkIn->id,
            ]);
        }
        $this->command->info("Created 2 Walk-In Visitors.");

        // Create 2 Public Sessions (StudySession)
        $publicSessions = StudySession::factory()->count(2)->create([
            'workspace_id' => $workspace->id,
        ]);
        foreach ($publicSessions as $session) {
            // Attach 3 users
            $sessionUsers = $users->random(3);
            foreach ($sessionUsers as $u) {
                $session->participants()->attach($u->id);
            }
        }
        $this->command->info("Created 2 Public Sessions with participants.");

        // Create 2 Private Sessions
        $privateSessions = WorkspacePrivateSession::factory()->count(2)->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $users->first()->id,
        ]);
        foreach ($privateSessions as $session) {
            // Attach 3 users
            $sessionUsers = $users->random(3);
            foreach ($sessionUsers as $u) {
                WorkspacePrivateSessionAttendee::factory()->create([
                    'workspace_private_session_id' => $session->id,
                    'user_id' => $u->id,
                    'name_snapshot' => $u->full_name,
                    'phone_snapshot' => $u->phone_number,
                ]);
            }
        }
        $this->command->info("Created 2 Private Sessions with attendees.");
    }
}
