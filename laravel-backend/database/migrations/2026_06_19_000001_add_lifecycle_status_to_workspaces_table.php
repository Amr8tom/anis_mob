<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspaces', function (Blueprint $table): void {
            // Admin-control lifecycle axis. Defaults to APPROVED so every existing
            // and admin-created workspace stays exactly as it is today.
            $table->enum('lifecycle_status', ['PENDING', 'APPROVED', 'SUSPENDED', 'REJECTED'])
                ->default('APPROVED')
                ->after('status');

            $table->timestamp('approved_at')->nullable()->after('lifecycle_status');
            $table->foreignUuid('approved_by_admin_id')->nullable()->after('approved_at');
            $table->timestamp('suspended_at')->nullable()->after('approved_by_admin_id');
            $table->foreignUuid('suspended_by_admin_id')->nullable()->after('suspended_at');
            $table->string('suspension_reason')->nullable()->after('suspended_by_admin_id');
            $table->string('rejection_reason')->nullable()->after('suspension_reason');

            $table->index(['lifecycle_status', 'is_active'], 'workspaces_lifecycle_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table): void {
            $table->dropIndex('workspaces_lifecycle_active_index');
            $table->dropColumn([
                'lifecycle_status',
                'approved_at',
                'approved_by_admin_id',
                'suspended_at',
                'suspended_by_admin_id',
                'suspension_reason',
                'rejection_reason',
            ]);
        });
    }
};
