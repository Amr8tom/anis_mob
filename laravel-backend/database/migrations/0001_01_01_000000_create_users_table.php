<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ---- Identity / auth (no OTP: phone + password) ----
            $table->string('full_name');
            $table->string('phone_number')->unique();       // login identifier
            $table->string('email')->nullable()->unique();  // optional alternate login identifier
            $table->string('whatsapp_number');              // shown for out-of-app contact
            $table->string('password');                     // hashed
            $table->enum('role', ['USER', 'ADMIN', 'WORKSPACE_OWNER'])->default('USER');
            $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
            $table->boolean('is_guest')->default(false);

            // ---- Profile / display ----
            $table->string('avatar_url')->nullable();
            $table->string('initials')->nullable();
            $table->string('university')->nullable();
            $table->string('study_field')->nullable();      // buddy: field of study
            $table->json('interests')->nullable();          // buddy: list<string>
            $table->string('avatar_color_key')->default('blue');
            $table->enum('availability', ['ONLINE', 'BUSY', 'OFFLINE'])->default('OFFLINE');
            $table->decimal('rating', 3, 2)->default(0);    // buddy rating 0.00..5.00

            // ---- Wallet + gamification (profile & home) ----
            $table->decimal('wallet_balance', 10, 2)->default(0);
            $table->integer('total_study_hours')->default(0);
            $table->integer('streak_days')->default(0);
            $table->integer('total_sessions')->default(0);

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Framework session-driver table (unrelated to study sessions).
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
