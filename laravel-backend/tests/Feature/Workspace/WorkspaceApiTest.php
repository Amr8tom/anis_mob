<?php

declare(strict_types=1);

namespace Tests\Feature\Workspace;

use App\Enums\SessionType;
use App\Models\StudySession;
use App\Models\Workspace;
use App\Models\WorkspaceDrink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WorkspaceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_is_public_and_paginated(): void
    {
        Workspace::factory()->count(3)->create();

        $this->getJson('/api/v1/workspaces')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success', 'message',
                'data' => [['id', 'name', 'status', 'currentOccupancy', 'galleryImages', 'amenities', 'drinks', 'sessions']],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    public function test_inactive_workspaces_are_hidden(): void
    {
        Workspace::factory()->create(['is_active' => true]);
        Workspace::factory()->create(['is_active' => false]);

        $this->getJson('/api/v1/workspaces')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
    }

    public function test_detail_returns_camelcase_shape_with_drinks(): void
    {
        $workspace = Workspace::factory()->create(['status' => 'OPEN']);
        WorkspaceDrink::factory()->create(['workspace_id' => $workspace->id, 'price_cents' => 2550]);

        $this->getJson("/api/v1/workspaces/{$workspace->id}")
            ->assertOk()
            ->assertJsonPath('data.status', 'open')               // lowercased for Flutter
            ->assertJsonPath('data.drinks.0.price', 25.5)         // cents -> currency
            ->assertJsonStructure(['data' => ['id', 'dayCalculationHours', 'distanceKm', 'currentOccupancy']]);
    }

    public function test_embedded_sessions_only_include_buddy_study_groups(): void
    {
        $workspace = Workspace::factory()->create();
        StudySession::factory()->create(['workspace_id' => $workspace->id]);
        StudySession::factory()->create([
            'workspace_id' => $workspace->id,
            'type' => SessionType::EVENT,
        ]);

        $this->getJson("/api/v1/workspaces/{$workspace->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data.sessions');
    }

    public function test_detail_404_for_inactive_workspace(): void
    {
        $workspace = Workspace::factory()->create(['is_active' => false]);

        $this->getJson("/api/v1/workspaces/{$workspace->id}")
            ->assertStatus(404)
            ->assertJsonPath('success', false);
    }
}
