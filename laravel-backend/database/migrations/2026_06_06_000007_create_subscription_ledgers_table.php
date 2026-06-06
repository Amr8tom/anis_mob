<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Immutable, append-only audit of every paid-balance change.
        // Written inside the same transaction as the deduction (see feature template §6.2).
        Schema::create('subscription_ledgers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Ledger history is immutable: keep rows even if the subscription is deleted.
            $table->foreignUuid('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('visit_id')->nullable()->constrained('workspace_visits')->nullOnDelete();
            $table->foreignUuid('user_id')->constrained();
            $table->integer('change_minutes');                       // negative = deduction, positive = top-up
            $table->integer('balance_after');                        // running balance snapshot
            $table->string('reason');                                // WORKSPACE_VISIT|TOP_UP|ADJUSTMENT|REFUND
            $table->timestamp('created_at')->useCurrent();           // no updated_at — rows are immutable

            $table->index(['subscription_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_ledgers');
    }
};
