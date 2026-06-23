<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspace_center_teachers', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name', 160);
            $table->string('phone_number', 30)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['workspace_id', 'is_active', 'name'], 'center_teachers_workspace_active_name_idx');
        });

        Schema::create('workspace_center_subjects', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['workspace_id', 'name'], 'center_subjects_workspace_name_unique');
            $table->index(['workspace_id', 'is_active', 'name'], 'center_subjects_workspace_active_name_idx');
        });

        Schema::create('workspace_center_grade_levels', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('name', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['workspace_id', 'name'], 'center_grades_workspace_name_unique');
            $table->index(['workspace_id', 'is_active', 'name'], 'center_grades_workspace_active_name_idx');
        });

        Schema::table('workspace_private_sessions', function (Blueprint $table): void {
            $table->foreignUuid('center_teacher_id')->nullable()->after('host_name')
                ->constrained('workspace_center_teachers')->nullOnDelete();
            $table->foreignUuid('center_subject_id')->nullable()->after('center_teacher_id')
                ->constrained('workspace_center_subjects')->nullOnDelete();
            $table->foreignUuid('center_grade_level_id')->nullable()->after('center_subject_id')
                ->constrained('workspace_center_grade_levels')->nullOnDelete();
            $table->string('instructor_payout_type', 32)->default('none')->after('price_cents');
            $table->unsignedInteger('instructor_payout_value')->default(0)->after('instructor_payout_type');

            $table->index(['workspace_id', 'center_teacher_id', 'starts_at'], 'private_sessions_teacher_filter_idx');
            $table->index(['workspace_id', 'center_subject_id', 'starts_at'], 'private_sessions_subject_filter_idx');
            $table->index(['workspace_id', 'center_grade_level_id', 'starts_at'], 'private_sessions_grade_filter_idx');
        });
    }

    public function down(): void
    {
        Schema::table('workspace_private_sessions', function (Blueprint $table): void {
            $table->dropIndex('private_sessions_teacher_filter_idx');
            $table->dropIndex('private_sessions_subject_filter_idx');
            $table->dropIndex('private_sessions_grade_filter_idx');
            $table->dropConstrainedForeignId('center_teacher_id');
            $table->dropConstrainedForeignId('center_subject_id');
            $table->dropConstrainedForeignId('center_grade_level_id');
            $table->dropColumn(['instructor_payout_type', 'instructor_payout_value']);
        });

        Schema::dropIfExists('workspace_center_grade_levels');
        Schema::dropIfExists('workspace_center_subjects');
        Schema::dropIfExists('workspace_center_teachers');
    }
};
