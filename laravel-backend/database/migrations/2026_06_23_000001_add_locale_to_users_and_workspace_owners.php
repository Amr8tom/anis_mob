<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 8)->nullable()->after('email');
        });

        if (Schema::hasTable('workspace_owners')) {
            Schema::table('workspace_owners', function (Blueprint $table) {
                $table->string('locale', 8)->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locale');
        });

        if (Schema::hasTable('workspace_owners')) {
            Schema::table('workspace_owners', function (Blueprint $table) {
                $table->dropColumn('locale');
            });
        }
    }
};
