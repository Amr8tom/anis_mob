<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('workspace_private_sessions')) {
            Schema::create('workspace_private_sessions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('created_by_owner_id')->constrained('users')->restrictOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('host_name')->nullable();
                $table->timestamp('starts_at');
                $table->timestamp('ends_at')->nullable();
                $table->unsignedInteger('capacity')->nullable();
                $table->unsignedInteger('price_cents')->default(0);
                $table->string('status', 24)->default('active');
                $table->string('qr_token', 80)->unique();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['workspace_id', 'starts_at']);
                $table->index(['workspace_id', 'status']);
            });
        }

        if (! Schema::hasTable('workspace_private_session_attendees')) {
            Schema::create('workspace_private_session_attendees', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('workspace_private_session_id');
                $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignUuid('walk_in_id')->nullable()->constrained('workspace_walk_ins')->nullOnDelete();
                $table->string('name_snapshot');
                $table->string('phone_snapshot', 30);
                $table->string('phone_normalized', 30);
                $table->string('source', 24)->default('manual');
                $table->string('status', 24)->default('invited');
                $table->timestamp('checked_in_at')->nullable();
                $table->string('checked_in_method', 24)->nullable();
                $table->foreignUuid('checked_in_by_owner_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedInteger('amount_cents')->default(0);
                $table->string('payment_status', 24)->default('paid');
                $table->timestamps();

                $table->foreign('workspace_private_session_id', 'wps_attendees_session_fk')
                    ->references('id')
                    ->on('workspace_private_sessions')
                    ->cascadeOnDelete();
                $table->unique(['workspace_private_session_id', 'phone_normalized'], 'private_session_attendees_phone_unique');
                $table->index(['workspace_id', 'phone_normalized'], 'private_session_attendees_workspace_phone_idx');
                $table->index(['workspace_private_session_id', 'status'], 'private_session_attendees_status_idx');
                $table->index(['workspace_private_session_id', 'checked_in_at'], 'private_session_attendees_checkin_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_private_session_attendees');
        Schema::dropIfExists('workspace_private_sessions');
    }
};
