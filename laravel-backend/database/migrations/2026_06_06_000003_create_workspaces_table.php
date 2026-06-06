<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Secret value encoded in the workspace QR. Check-in resolves the workspace
            // from this token — the client never sends a workspace id directly.
            $table->string('qr_token')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->json('gallery_images')->nullable();              // list<string> urls
            $table->json('amenities')->nullable();                   // wifi|ac|coffee|printing|quiet
            $table->enum('status', ['OPEN', 'BUSY', 'FULL', 'CLOSED'])->default('OPEN');
            $table->integer('capacity')->nullable();
            $table->string('open_time')->nullable();                 // "08:00"
            $table->string('close_time')->nullable();                // "23:00"
            $table->integer('day_calculation_hours')->default(8);    // X hours -> one subscription day
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Drinks/menu served at a workspace (app: WorkspaceDrinkEntity)
        Schema::create('workspace_drinks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('icon');                                  // icon key
            $table->integer('price_cents');
            $table->timestamps();

            $table->index('workspace_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_drinks');
        Schema::dropIfExists('workspaces');
    }
};
