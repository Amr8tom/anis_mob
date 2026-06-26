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
            if (! Schema::hasColumn('workspace_visits', 'registered_by_workspace_owner_id')) {
                $table->foreignUuid('registered_by_workspace_owner_id')
                    ->nullable()
                    ->after('registered_by')
                    ->constrained('workspace_owners')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table): void {
            if (Schema::hasColumn('workspace_visits', 'registered_by_workspace_owner_id')) {
                $table->dropConstrainedForeignId('registered_by_workspace_owner_id');
            }
        });
    }
};
