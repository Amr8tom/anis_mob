<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspace_earnings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->foreignUuid('workspace_visit_id')->unique()->constrained('workspace_visits')->restrictOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('subscription_id')->nullable()->constrained('subscriptions')->restrictOnDelete();
            $table->foreignUuid('plan_id')->nullable()->constrained('plans')->restrictOnDelete();
            $table->string('plan_tier');
            $table->unsignedInteger('attended_minutes');
            $table->unsignedInteger('subscription_deducted_minutes');
            $table->unsignedInteger('payout_rate_cents_per_hour');
            $table->unsignedBigInteger('earned_amount_cents');
            $table->char('currency', 3);
            $table->string('status');
            $table->foreignUuid('settlement_id')->nullable()->constrained('workspace_settlements')->restrictOnDelete();
            $table->timestamp('earned_at');
            $table->timestamp('voided_at')->nullable();
            $table->foreignUuid('voided_by_admin_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('void_reason')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['workspace_id', 'status', 'earned_at']);
            $table->index(['user_id', 'earned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_earnings');
    }
};
