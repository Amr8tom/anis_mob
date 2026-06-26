<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_notification_preferences')) {
            Schema::create('user_notification_preferences', function (Blueprint $table): void {
                $table->foreignUuid('user_id')->primary()->constrained()->cascadeOnDelete();
                $table->boolean('session_reminders')->default(true);
                $table->boolean('subscription_alerts')->default(true);
                $table->boolean('offers_marketing')->default(true);
                $table->boolean('workspace_updates')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('notification_campaigns', function (Blueprint $table): void {
            if (! Schema::hasColumn('notification_campaigns', 'notification_category')) {
                $table->string('notification_category', 64)
                    ->default('workspace_updates')
                    ->after('target_type');
                $table->index(['notification_category', 'created_at'], 'notif_campaign_category_created_idx');
            }

            if (! Schema::hasColumn('notification_campaigns', 'opened_count')) {
                $table->unsignedBigInteger('opened_count')->default(0)->after('skipped_count');
            }

            if (! Schema::hasColumn('notification_campaigns', 'clicked_count')) {
                $table->unsignedBigInteger('clicked_count')->default(0)->after('opened_count');
            }
        });

        Schema::table('notification_recipients', function (Blueprint $table): void {
            if (! Schema::hasColumn('notification_recipients', 'opened_at')) {
                $table->timestamp('opened_at')->nullable()->after('sent_at');
                $table->index(['campaign_id', 'opened_at'], 'notif_rec_campaign_opened_idx');
            }

            if (! Schema::hasColumn('notification_recipients', 'clicked_at')) {
                $table->timestamp('clicked_at')->nullable()->after('opened_at');
                $table->index(['campaign_id', 'clicked_at'], 'notif_rec_campaign_clicked_idx');
            }

            if (! Schema::hasColumn('notification_recipients', 'open_count')) {
                $table->unsignedInteger('open_count')->default(0)->after('clicked_at');
            }

            if (! Schema::hasColumn('notification_recipients', 'click_count')) {
                $table->unsignedInteger('click_count')->default(0)->after('open_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_recipients', function (Blueprint $table): void {
            if (Schema::hasColumn('notification_recipients', 'clicked_at')) {
                $table->dropIndex('notif_rec_campaign_clicked_idx');
                $table->dropColumn('clicked_at');
            }

            if (Schema::hasColumn('notification_recipients', 'opened_at')) {
                $table->dropIndex('notif_rec_campaign_opened_idx');
                $table->dropColumn('opened_at');
            }

            if (Schema::hasColumn('notification_recipients', 'click_count')) {
                $table->dropColumn('click_count');
            }

            if (Schema::hasColumn('notification_recipients', 'open_count')) {
                $table->dropColumn('open_count');
            }
        });

        Schema::table('notification_campaigns', function (Blueprint $table): void {
            if (Schema::hasColumn('notification_campaigns', 'notification_category')) {
                $table->dropIndex('notif_campaign_category_created_idx');
                $table->dropColumn('notification_category');
            }

            if (Schema::hasColumn('notification_campaigns', 'clicked_count')) {
                $table->dropColumn('clicked_count');
            }

            if (Schema::hasColumn('notification_campaigns', 'opened_count')) {
                $table->dropColumn('opened_count');
            }
        });

        Schema::dropIfExists('user_notification_preferences');
    }
};
