<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->unsignedInteger('payout_rate_cents_per_hour')->default(0)->after('hour_multiplier');
            $table->char('payout_currency', 3)->default('EGP')->after('payout_rate_cents_per_hour');
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropColumn(['payout_rate_cents_per_hour', 'payout_currency']);
        });
    }
};
