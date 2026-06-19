<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('room_id')->constrained('workspace_rooms')->restrictOnDelete();
            $table->foreignUuid('room_client_id')->constrained('room_clients')->restrictOnDelete();

            // Snapshots so list rendering needs no extra joins and edits to the
            // client/room never rewrite history.
            $table->string('client_name');
            $table->string('client_phone');

            $table->timestamp('starts_at');
            $table->timestamp('ends_at');                 // half-open: end exclusive
            $table->unsignedInteger('hourly_price_cents'); // room price snapshot
            $table->unsignedInteger('total_cost_cents');   // duration(min)/60 * price

            $table->string('status')->default('RESERVED'); // RESERVED | CANCELLED
            $table->string('note')->nullable();
            $table->uuid('series_id')->nullable();         // recurring series grouping
            $table->foreignUuid('created_by_owner_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['room_id', 'status', 'starts_at']);   // overlap check
            $table->index(['workspace_id', 'starts_at']);        // list/filter
            $table->index('room_client_id');                     // client history
            $table->index('series_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_reservations');
    }
};
