<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspaces', function (Blueprint $table): void {
            $table->index(['is_active', 'latitude', 'longitude'], 'workspaces_active_geo_bbox_index');
        });
        Schema::table('study_sessions', function (Blueprint $table): void {
            $table->index(['type', 'status', 'start_time'], 'sessions_type_status_start_index');
            $table->index(['host_id', 'status', 'start_time'], 'sessions_host_status_start_index');
        });

        Schema::create('workspace_daily_visit_stats', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->date('stat_date');
            $table->string('plan_tier', 20);
            $table->unsignedBigInteger('visits_count')->default(0);
            $table->unsignedBigInteger('visitors_count')->default(0);
            $table->unsignedBigInteger('total_minutes')->default(0);
            $table->timestamps();
            $table->unique(['workspace_id', 'stat_date', 'plan_tier'], 'workspace_daily_stats_unique');
            $table->index(['stat_date', 'plan_tier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_daily_visit_stats');
        Schema::table('study_sessions', function (Blueprint $table): void {
            $table->dropIndex('sessions_type_status_start_index');
            $table->dropIndex('sessions_host_status_start_index');
        });
        Schema::table('workspaces', function (Blueprint $table): void {
            $table->dropIndex('workspaces_active_geo_bbox_index');
        });
    }
};
