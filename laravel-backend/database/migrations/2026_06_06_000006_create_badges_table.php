<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Badge catalog (app: ProfileBadge { id, label, iconKey }).
        Schema::create('badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();                         // streak|hours|sessions|top
            $table->string('label');
            $table->string('icon_key');
            $table->timestamps();
        });

        // Which badges a user has earned.
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();   // auto-increment PK: pivot rows are inserted via attach() (no model event)
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('badge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('earned_at')->useCurrent();

            $table->unique(['user_id', 'badge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
