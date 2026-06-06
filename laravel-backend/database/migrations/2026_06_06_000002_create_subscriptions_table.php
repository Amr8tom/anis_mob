<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('plan_id')->constrained();
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'CANCELLED'])->default('ACTIVE');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();             // date-bound plans
            $table->integer('remaining_minutes')->nullable();        // time-balance; decremented on check-out
            // DB-level guarantee of "one ACTIVE subscription per user":
            // active_flag = 1 while ACTIVE, NULL otherwise. MySQL ignores NULLs in unique indexes.
            $table->unsignedTinyInteger('active_flag')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->unique(['user_id', 'active_flag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
