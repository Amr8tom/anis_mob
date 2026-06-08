<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class WorkspaceSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_settings(): void
    {
        $this->get('/workspace/settings')
            ->assertRedirect(route('workspace.login'));
    }

    public function test_owner_can_view_settings(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Original Name',
        ]);

        $this->actingAs($user)
            ->get('/workspace/settings')
            ->assertOk()
            ->assertViewIs('workspace.settings')
            ->assertSee('Original Name');
    }

    public function test_owner_can_update_workspace_details(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Original Name',
            'cover_image_url' => null,
            'gallery_images' => [],
        ]);

        $coverImage = UploadedFile::fake()->image('cover.jpg');
        $galleryImage1 = UploadedFile::fake()->image('g1.png');

        $payload = [
            'name' => 'Updated Workspace Name',
            'description' => 'A beautiful study workspace.',
            'address' => '5th Settlement, New Cairo',
            'latitude' => 30.012345,
            'longitude' => 31.543210,
            'capacity' => 50,
            'open_time' => '09:00',
            'close_time' => '22:00',
            'admin_phone' => '01000000000',
            'day_calculation_hours' => 6,
            'amenities' => ['wifi', 'ac'],
            'cover_image' => $coverImage,
            'gallery_images' => [$galleryImage1],
            'retained_gallery_images' => [],
            'status' => 'OPEN',
        ];

        $response = $this->actingAs($user)
            ->put('/workspace/settings', $payload);

        $response->assertRedirect(route('workspace.settings.edit'));

        $workspace->refresh();

        $this->assertEquals('Updated Workspace Name', $workspace->name);
        $this->assertEquals('A beautiful study workspace.', $workspace->description);
        $this->assertEquals('5th Settlement, New Cairo', $workspace->address);
        $this->assertEquals(30.012345, $workspace->latitude);
        $this->assertEquals(31.543210, $workspace->longitude);
        $this->assertEquals(50, $workspace->capacity);
        $this->assertEquals('09:00', $workspace->open_time);
        $this->assertEquals('22:00', $workspace->close_time);
        $this->assertEquals('01000000000', $workspace->admin_phone);
        $this->assertEquals(6, $workspace->day_calculation_hours);
        $this->assertEquals(['wifi', 'ac'], $workspace->amenities);

        // Verify storage file names and URL structure
        $this->assertNotNull($workspace->cover_image_url);
        $this->assertStringStartsWith('/storage/workspaces/'.$workspace->id, $workspace->cover_image_url);

        $this->assertCount(1, $workspace->gallery_images);
        $this->assertStringStartsWith('/storage/workspaces/'.$workspace->id.'/gallery', $workspace->gallery_images[0]);

        // Assert files actually exist in fake storage
        $coverDiskPath = str_replace('/storage/', '', $workspace->cover_image_url);
        $galleryDiskPath = str_replace('/storage/', '', $workspace->gallery_images[0]);

        Storage::disk('public')->assertExists($coverDiskPath);
        Storage::disk('public')->assertExists($galleryDiskPath);
    }

    public function test_owner_can_delete_gallery_images_by_not_retaining_them(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Workspace',
        ]);

        // Place mock images
        $path1 = UploadedFile::fake()->image('img1.png')->store("workspaces/{$workspace->id}/gallery", 'public');
        $path2 = UploadedFile::fake()->image('img2.png')->store("workspaces/{$workspace->id}/gallery", 'public');

        $url1 = '/storage/'.$path1;
        $url2 = '/storage/'.$path2;

        $workspace->update([
            'gallery_images' => [$url1, $url2],
        ]);

        Storage::disk('public')->assertExists($path1);
        Storage::disk('public')->assertExists($path2);

        // Submit form retaining only image 1
        $payload = [
            'name' => 'Workspace',
            'address' => 'Cairo',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'day_calculation_hours' => 8,
            'retained_gallery_images' => [$url1], // url2 is deleted
            'status' => 'OPEN',
        ];

        $this->actingAs($user)
            ->put('/workspace/settings', $payload)
            ->assertRedirect();

        $workspace->refresh();

        // Check DB update
        $this->assertCount(1, $workspace->gallery_images);
        $this->assertEquals([$url1], $workspace->gallery_images);

        // Check filesystem cleanup: image 2 should be deleted
        Storage::disk('public')->assertExists($path1);
        Storage::disk('public')->assertMissing($path2);
    }
}
