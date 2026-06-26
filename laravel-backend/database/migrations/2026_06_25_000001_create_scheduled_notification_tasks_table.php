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
            if (! Schema::hasColumn('workspaces', 'notification_locale')) {
                $table->string('notification_locale', 8)->default('ar')->after('checkout_mode');
            }
        });

        if (! Schema::hasTable('scheduled_notification_tasks')) {
            Schema::create('scheduled_notification_tasks', function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->foreignUuid('workspace_id')->nullable()->constrained('workspaces')->nullOnDelete();
                $table->string('type', 64);
                $table->string('entity_type', 64);
                $table->uuid('entity_id')->nullable();
                $table->timestamp('due_at');
                $table->string('status', 32)->default('pending');
                $table->string('idempotency_key', 191)->unique();
                $table->json('payload')->nullable();
                $table->foreignUuid('campaign_id')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->foreign('campaign_id', 'sched_notif_campaign_fk')
                    ->references('id')->on('notification_campaigns')->nullOnDelete();

                $table->index(['status', 'due_at', 'id'], 'sched_notif_status_due_idx');
                $table->index(['workspace_id', 'type', 'due_at'], 'sched_notif_workspace_type_due_idx');
                $table->index(['entity_type', 'entity_id', 'type'], 'sched_notif_entity_type_idx');
            });
        }

        Schema::table('workspace_private_sessions', function (Blueprint $table): void {
            $table->index(['status', 'starts_at', 'id'], 'wps_status_starts_id_idx');
        });

        Schema::table('workspace_subscriptions', function (Blueprint $table): void {
            $table->index(['status', 'remaining_minutes', 'id'], 'ws_subs_status_remaining_idx');
        });
    }

    public function down(): void
    {
        Schema::table('workspace_subscriptions', function (Blueprint $table): void {
            $table->dropIndex('ws_subs_status_remaining_idx');
        });

        Schema::table('workspace_private_sessions', function (Blueprint $table): void {
            $table->dropIndex('wps_status_starts_id_idx');
        });

        Schema::dropIfExists('scheduled_notification_tasks');

        Schema::table('workspaces', function (Blueprint $table): void {
            if (Schema::hasColumn('workspaces', 'notification_locale')) {
                $table->dropColumn('notification_locale');
            }
        });
    }
};
