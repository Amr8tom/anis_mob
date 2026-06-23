<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceCenterGradeLevel;
use App\Models\WorkspaceCenterSubject;
use App\Models\WorkspaceCenterTeacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WorkspaceEducationTest extends TestCase
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

    public function test_owner_can_manage_education_setup_for_own_workspace(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();

        $this->actingAs($owner)
            ->post(route('workspace.education.teachers.store'), [
                'name' => 'أستاذ أحمد',
                'phone_number' => '01011112222',
                'notes' => 'رياضيات',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($owner)
            ->post(route('workspace.education.subjects.store'), ['name' => 'رياضيات'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($owner)
            ->post(route('workspace.education.grade-levels.store'), ['name' => 'تانية ثانوي'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workspace_center_teachers', [
            'workspace_id' => $workspace->id,
            'name' => 'أستاذ أحمد',
        ]);
        $this->assertDatabaseHas('workspace_center_subjects', [
            'workspace_id' => $workspace->id,
            'name' => 'رياضيات',
        ]);
        $this->assertDatabaseHas('workspace_center_grade_levels', [
            'workspace_id' => $workspace->id,
            'name' => 'تانية ثانوي',
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.education.index'))
            ->assertOk()
            ->assertSee('إدارة التعليم')
            ->assertSee('أستاذ أحمد')
            ->assertSee('رياضيات')
            ->assertSee('تانية ثانوي');
    }

    public function test_education_items_are_workspace_scoped(): void
    {
        [$owner, $workspace] = $this->ownerWorkspace();
        $otherWorkspace = Workspace::factory()->create();
        WorkspaceCenterTeacher::create([
            'workspace_id' => $otherWorkspace->id,
            'name' => 'مدرس سنتر آخر',
            'is_active' => true,
        ]);
        WorkspaceCenterSubject::create([
            'workspace_id' => $workspace->id,
            'name' => 'علوم',
            'is_active' => true,
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.education.index'))
            ->assertOk()
            ->assertSee('علوم')
            ->assertDontSee('مدرس سنتر آخر');
    }
}
