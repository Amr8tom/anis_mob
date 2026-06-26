<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_tokens', function (Blueprint $table): void {
            if (! Schema::hasColumn('device_tokens', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('platform')->index();
            }

            if (! Schema::hasColumn('device_tokens', 'failure_count')) {
                $table->unsignedInteger('failure_count')->default(0)->after('is_active');
            }

            if (! Schema::hasColumn('device_tokens', 'last_failed_at')) {
                $table->timestamp('last_failed_at')->nullable()->after('last_used_at');
            }

            $table->index(['is_active', 'id'], 'device_tokens_active_id_idx');
            $table->index(['user_id', 'is_active'], 'device_tokens_user_active_idx');
        });

        if (! Schema::hasTable('notification_campaigns')) {
            Schema::create('notification_campaigns', function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->foreignUuid('workspace_id')->nullable()->constrained()->nullOnDelete();
                $table->string('sender_type', 32);
                $table->uuid('sender_id')->nullable();
                $table->string('target_type', 64);
                $table->json('target_payload')->nullable();
                $table->string('locale', 8)->default('ar');
                $table->string('title', 120);
                $table->text('body');
                $table->string('image_url', 1024)->nullable();
                $table->string('status', 32)->default('pending');
                $table->unsignedBigInteger('targeted_count')->default(0);
                $table->unsignedBigInteger('sent_count')->default(0);
                $table->unsignedBigInteger('failed_count')->default(0);
                $table->unsignedBigInteger('skipped_count')->default(0);
                $table->timestamp('queued_at')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['workspace_id', 'created_at'], 'notif_campaign_workspace_created_idx');
                $table->index(['status', 'created_at'], 'notif_campaign_status_created_idx');
                $table->index(['sender_type', 'sender_id'], 'notif_campaign_sender_idx');
            });
        }

        if (! Schema::hasTable('notification_recipients')) {
            Schema::create('notification_recipients', function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->foreignUuid('campaign_id');
                $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignUuid('device_token_id')->nullable();
                $table->string('locale', 8)->default('ar');
                $table->string('status', 32)->default('pending');
                $table->string('provider_message_id')->nullable();
                $table->string('error_code')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                $table->foreign('campaign_id', 'notif_rec_campaign_fk')
                    ->references('id')->on('notification_campaigns')->cascadeOnDelete();
                $table->foreign('device_token_id', 'notif_rec_device_fk')
                    ->references('id')->on('device_tokens')->nullOnDelete();

                $table->unique(['campaign_id', 'device_token_id'], 'notif_rec_campaign_device_unique');
                $table->index(['campaign_id', 'status'], 'notif_rec_campaign_status_idx');
                $table->index(['user_id', 'created_at'], 'notif_rec_user_created_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_recipients');
        Schema::dropIfExists('notification_campaigns');

        Schema::table('device_tokens', function (Blueprint $table): void {
            $table->dropIndex('device_tokens_active_id_idx');
            $table->dropIndex('device_tokens_user_active_idx');

            if (Schema::hasColumn('device_tokens', 'last_failed_at')) {
                $table->dropColumn('last_failed_at');
            }

            if (Schema::hasColumn('device_tokens', 'failure_count')) {
                $table->dropColumn('failure_count');
            }

            if (Schema::hasColumn('device_tokens', 'is_active')) {
                $table->dropIndex(['is_active']);
                $table->dropColumn('is_active');
            }
        });
    }
};
