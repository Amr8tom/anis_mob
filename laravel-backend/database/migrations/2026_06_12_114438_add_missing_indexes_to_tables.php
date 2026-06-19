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
        Schema::table('workspaces', function (Blueprint $table) {
            $table->index(['is_active', 'status'], 'workspaces_active_status_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['status', 'expires_at'], 'subscriptions_status_expires_index');
            $table->index(['plan_id', 'status'], 'subscriptions_plan_status_index');
        });

        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->index(['workspace_id', 'status'], 'visits_workspace_status_index');
            $table->index(['workspace_id', 'check_in_at'], 'visits_workspace_checkin_index');
            $table->index(['user_id', 'workspace_id', 'check_in_at'], 'visits_user_workspace_checkin_index');
            $table->index('check_in_at', 'visits_checkin_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->dropIndex('visits_workspace_status_index');
            $table->dropIndex('visits_workspace_checkin_index');
            $table->dropIndex('visits_user_workspace_checkin_index');
            $table->dropIndex('visits_checkin_index');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_status_expires_index');
            $table->dropIndex('subscriptions_plan_status_index');
        });

        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropIndex('workspaces_active_status_index');
        });
    }
};
