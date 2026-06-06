<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One QR check-in -> check-out cycle. Maps to app WorkspaceAttendanceEntity.
        Schema::create('workspace_visits', function (Blueprint $table) {
            $table->uuid('id')->primary();                           // = attendance_id
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('subscription_id')->nullable()->constrained(); // which sub was charged
            $table->enum('status', ['CHECKED_IN', 'CHECKED_OUT'])->default('CHECKED_IN');
            $table->timestamp('check_in_at')->useCurrent();
            $table->timestamp('check_out_at')->nullable();
            $table->integer('duration_minutes')->nullable();         // = study_minutes (computed)
            // DB-level guarantee of "one active (CHECKED_IN) visit per user".
            $table->unsignedTinyInteger('active_flag')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->unique(['user_id', 'active_flag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_visits');
    }
};
