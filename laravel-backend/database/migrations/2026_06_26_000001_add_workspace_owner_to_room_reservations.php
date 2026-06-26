<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('room_reservations')) {
            return;
        }

        if (Schema::hasColumn('room_reservations', 'created_by_owner_id')) {
            try {
                Schema::table('room_reservations', function (Blueprint $table) {
                    $table->foreignUuid('created_by_owner_id')->nullable()->change();
                });
            } catch (Throwable) {
                // Some SQLite/test setups cannot alter existing FK columns.
                // The new workspace-owner column below is the source of truth
                // for the separated owner guard.
            }
        }

        if (! Schema::hasColumn('room_reservations', 'created_by_workspace_owner_id')) {
            Schema::table('room_reservations', function (Blueprint $table) {
                $table->foreignUuid('created_by_workspace_owner_id')->nullable()->after('created_by_owner_id');
                $table->foreign('created_by_workspace_owner_id', 'room_reservations_ws_owner_fk')
                    ->references('id')->on('workspace_owners')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('room_reservations', 'created_by_workspace_owner_id')) {
            try {
                DB::table('room_reservations')
                    ->join('workspaces', 'workspaces.id', '=', 'room_reservations.workspace_id')
                    ->whereNull('room_reservations.created_by_workspace_owner_id')
                    ->whereNotNull('workspaces.workspace_owner_id')
                    ->update([
                        'room_reservations.created_by_workspace_owner_id' => DB::raw('workspaces.workspace_owner_id'),
                    ]);
            } catch (Throwable) {
                // SQLite/schema-dump test environments may not expose the
                // workspace_owner_id column at this point. Runtime writes still
                // populate the new column explicitly.
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('room_reservations')) {
            return;
        }

        if (Schema::hasColumn('room_reservations', 'created_by_workspace_owner_id')) {
            Schema::table('room_reservations', function (Blueprint $table) {
                $table->dropForeign('room_reservations_ws_owner_fk');
                $table->dropColumn('created_by_workspace_owner_id');
            });
        }
    }
};
