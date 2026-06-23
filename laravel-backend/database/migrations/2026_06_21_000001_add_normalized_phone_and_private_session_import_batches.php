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
        if (! Schema::hasColumn('users', 'phone_number_normalized')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('phone_number_normalized', 30)->nullable()->after('phone_number');
                $table->index('phone_number_normalized', 'users_phone_number_normalized_idx');
            });

            DB::table('users')
                ->select(['id', 'phone_number'])
                ->orderBy('id')
                ->chunkById(500, function ($users): void {
                    foreach ($users as $user) {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'phone_number_normalized' => preg_replace('/\D+/', '', (string) $user->phone_number) ?: null,
                            ]);
                    }
                }, 'id');
        }

        if (Schema::hasTable('workspace_walk_ins') && ! Schema::hasColumn('workspace_walk_ins', 'phone_number_normalized')) {
            Schema::table('workspace_walk_ins', function (Blueprint $table): void {
                $table->string('phone_number_normalized', 30)->nullable()->after('phone_number');
                $table->index(['workspace_id', 'phone_number_normalized'], 'walk_ins_workspace_phone_normalized_idx');
            });

            DB::table('workspace_walk_ins')
                ->select(['id', 'phone_number'])
                ->orderBy('id')
                ->chunkById(500, function ($walkIns): void {
                    foreach ($walkIns as $walkIn) {
                        DB::table('workspace_walk_ins')
                            ->where('id', $walkIn->id)
                            ->update([
                                'phone_number_normalized' => preg_replace('/\D+/', '', (string) $walkIn->phone_number) ?: null,
                            ]);
                    }
                }, 'id');
        }

        if (! Schema::hasTable('workspace_private_session_import_batches')) {
            Schema::create('workspace_private_session_import_batches', function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->uuid('workspace_private_session_id');
                $table->uuid('workspace_id');
                $table->uuid('created_by_owner_id')->nullable();
                $table->string('original_filename')->nullable();
                $table->string('status', 24)->default('preview');
                $table->unsignedInteger('valid_count')->default(0);
                $table->unsignedInteger('duplicate_count')->default(0);
                $table->unsignedInteger('failed_count')->default(0);
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamps();

                $table->index(['workspace_private_session_id', 'status', 'created_at'], 'wps_import_batches_session_status_idx');
                $table->index(['workspace_id', 'created_at'], 'wps_import_batches_workspace_idx');
            });
        }
        $this->addForeignIfMissing('workspace_private_session_import_batches', 'wps_import_batches_session_fk', 'workspace_private_session_id', 'workspace_private_sessions', 'cascade');
        $this->addForeignIfMissing('workspace_private_session_import_batches', 'wps_import_batches_workspace_fk', 'workspace_id', 'workspaces', 'cascade');
        $this->addForeignIfMissing('workspace_private_session_import_batches', 'wps_import_batches_owner_fk', 'created_by_owner_id', 'users', 'restrict');

        if (! Schema::hasTable('workspace_private_session_import_rows')) {
            Schema::create('workspace_private_session_import_rows', function (Blueprint $table): void {
                $table->uuid('id')->primary();
                $table->uuid('workspace_private_session_import_batch_id');
                $table->uuid('workspace_private_session_id');
                $table->uuid('workspace_id');
                $table->unsignedInteger('row_number');
                $table->string('phone_snapshot', 30)->nullable();
                $table->string('phone_normalized', 30)->nullable();
                $table->string('name_snapshot')->nullable();
                $table->string('resolved_name')->nullable();
                $table->string('visitor_type', 40)->nullable();
                $table->string('status', 24);
                $table->text('message')->nullable();
                $table->timestamps();

                $table->unique(['workspace_private_session_import_batch_id', 'row_number'], 'wps_import_rows_batch_row_unique');
                $table->index(['workspace_private_session_import_batch_id', 'status'], 'wps_import_rows_batch_status_idx');
                $table->index(['workspace_private_session_id', 'phone_normalized'], 'wps_import_rows_session_phone_idx');
            });
        }
        $this->addForeignIfMissing('workspace_private_session_import_rows', 'wps_import_rows_batch_fk', 'workspace_private_session_import_batch_id', 'workspace_private_session_import_batches', 'cascade');
        $this->addForeignIfMissing('workspace_private_session_import_rows', 'wps_import_rows_session_fk', 'workspace_private_session_id', 'workspace_private_sessions', 'cascade');
        $this->addForeignIfMissing('workspace_private_session_import_rows', 'wps_import_rows_workspace_fk', 'workspace_id', 'workspaces', 'cascade');

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            $this->addFullTextIndexIfMissing('workspace_clients', 'workspace_clients_name_fulltext', 'full_name_snapshot');
            $this->addFullTextIndexIfMissing('room_reservations', 'room_reservations_client_name_fulltext', 'client_name');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_private_session_import_rows');
        Schema::dropIfExists('workspace_private_session_import_batches');

        if (Schema::hasColumn('workspace_walk_ins', 'phone_number_normalized')) {
            Schema::table('workspace_walk_ins', function (Blueprint $table): void {
                $table->dropIndex('walk_ins_workspace_phone_normalized_idx');
                $table->dropColumn('phone_number_normalized');
            });
        }

        if (Schema::hasColumn('users', 'phone_number_normalized')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropIndex('users_phone_number_normalized_idx');
                $table->dropColumn('phone_number_normalized');
            });
        }
    }

    private function addFullTextIndexIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('database()'))
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();

        if (! $exists) {
            DB::statement("ALTER TABLE {$table} ADD FULLTEXT {$indexName} ({$column})");
        }
    }

    private function addForeignIfMissing(string $table, string $constraint, string $column, string $references, string $onDelete): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            $exists = DB::table('information_schema.table_constraints')
                ->where('table_schema', DB::raw('database()'))
                ->where('table_name', $table)
                ->where('constraint_name', $constraint)
                ->exists();

            if ($exists) {
                return;
            }
        }

        Schema::table($table, function (Blueprint $table) use ($constraint, $column, $references, $onDelete): void {
            $foreign = $table->foreign($column, $constraint)->references('id')->on($references);
            if ($onDelete === 'cascade') {
                $foreign->cascadeOnDelete();
            } elseif ($onDelete === 'restrict') {
                $foreign->restrictOnDelete();
            }
        });
    }
};
