<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plan_activation_codes', function (Blueprint $table) {
            $table->string('status')->default('ACTIVE')->after('code'); // ACTIVE, REDEEMED, VOIDED
            $table->foreignUuid('created_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('voided_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('redeemed_subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();

            $table->index('status');
        });

        // Migrate existing rows
        DB::table('plan_activation_codes')
            ->where('is_used', true)
            ->update(['status' => 'REDEEMED']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_activation_codes', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropConstrainedForeignId('created_by_admin_id');
            $table->dropConstrainedForeignId('voided_by_admin_id');
            $table->dropConstrainedForeignId('redeemed_subscription_id');
            $table->dropColumn(['status', 'voided_at']);
        });
    }
};
