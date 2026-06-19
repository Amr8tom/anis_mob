<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            // A "pending checkout request" is a still-CHECKED_IN visit whose
            // checkout_requested_at is set. No new status value is introduced,
            // so existing billing / auto-checkout logic is untouched.
            $table->timestamp('checkout_requested_at')->nullable()->after('check_out_at');
            $table->string('checkout_request_note')->nullable()->after('checkout_requested_at');

            // Makes "pending requests first" cheap for the owner dashboard.
            $table->index(['workspace_id', 'status', 'checkout_requested_at'], 'visits_ws_status_reqat_idx');
        });
    }

    public function down(): void
    {
        Schema::table('workspace_visits', function (Blueprint $table) {
            $table->dropIndex('visits_ws_status_reqat_idx');
            $table->dropColumn(['checkout_requested_at', 'checkout_request_note']);
        });
    }
};
