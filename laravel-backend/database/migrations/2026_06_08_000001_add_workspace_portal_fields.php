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
        // Modify the user role enum in users table (only if MySQL)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('USER', 'ADMIN', 'WORKSPACE_OWNER') NOT NULL DEFAULT 'USER'");
        }

        Schema::table('workspaces', function (Blueprint $table) {
            $table->foreignUuid('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('admin_phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['owner_id', 'admin_phone']);
        });

        // Revert user role enum (Note: make sure to clean up WORKSPACE_OWNER users before rollback)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('USER', 'ADMIN') NOT NULL DEFAULT 'USER'");
        }
    }
};
