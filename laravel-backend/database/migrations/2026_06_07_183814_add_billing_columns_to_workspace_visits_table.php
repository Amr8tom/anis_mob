<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->integer('billable_minutes')->nullable()->after('duration_minutes');
            $table->integer('deducted_minutes')->nullable()->after('billable_minutes');
            $table->decimal('hour_multiplier_applied', 4, 2)->nullable()->after('deducted_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->dropColumn(['billable_minutes', 'deducted_minutes', 'hour_multiplier_applied']);
        });
    }
};
