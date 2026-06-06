<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');                                  // "Monthly", "20 Hours"
            $table->enum('tier', ['FREE', 'SILVER', 'GOLD'])->default('FREE'); // app subscriptionType
            $table->text('description')->nullable();
            $table->integer('price_cents');
            $table->string('currency', 3)->default('EGP');
            $table->integer('included_minutes')->nullable();         // time-balance plans
            $table->integer('duration_days')->nullable();            // date-bound plans
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
