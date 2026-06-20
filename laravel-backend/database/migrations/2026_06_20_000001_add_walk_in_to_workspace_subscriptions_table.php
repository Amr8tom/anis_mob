<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Allow workspace (special) subscriptions to belong to a walk-in visitor, not
 * only a registered app user. Exactly one of (user_id, walk_in_id) is set.
 */
return new class extends Migration
{
    public function up(): void
    {
        $sqlite = Schema::getConnection()->getDriverName() === 'sqlite';

        if (! Schema::hasColumn('workspace_subscriptions', 'walk_in_id')) {
            // SQLite can't drop/recreate FKs the way MySQL does; change() handles nullability there.
            if (! $sqlite) {
                Schema::table('workspace_subscriptions', function (Blueprint $table): void {
                    $table->dropForeign(['user_id']);
                });
            }

            Schema::table('workspace_subscriptions', function (Blueprint $table) use ($sqlite): void {
                $table->uuid('user_id')->nullable()->change();
                if (! $sqlite) {
                    $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
                }

                $table->foreignUuid('walk_in_id')->nullable()->after('user_id')
                    ->constrained('workspace_walk_ins')->nullOnDelete();

                // One active workspace subscription per (walk-in, workspace), mirroring
                // the existing (user, workspace) rule. NULL active_flag is ignored.
                $table->unique(['walk_in_id', 'workspace_id', 'active_flag'], 'ws_sub_walkin_active_unique');
                $table->index(['walk_in_id', 'workspace_id', 'status'], 'ws_sub_walkin_status_idx');
            });
        }

        // Ledger rows can now belong to a walk-in's subscription too.
        if (! Schema::hasColumn('workspace_subscription_ledgers', 'walk_in_id')) {
            if (! $sqlite) {
                Schema::table('workspace_subscription_ledgers', function (Blueprint $table): void {
                    $table->dropForeign(['user_id']);
                });
            }
            Schema::table('workspace_subscription_ledgers', function (Blueprint $table) use ($sqlite): void {
                $table->uuid('user_id')->nullable()->change();
                if (! $sqlite) {
                    $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
                }
                $table->foreignUuid('walk_in_id')->nullable()->after('user_id')
                    ->constrained('workspace_walk_ins')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('workspace_subscription_ledgers', 'walk_in_id')) {
            Schema::table('workspace_subscription_ledgers', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('walk_in_id');
            });
        }

        if (Schema::hasColumn('workspace_subscriptions', 'walk_in_id')) {
            Schema::table('workspace_subscriptions', function (Blueprint $table): void {
                $table->dropUnique('ws_sub_walkin_active_unique');
                $table->dropIndex('ws_sub_walkin_status_idx');
                $table->dropConstrainedForeignId('walk_in_id');
            });
        }
    }
};
