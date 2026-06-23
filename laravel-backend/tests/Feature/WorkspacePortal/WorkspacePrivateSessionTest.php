<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceCenterGradeLevel;
use App\Models\WorkspaceCenterSubject;
use App\Models\WorkspaceCenterTeacher;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

final class WorkspacePrivateSessionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: User, 1: Workspace}
     */
    private function ownerWorkspace(): array
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

        return [$owner, $workspace];
    }

    public function test_owner_can_create_private_session_with_price(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $teacher = WorkspaceCenterTeacher::create([
            'workspace_id' => $workspace->id,
            'name' => 'أستاذ أحمد',
            'is_active' => true,
        ]);
        $subject = WorkspaceCenterSubject::create([
            'workspace_id' => $workspace->id,
            'name' => 'عربي',
            'is_active' => true,
        ]);
        $gradeLevel = WorkspaceCenterGradeLevel::create([
            'workspace_id' => $workspace->id,
            'name' => 'أولى ثانوي',
            'is_active' => true,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.private-sessions.store'), [
                'title' => 'جلسة مذاكرة خاصة',
                'description' => 'داخل مساحة العمل فقط',
                'host_name' => 'أحمد',
                'center_teacher_id' => $teacher->id,
                'center_subject_id' => $subject->id,
                'center_grade_level_id' => $gradeLevel->id,
                'starts_at' => now()->addDay()->format('Y-m-d H:i:s'),
                'ends_at' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
                'capacity' => 20,
                'price_pounds' => 150,
                'instructor_payout_type' => 'percentage',
                'instructor_payout_value' => 40,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('workspace_private_sessions', [
            'workspace_id' => $workspace->id,
            'title' => 'جلسة مذاكرة خاصة',
            'price_cents' => 15000,
            'center_teacher_id' => $teacher->id,
            'center_subject_id' => $subject->id,
            'center_grade_level_id' => $gradeLevel->id,
            'instructor_payout_type' => 'percentage',
            'instructor_payout_value' => 4000,
            'status' => 'active',
        ]);
    }

    public function test_private_sessions_index_uses_dark_section_style(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
            'title' => 'Dark Listed Session',
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.private-sessions.index'))
            ->assertOk()
            ->assertSee('private-session-list-page', false)
            ->assertSee('private-session-dark-card', false)
            ->assertSee('Dark Listed Session');
    }

    public function test_owner_adds_walk_in_and_can_check_in_immediately(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
            'price_cents' => 12000,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.private-sessions.attendees.store', $session), [
                'phone_number' => '01011112222',
                'name' => 'زائر خاص',
                'check_in_now' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workspace_walk_ins', [
            'workspace_id' => $workspace->id,
            'phone_number' => '01011112222',
            'full_name' => 'زائر خاص',
        ]);
        $this->assertDatabaseHas('workspace_private_session_attendees', [
            'workspace_private_session_id' => $session->id,
            'phone_normalized' => '01011112222',
            'status' => 'attended',
            'checked_in_method' => 'owner',
            'amount_cents' => 12000,
        ]);
    }

    public function test_csv_import_previews_rows_then_confirm_saves_valid_rows_only(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $user = User::factory()->create([
            'full_name' => 'App Visitor',
            'phone_number' => '01033334444',
        ]);
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
        ]);
        $file = UploadedFile::fake()->createWithContent(
            'attendees.csv',
            "phone,name\n01033334444,\n01099990000,\n"
        );

        $this->actingAs($owner)
            ->post(route('workspace.private-sessions.attendees.import', $session), [
                'attendees_file' => $file,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('workspace_private_session_import_batches', [
            'workspace_private_session_id' => $session->id,
            'status' => 'preview',
            'valid_count' => 1,
            'failed_count' => 1,
        ]);
        $this->assertDatabaseHas('workspace_private_session_import_rows', [
            'workspace_private_session_id' => $session->id,
            'phone_normalized' => '01033334444',
            'status' => 'valid',
        ]);

        $this->assertDatabaseMissing('workspace_private_session_attendees', [
            'workspace_private_session_id' => $session->id,
            'phone_normalized' => '01033334444',
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.private-sessions.show', $session))
            ->assertOk()
            ->assertSee('معاينة استيراد الحضور')
            ->assertSee('App Visitor')
            ->assertSee('الاسم مطلوب إذا كان الرقم غير مسجل في التطبيق');

        $this->actingAs($owner)
            ->post(route('workspace.private-sessions.attendees.import.confirm', $session))
            ->assertRedirect();

        $this->assertDatabaseHas('workspace_private_session_import_batches', [
            'workspace_private_session_id' => $session->id,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('workspace_private_session_attendees', [
            'workspace_private_session_id' => $session->id,
            'user_id' => $user->id,
            'name_snapshot' => 'App Visitor',
            'phone_normalized' => '01033334444',
            'source' => 'excel',
        ]);
        $this->assertDatabaseMissing('workspace_private_session_attendees', [
            'workspace_private_session_id' => $session->id,
            'phone_normalized' => '01099990000',
        ]);
    }

    public function test_csv_import_preview_marks_duplicate_rows(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
        ]);
        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_private_session_id' => $session->id,
            'workspace_id' => $workspace->id,
            'phone_snapshot' => '01044445555',
            'phone_normalized' => '01044445555',
        ]);
        $file = UploadedFile::fake()->createWithContent(
            'attendees.csv',
            "phone,name\n01044445555,Duplicate Visitor\n01088889999,New Visitor\n"
        );

        $this->actingAs($owner)
            ->post(route('workspace.private-sessions.attendees.import', $session), [
                'attendees_file' => $file,
            ])
            ->assertRedirect();

        $this->actingAs($owner)
            ->get(route('workspace.private-sessions.show', $session))
            ->assertOk()
            ->assertSee('مكرر')
            ->assertSee('Duplicate Visitor')
            ->assertSee('New Visitor');
    }

    public function test_app_user_can_check_in_by_qr_when_invited(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $user = User::factory()->create([
            'full_name' => 'QR Visitor',
            'phone_number' => '01055556666',
        ]);
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
            'price_cents' => 9000,
        ]);
        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_private_session_id' => $session->id,
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'name_snapshot' => $user->full_name,
            'phone_snapshot' => $user->phone_number,
            'phone_normalized' => '01055556666',
            'amount_cents' => 9000,
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-private-sessions/check-in', [
                'qr_token' => $session->qr_token,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('workspace_private_session_attendees', [
            'workspace_private_session_id' => $session->id,
            'phone_normalized' => '01055556666',
            'status' => 'attended',
            'checked_in_method' => 'qr',
        ]);

        $session->refresh()->load('attendees');
        $this->assertSame(1, $session->summary()['attended']);
        $this->assertSame(9000, $session->summary()['actual_revenue_cents']);
    }

    public function test_qr_check_in_rejects_user_not_added_to_private_session(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $user = User::factory()->create(['phone_number' => '01077778888']);
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-private-sessions/check-in', [
                'qr_token' => $session->qr_token,
            ])
            ->assertForbidden();
    }

    public function test_details_page_renders_attendee_search_and_green_check_in_action(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'created_by_owner_id' => $owner->id,
        ]);
        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_private_session_id' => $session->id,
            'workspace_id' => $workspace->id,
            'name_snapshot' => 'Searchable Visitor',
            'phone_snapshot' => '01012345678',
            'phone_normalized' => '01012345678',
            'status' => 'invited',
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.private-sessions.show', $session))
            ->assertOk()
            ->assertSee('private-session-attendee-search', false)
            ->assertSee('Searchable Visitor')
            ->assertSee('btn-primary', false)
            ->assertSee('private-session-check-in-now', false)
            ->assertSee('إنهاء الجلسة')
            ->assertSee('إلغاء الجلسة')
            ->assertSee('إنهاء = الجلسة تمت وانتهت')
            ->assertSee('استخدمه عندما تكون الجلسة انعقدت بالفعل')
            ->assertSee('استخدمه إذا لم تُعقد الجلسة');
    }
}
