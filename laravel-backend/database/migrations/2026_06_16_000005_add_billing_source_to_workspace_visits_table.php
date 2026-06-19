<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->foreignUuid('workspace_subscription_id')->nullable()->after('subscription_id')->constrained()->nullOnDelete();
            $table->string('billing_source')->default('FREE')->after('workspace_subscription_id');

            $table->index(['workspace_subscription_id', 'status']);
        });

        // Backfill: existing visits funded by a global subscription.
        DB::table('workspace_visits')
            ->whereNotNull('subscription_id')
            ->update(['billing_source' => 'GLOBAL_SUBSCRIPTION']);
        // Everything else stays the default 'FREE'.
    }

    public function down(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->dropIndex(['workspace_subscription_id', 'status']);
            $table->dropConstrainedForeignId('workspace_subscription_id');
            $table->dropColumn('billing_source');
        });
    }
};
