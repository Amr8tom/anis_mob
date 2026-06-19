<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('subscriptions')
            ->where('status', '!=', 'ACTIVE')
            ->whereNotNull('active_flag')
            ->update(['active_flag' => null]);

        // Clear first so repairing legacy duplicate ACTIVE rows cannot violate
        // the unique (user_id, active_flag) constraint mid-migration.
        DB::table('subscriptions')
            ->where('status', 'ACTIVE')
            ->update(['active_flag' => null]);

        DB::table('subscriptions')
            ->where('status', 'ACTIVE')
            ->orderBy('user_id')
            ->orderByDesc('started_at')
            ->orderByDesc('id')
            ->get(['id', 'user_id'])
            ->groupBy('user_id')
            ->each(function ($subscriptions): void {
                $kept = $subscriptions->first();

                DB::table('subscriptions')
                    ->where('id', $kept->id)
                    ->update(['active_flag' => 1]);

                $duplicateIds = $subscriptions->skip(1)->pluck('id');
                if ($duplicateIds->isNotEmpty()) {
                    DB::table('subscriptions')
                        ->whereIn('id', $duplicateIds)
                        ->update(['status' => 'EXPIRED', 'active_flag' => null]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
