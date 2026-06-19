<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspace_subscription_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('workspace_plan_id')->constrained()->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('status')->default('UNUSED');       // UNUSED|REDEEMED|REVOKED|EXPIRED
            $table->foreignUuid('created_by_owner_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('redeemed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('workspace_subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'status']);
            $table->index(['workspace_plan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_subscription_codes');
    }
};
