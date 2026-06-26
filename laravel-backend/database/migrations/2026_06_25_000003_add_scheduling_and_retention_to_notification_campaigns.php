<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_campaigns', function (Blueprint $table): void {
            if (! Schema::hasColumn('notification_campaigns', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('queued_at');
                $table->index(['status', 'scheduled_at'], 'notif_campaign_status_scheduled_idx');
            }

            if (! Schema::hasColumn('notification_campaigns', 'recipients_pruned_at')) {
                $table->timestamp('recipients_pruned_at')->nullable()->after('completed_at');
                $table->index(['recipients_pruned_at', 'created_at'], 'notif_campaign_pruned_created_idx');
            }
        });

        Schema::table('notification_recipients', function (Blueprint $table): void {
            $table->index(['campaign_id', 'user_id', 'status'], 'notif_rec_campaign_user_status_idx');
            $table->index('created_at', 'notif_rec_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('notification_recipients', function (Blueprint $table): void {
            $table->dropIndex('notif_rec_campaign_user_status_idx');
            $table->dropIndex('notif_rec_created_idx');
        });

        Schema::table('notification_campaigns', function (Blueprint $table): void {
            if (Schema::hasColumn('notification_campaigns', 'recipients_pruned_at')) {
                $table->dropIndex('notif_campaign_pruned_created_idx');
                $table->dropColumn('recipients_pruned_at');
            }

            if (Schema::hasColumn('notification_campaigns', 'scheduled_at')) {
                $table->dropIndex('notif_campaign_status_scheduled_idx');
                $table->dropColumn('scheduled_at');
            }
        });
    }
};
