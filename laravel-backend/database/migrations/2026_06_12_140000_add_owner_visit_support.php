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
        Schema::table('users', function (Blueprint $table) {
            // Walk-in visitor registered by a workspace owner; has phone + name and
            // an unusable random password until they claim the account by registering.
            $table->boolean('is_walk_in')->default(false)->after('is_guest');
        });

        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->string('plan_tier_snapshot', 20)->default('FREE')->after('subscription_id');
            // How the visit was created: scanned QR (app user) or registered by the owner.
            $table->string('source', 20)->default('QR')->after('status');
            // The workspace owner who manually registered/checked the visitor (if any).
            $table->foreignUuid('registered_by')->nullable()->after('source')->constrained('users')->restrictOnDelete();

            $table->index(['workspace_id', 'status', 'check_out_at', 'plan_tier_snapshot'], 'visits_workspace_report_index');
            $table->index(['status', 'check_in_at'], 'visits_status_checkin_index');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('
                UPDATE workspace_visits wv
                JOIN subscriptions s ON s.id = wv.subscription_id
                JOIN plans p ON p.id = s.plan_id
                SET wv.plan_tier_snapshot = p.tier
            ');
        } else {
            DB::statement("
                UPDATE workspace_visits
                SET plan_tier_snapshot = COALESCE((
                    SELECT plans.tier
                    FROM subscriptions
                    JOIN plans ON plans.id = subscriptions.plan_id
                    WHERE subscriptions.id = workspace_visits.subscription_id
                ), 'FREE')
            ");
        }
    }

    public function down(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->dropIndex('visits_workspace_report_index');
            $table->dropIndex('visits_status_checkin_index');
            $table->dropConstrainedForeignId('registered_by');
            $table->dropColumn(['plan_tier_snapshot', 'source']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_walk_in');
        });
    }
};
