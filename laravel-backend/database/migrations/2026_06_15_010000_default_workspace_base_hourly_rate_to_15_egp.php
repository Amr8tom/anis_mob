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
        if (! Schema::hasColumn('workspaces', 'payout_rate_cents_per_hour')) {
            Schema::table('workspaces', function (Blueprint $table): void {
                $table->unsignedInteger('payout_rate_cents_per_hour')->default(1500)->after('hour_multiplier');
                $table->char('payout_currency', 3)->default('EGP')->after('payout_rate_cents_per_hour');
            });

            return;
        }

        DB::table('workspaces')
            ->where('payout_rate_cents_per_hour', 0)
            ->update(['payout_rate_cents_per_hour' => 1500]);

        Schema::table('workspaces', function (Blueprint $table): void {
            $table->unsignedInteger('payout_rate_cents_per_hour')->default(1500)->change();
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table): void {
            $table->dropColumn(['payout_rate_cents_per_hour', 'payout_currency']);
        });
    }
};
