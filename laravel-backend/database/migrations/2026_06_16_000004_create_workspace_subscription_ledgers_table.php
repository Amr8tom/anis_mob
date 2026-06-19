<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Append-only audit trail for workspace-subscription balance changes.
        Schema::create('workspace_subscription_ledgers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('workspace_visit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('workspace_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('user_id')->constrained()->restrictOnDelete();
            $table->integer('change_minutes');        // negative = deduction/forfeit, positive = grant
            $table->integer('balance_after');
            $table->string('reason');                 // ACTIVATION|DIRECT_ASSIGNMENT|WORKSPACE_VISIT|EXPIRY_FORFEIT|CANCELLATION|ADMIN_ADJUSTMENT
            $table->timestamp('created_at')->useCurrent();   // no updated_at — immutable

            $table->index(['workspace_subscription_id', 'created_at'], 'ws_sub_ledgers_sub_id_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_subscription_ledgers');
    }
};
