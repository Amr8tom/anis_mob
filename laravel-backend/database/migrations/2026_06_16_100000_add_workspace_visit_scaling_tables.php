<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table): void {
            $table->timestamp('workspace_client_counted_at')->nullable()->after('checkout_request_note');

            $table->index(['workspace_id', 'status', 'check_out_at', 'billing_source'], 'visits_ws_status_out_source_idx');
            $table->index(['workspace_id', 'status', 'check_in_at'], 'visits_ws_status_in_idx');
            $table->index(['workspace_id', 'user_id', 'check_in_at'], 'visits_ws_user_in_idx');
            $table->index(['workspace_id', 'walk_in_id', 'check_in_at'], 'visits_ws_walkin_in_idx');
            $table->index(['workspace_id', 'status', 'checkout_requested_at', 'check_in_at'], 'visits_ws_checkout_queue_idx');
            $table->index(['workspace_id', 'workspace_client_counted_at'], 'visits_ws_client_counted_idx');
        });

        Schema::table('workspace_subscriptions', function (Blueprint $table): void {
            $table->index(['workspace_id', 'created_at'], 'ws_subs_workspace_created_idx');
            $table->index(['workspace_id', 'status', 'created_at'], 'ws_subs_workspace_status_created_idx');
            $table->index(['status', 'expires_at', 'id'], 'ws_subs_expiry_idx');
        });

        Schema::table('workspace_subscription_codes', function (Blueprint $table): void {
            $table->index(['workspace_id', 'status', 'created_at'], 'ws_codes_workspace_status_created_idx');
            $table->index(['status', 'expires_at'], 'ws_codes_status_expires_idx');
        });

        Schema::table('workspace_daily_visit_stats', function (Blueprint $table): void {
            $table->unsignedBigInteger('unique_visitors_count')->default(0)->after('visitors_count');
            $table->unsignedBigInteger('registered_visitors_count')->default(0)->after('unique_visitors_count');
            $table->unsignedBigInteger('walk_in_visitors_count')->default(0)->after('registered_visitors_count');
        });

        Schema::create('workspace_clients', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('walk_in_id')->nullable()->constrained('workspace_walk_ins')->nullOnDelete();
            $table->string('client_type', 20);
            $table->string('full_name_snapshot', 120)->nullable();
            $table->string('phone_number_snapshot', 30)->nullable();
            $table->string('phone_number_normalized', 30)->nullable();
            $table->timestamp('first_visit_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->unsignedBigInteger('total_visits')->default(0);
            $table->unsignedBigInteger('total_minutes')->default(0);
            $table->unsignedBigInteger('free_visits')->default(0);
            $table->unsignedBigInteger('global_subscription_visits')->default(0);
            $table->unsignedBigInteger('workspace_subscription_visits')->default(0);
            $table->string('last_billing_source', 30)->nullable();
            $table->timestamps();

            $table->unique(['workspace_id', 'user_id'], 'workspace_clients_user_unique');
            $table->unique(['workspace_id', 'walk_in_id'], 'workspace_clients_walkin_unique');
            $table->index(['workspace_id', 'last_visit_at'], 'workspace_clients_last_visit_idx');
            $table->index(['workspace_id', 'phone_number_normalized'], 'workspace_clients_phone_idx');
            $table->index(['workspace_id', 'full_name_snapshot'], 'workspace_clients_name_idx');
            $table->index(['workspace_id', 'client_type'], 'workspace_clients_type_idx');
            $table->index(['workspace_id', 'last_billing_source'], 'workspace_clients_source_idx');
        });

        Schema::create('workspace_visit_archives', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('walk_in_id')->nullable()->constrained('workspace_walk_ins')->nullOnDelete();
            $table->foreignUuid('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->foreignUuid('workspace_subscription_id')->nullable()->constrained('workspace_subscriptions')->nullOnDelete();
            $table->string('billing_source', 30)->nullable();
            $table->string('status', 30);
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('billable_minutes')->nullable();
            $table->unsignedInteger('deducted_minutes')->nullable();
            $table->decimal('hour_multiplier_applied', 8, 2)->nullable();
            $table->timestamp('archived_at');
            $table->timestamp('original_created_at')->nullable();
            $table->timestamp('original_updated_at')->nullable();

            $table->index(['workspace_id', 'check_out_at'], 'visit_archives_ws_out_idx');
            $table->index(['user_id', 'check_out_at'], 'visit_archives_user_out_idx');
            $table->index(['walk_in_id', 'check_out_at'], 'visit_archives_walkin_out_idx');
            $table->index(['billing_source', 'check_out_at'], 'visit_archives_source_out_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_visit_archives');
        Schema::dropIfExists('workspace_clients');

        Schema::table('workspace_daily_visit_stats', function (Blueprint $table): void {
            $table->dropColumn([
                'unique_visitors_count',
                'registered_visitors_count',
                'walk_in_visitors_count',
            ]);
        });

        Schema::table('workspace_subscription_codes', function (Blueprint $table): void {
            $table->dropIndex('ws_codes_workspace_status_created_idx');
            $table->dropIndex('ws_codes_status_expires_idx');
        });

        Schema::table('workspace_subscriptions', function (Blueprint $table): void {
            $table->dropIndex('ws_subs_workspace_created_idx');
            $table->dropIndex('ws_subs_workspace_status_created_idx');
            $table->dropIndex('ws_subs_expiry_idx');
        });

        Schema::table('workspace_visits', function (Blueprint $table): void {
            $table->dropIndex('visits_ws_status_out_source_idx');
            $table->dropIndex('visits_ws_status_in_idx');
            $table->dropIndex('visits_ws_user_in_idx');
            $table->dropIndex('visits_ws_walkin_in_idx');
            $table->dropIndex('visits_ws_checkout_queue_idx');
            $table->dropIndex('visits_ws_client_counted_idx');
            $table->dropColumn('workspace_client_counted_at');
        });
    }
};
