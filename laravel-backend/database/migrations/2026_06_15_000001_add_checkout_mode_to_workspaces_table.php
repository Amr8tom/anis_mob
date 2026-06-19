<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            // DIRECT (default) preserves the existing self-checkout behaviour.
            // APPROVAL makes paid visitors request checkout for owner approval.
            // String column + PHP enum cast (not a MySQL enum) for easy future values.
            $table->string('checkout_mode', 16)->default('DIRECT')->after('day_calculation_hours');
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropColumn('checkout_mode');
        });
    }
};
