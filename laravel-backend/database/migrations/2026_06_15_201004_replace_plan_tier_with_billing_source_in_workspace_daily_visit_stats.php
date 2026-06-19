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
        \Illuminate\Support\Facades\DB::table('workspace_daily_visit_stats')->truncate();

        Schema::table('workspace_daily_visit_stats', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropUnique('workspace_daily_stats_unique');
            $table->dropIndex(['stat_date', 'plan_tier']);
            $table->dropColumn('plan_tier');

            $table->string('billing_source', 30);
            $table->unique(['workspace_id', 'stat_date', 'billing_source'], 'workspace_daily_stats_source_unique');
            $table->index(['stat_date', 'billing_source']);
            $table->foreign('workspace_id')->references('id')->on('workspaces')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('workspace_daily_visit_stats')->truncate();

        Schema::table('workspace_daily_visit_stats', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropUnique('workspace_daily_stats_source_unique');
            $table->dropIndex(['stat_date', 'billing_source']);
            $table->dropColumn('billing_source');

            $table->string('plan_tier', 20);
            $table->unique(['workspace_id', 'stat_date', 'plan_tier'], 'workspace_daily_stats_unique');
            $table->index(['stat_date', 'plan_tier']);
            $table->foreign('workspace_id')->references('id')->on('workspaces')->cascadeOnDelete();
        });
    }
};
