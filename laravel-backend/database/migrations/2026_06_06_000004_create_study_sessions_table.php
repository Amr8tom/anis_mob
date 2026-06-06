<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "Buddy session" == "Study session" — one table, two API projections.
        // Founder = host (user). Buddy "members" = session_participants rows.
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('host_id')->constrained('users');    // founder / buddy
            $table->string('title');                                 // title / topic
            $table->string('subject')->nullable();                   // buddy subject area
            $table->text('description')->nullable();
            $table->json('rules')->nullable();                       // buddy: list<string>
            $table->string('gift')->nullable();                      // buddy optional reward
            $table->enum('type', ['STUDY_GROUP', 'EVENT', 'FOCUS_BLOCK'])->default('STUDY_GROUP');
            $table->enum('status', ['UPCOMING', 'IN_PROGRESS', 'ENDED', 'CANCELLED'])->default('UPCOMING');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('max_seats')->nullable();                // maxParticipants / maxCapacity
            $table->string('time_label')->nullable();                // display, e.g. "2:00 م"
            $table->string('tag_label')->nullable();                 // home card short tag
            $table->string('tag_color_key')->nullable();             // blue|yellow|pink|green
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
        });

        Schema::create('session_participants', function (Blueprint $table) {
            $table->id();   // auto-increment PK: pivot rows are inserted via attach() (no model event)
            $table->foreignUuid('session_id')->constrained('study_sessions')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('joined_at')->useCurrent();

            $table->unique(['session_id', 'user_id']);               // join once
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_participants');
        Schema::dropIfExists('study_sessions');
    }
};
