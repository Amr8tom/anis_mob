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
        Schema::table('workspace_settlements', function (Blueprint $table) {
            // Keep legacy snapshots readable while new settlements group paid
            // visits by funding source instead of Silver/Gold.
            $table->unsignedInteger('silver_visits')->default(0)->change();
            $table->unsignedInteger('silver_visitors')->default(0)->change();
            $table->unsignedBigInteger('silver_minutes')->default(0)->change();
            $table->unsignedInteger('gold_visits')->default(0)->change();
            $table->unsignedInteger('gold_visitors')->default(0)->change();
            $table->unsignedBigInteger('gold_minutes')->default(0)->change();

            $table->unsignedInteger('global_subscription_visits')->default(0)->after('free_minutes');
            $table->unsignedInteger('global_subscription_visitors')->default(0)->after('global_subscription_visits');
            $table->unsignedBigInteger('global_subscription_minutes')->default(0)->after('global_subscription_visitors');

            $table->unsignedInteger('workspace_subscription_visits')->default(0)->after('global_subscription_minutes');
            $table->unsignedInteger('workspace_subscription_visitors')->default(0)->after('workspace_subscription_visits');
            $table->unsignedBigInteger('workspace_subscription_minutes')->default(0)->after('workspace_subscription_visitors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_settlements', function (Blueprint $table) {
            $table->dropColumn([
                'global_subscription_visits',
                'global_subscription_visitors',
                'global_subscription_minutes',
                'workspace_subscription_visits',
                'workspace_subscription_visitors',
                'workspace_subscription_minutes',
            ]);
        });
    }
};
