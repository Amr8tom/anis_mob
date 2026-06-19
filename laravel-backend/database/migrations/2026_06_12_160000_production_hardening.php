<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->softDeletes();
            $table->json('admin_permissions')->nullable();
            $table->text('admin_mfa_secret')->nullable();
            $table->boolean('admin_mfa_enabled')->default(false);
            $table->unsignedSmallInteger('admin_failed_login_attempts')->default(0);
            $table->timestamp('admin_locked_until')->nullable();
        });

        Schema::table('workspaces', function (Blueprint $table): void {
            $table->softDeletes();
            $table->unsignedInteger('active_visit_count')->default(0);
            $table->unique('owner_id', 'workspaces_owner_unique');
        });

        Schema::create('workspace_walk_ins', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->string('full_name', 120);
            $table->string('phone_number', 30);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['workspace_id', 'phone_number']);
        });

        Schema::table('workspace_visits', function (Blueprint $table): void {
            $table->foreignUuid('walk_in_id')->nullable()->after('user_id')->constrained('workspace_walk_ins')->restrictOnDelete();
            $table->unique(['walk_in_id', 'active_flag'], 'visits_walk_in_active_unique');
        });

        $walkIns = DB::table('users')->where('is_walk_in', true)->get(['id', 'full_name', 'phone_number', 'created_at', 'updated_at']);
        foreach ($walkIns as $walkIn) {
            $workspaceIds = DB::table('workspace_visits')
                ->where('user_id', $walkIn->id)
                ->distinct()
                ->pluck('workspace_id');

            foreach ($workspaceIds as $workspaceId) {
                $walkInId = (string) Str::uuid7();
                DB::table('workspace_walk_ins')->insert([
                    'id' => $walkInId,
                    'workspace_id' => $workspaceId,
                    'full_name' => $walkIn->full_name,
                    'phone_number' => $walkIn->phone_number,
                    'created_at' => $walkIn->created_at,
                    'updated_at' => $walkIn->updated_at,
                ]);
                DB::table('workspace_visits')
                    ->where('user_id', $walkIn->id)
                    ->where('workspace_id', $workspaceId)
                    ->update(['walk_in_id' => $walkInId]);
            }
        }

        Schema::table('workspace_visits', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
            $table->uuid('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->dropForeign(['workspace_id']);
            $table->foreign('workspace_id')->references('id')->on('workspaces')->restrictOnDelete();
        });

        DB::table('workspace_visits')->whereNotNull('walk_in_id')->update(['user_id' => null]);
        DB::table('users')->where('is_walk_in', true)->delete();

        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });

        Schema::create('workspace_ownership_invitations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->string('phone_number', 30);
            $table->string('token_hash', 64)->unique();
            $table->foreignUuid('invited_by_admin_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->index(['workspace_id', 'expires_at']);
        });

        Schema::create('workspace_ownership_changes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->restrictOnDelete();
            $table->foreignUuid('previous_owner_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignUuid('new_owner_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignUuid('changed_by_admin_id')->constrained('users')->restrictOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('idempotency_keys', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('actor_key', 100);
            $table->string('operation', 150);
            $table->string('idempotency_key', 100);
            $table->string('request_hash', 64);
            $table->string('status', 20)->default('PROCESSING');
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->longText('response_body')->nullable();
            $table->timestamps();
            $table->unique(['actor_key', 'operation', 'idempotency_key'], 'idempotency_actor_operation_key');
            $table->index('created_at');
        });

        Schema::create('accounting_corrections', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_visit_id')->nullable()->constrained('workspace_visits')->restrictOnDelete();
            $table->foreignUuid('subscription_id')->nullable()->constrained('subscriptions')->restrictOnDelete();
            $table->foreignUuid('created_by_admin_id')->constrained('users')->restrictOnDelete();
            $table->integer('minutes_delta')->default(0);
            $table->string('reason');
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('workspace_settlement_reversals', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_settlement_id')->unique()->constrained('workspace_settlements')->restrictOnDelete();
            $table->foreignUuid('created_by_admin_id')->constrained('users')->restrictOnDelete();
            $table->string('reason');
            $table->timestamp('created_at')->useCurrent();
        });

        DB::table('workspaces')->update([
            'active_visit_count' => DB::raw("(SELECT COUNT(*) FROM workspace_visits WHERE workspace_visits.workspace_id = workspaces.id AND workspace_visits.status = 'CHECKED_IN')"),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_settlement_reversals');
        Schema::dropIfExists('accounting_corrections');
        Schema::dropIfExists('idempotency_keys');
        Schema::dropIfExists('workspace_ownership_changes');
        Schema::dropIfExists('workspace_ownership_invitations');

        Schema::table('workspace_visits', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('walk_in_id');
        });
        Schema::dropIfExists('workspace_walk_ins');

        Schema::table('workspaces', function (Blueprint $table): void {
            $table->dropColumn(['deleted_at', 'active_visit_count']);
        });
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'deleted_at',
                'admin_permissions',
                'admin_mfa_secret',
                'admin_mfa_enabled',
                'admin_failed_login_attempts',
                'admin_locked_until',
            ]);
        });
    }
};
