<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workspace_settlements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->foreignUuid('created_by_admin_id')->constrained('users')->restrictOnDelete();
            $table->unsignedInteger('total_visits');
            $table->unsignedInteger('unique_visitors');
            $table->unsignedBigInteger('total_minutes');
            $table->unsignedInteger('free_visits');
            $table->unsignedInteger('free_visitors');
            $table->unsignedBigInteger('free_minutes');
            $table->unsignedInteger('silver_visits');
            $table->unsignedInteger('silver_visitors');
            $table->unsignedBigInteger('silver_minutes');
            $table->unsignedInteger('gold_visits');
            $table->unsignedInteger('gold_visitors');
            $table->unsignedBigInteger('gold_minutes');
            $table->unsignedBigInteger('amount_cents');
            $table->char('currency', 3);
            $table->string('payment_method');
            $table->string('payment_reference')->nullable()->unique();
            $table->string('note')->nullable();
            $table->timestamp('period_started_at')->nullable();
            $table->timestamp('period_ended_at')->nullable();
            $table->timestamp('paid_at');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['workspace_id', 'paid_at']);
            $table->index(['workspace_id', 'period_started_at', 'period_ended_at'], 'settlements_workspace_period_index');
            $table->index(['created_by_admin_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_settlements');
    }
};
