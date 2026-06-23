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
        // 1. Dedicated table for workspace/center owners (separate from app users).
        Schema::create('workspace_owners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('phone_number_normalized')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('status')->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('phone_number_normalized');
            $table->unique('email');
            $table->index('status');
        });

        // 2. New owner FK on workspaces (kept alongside the old owner_id).
        Schema::table('workspaces', function (Blueprint $table) {
            $table->foreignUuid('workspace_owner_id')->nullable()->after('owner_id')
                ->constrained('workspace_owners')->nullOnDelete();
        });

        // 3. New owner-reference columns wherever a workspace owner is recorded.
        if (Schema::hasTable('workspace_private_sessions') && Schema::hasColumn('workspace_private_sessions', 'created_by_owner_id')) {
            try {
                Schema::table('workspace_private_sessions', function (Blueprint $table) {
                    $table->foreignUuid('created_by_owner_id')->nullable()->change();
                });
            } catch (Throwable) {
                // Some SQLite/test setups cannot alter FK columns in-place; the
                // new workspace-owner column below is the source of truth.
            }

            Schema::table('workspace_private_sessions', function (Blueprint $table) {
                $table->foreignUuid('created_by_workspace_owner_id')->nullable()->after('created_by_owner_id');
                $table->foreign('created_by_workspace_owner_id', 'wps_created_by_ws_owner_fk')
                    ->references('id')->on('workspace_owners')->nullOnDelete();
            });
        }
        if (Schema::hasTable('workspace_private_session_attendees') && Schema::hasColumn('workspace_private_session_attendees', 'checked_in_by_owner_id')) {
            try {
                Schema::table('workspace_private_session_attendees', function (Blueprint $table) {
                    $table->foreignUuid('checked_in_by_owner_id')->nullable()->change();
                });
            } catch (Throwable) {
                // See note above; keep old compatibility column best-effort only.
            }

            Schema::table('workspace_private_session_attendees', function (Blueprint $table) {
                $table->foreignUuid('checked_in_by_workspace_owner_id')->nullable()->after('checked_in_by_owner_id');
                $table->foreign('checked_in_by_workspace_owner_id', 'wps_att_checkin_ws_owner_fk')
                    ->references('id')->on('workspace_owners')->nullOnDelete();
            });
        }
        if (Schema::hasTable('workspace_private_session_import_batches') && Schema::hasColumn('workspace_private_session_import_batches', 'created_by_owner_id')) {
            try {
                Schema::table('workspace_private_session_import_batches', function (Blueprint $table) {
                    $table->foreignUuid('created_by_owner_id')->nullable()->change();
                });
            } catch (Throwable) {
                // Compatibility-only legacy column.
            }

            Schema::table('workspace_private_session_import_batches', function (Blueprint $table) {
                $table->foreignUuid('created_by_workspace_owner_id')->nullable()->after('created_by_owner_id');
                $table->foreign('created_by_workspace_owner_id', 'wps_import_created_ws_owner_fk')
                    ->references('id')->on('workspace_owners')->nullOnDelete();
            });
        }

        // 4. Backfill: copy every WORKSPACE_OWNER user into workspace_owners and
        //    remap every owner reference. Old columns are left untouched.
        $map = [];   // old users.id => new workspace_owners.id
        $ownerUsers = DB::table('users')->where('role', 'WORKSPACE_OWNER')->get();

        foreach ($ownerUsers as $user) {
            $newId = (string) Str::uuid();
            $map[$user->id] = $newId;

            DB::table('workspace_owners')->insert([
                'id' => $newId,
                'full_name' => $user->full_name,
                'phone_number' => $user->phone_number,
                'phone_number_normalized' => $user->phone_number_normalized ?? null,
                'email' => $user->email,
                'password' => $user->password,
                'status' => 'active',
                'last_login_at' => null,
                'remember_token' => $user->remember_token ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => null,
            ]);
        }

        foreach ($map as $oldUserId => $newOwnerId) {
            DB::table('workspaces')->where('owner_id', $oldUserId)->update(['workspace_owner_id' => $newOwnerId]);

            if (Schema::hasColumn('workspace_private_sessions', 'created_by_workspace_owner_id')) {
                DB::table('workspace_private_sessions')->where('created_by_owner_id', $oldUserId)
                    ->update(['created_by_workspace_owner_id' => $newOwnerId]);
            }
            if (Schema::hasColumn('workspace_private_session_attendees', 'checked_in_by_workspace_owner_id')) {
                DB::table('workspace_private_session_attendees')->where('checked_in_by_owner_id', $oldUserId)
                    ->update(['checked_in_by_workspace_owner_id' => $newOwnerId]);
            }
            if (Schema::hasColumn('workspace_private_session_import_batches', 'created_by_workspace_owner_id')) {
                DB::table('workspace_private_session_import_batches')->where('created_by_owner_id', $oldUserId)
                    ->update(['created_by_workspace_owner_id' => $newOwnerId]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('workspace_private_session_import_batches', 'created_by_workspace_owner_id')) {
            Schema::table('workspace_private_session_import_batches', function (Blueprint $table) {
                $table->dropForeign('wps_import_created_ws_owner_fk');
                $table->dropColumn('created_by_workspace_owner_id');
            });
        }
        if (Schema::hasColumn('workspace_private_session_attendees', 'checked_in_by_workspace_owner_id')) {
            Schema::table('workspace_private_session_attendees', function (Blueprint $table) {
                $table->dropForeign('wps_att_checkin_ws_owner_fk');
                $table->dropColumn('checked_in_by_workspace_owner_id');
            });
        }
        if (Schema::hasColumn('workspace_private_sessions', 'created_by_workspace_owner_id')) {
            Schema::table('workspace_private_sessions', function (Blueprint $table) {
                $table->dropForeign('wps_created_by_ws_owner_fk');
                $table->dropColumn('created_by_workspace_owner_id');
            });
        }
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropConstrainedForeignId('workspace_owner_id');
        });
        Schema::dropIfExists('workspace_owners');
    }
};
