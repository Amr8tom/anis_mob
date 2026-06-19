<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspace_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('workspace_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('user_id')->constrained()->restrictOnDelete();

            $table->string('status')->default('ACTIVE');      // ACTIVE|EXHAUSTED|EXPIRED|CANCELLED
            // active_flag = 1 while ACTIVE, NULL otherwise. MySQL ignores NULLs in
            // unique indexes -> "one active subscription per (user, workspace)".
            $table->unsignedTinyInteger('active_flag')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('remaining_minutes')->default(0);
            $table->unsignedInteger('total_minutes')->default(0);

            // Snapshots so a later plan edit never mutates an issued subscription.
            $table->string('plan_name_snapshot');
            $table->unsignedInteger('duration_days_snapshot');
            $table->unsignedInteger('price_cents_snapshot')->nullable();
            $table->char('currency', 3)->default('EGP');

            $table->string('delivery_method');                // ACTIVATION_CODE|DIRECT_ASSIGNMENT
            $table->foreignUuid('issued_by_owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'workspace_id', 'active_flag']);
            $table->index(['workspace_id', 'status', 'expires_at']);
            $table->index(['user_id', 'workspace_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_subscriptions');
    }
};
