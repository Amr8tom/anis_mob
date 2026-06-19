<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reusable per-workspace client registry for room bookings.
        // Phone is the identity: first booking auto-creates the client, later
        // bookings reuse it so the owner never re-enters the data.
        Schema::create('room_clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique(['workspace_id', 'phone']);
            $table->index(['workspace_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_clients');
    }
};
