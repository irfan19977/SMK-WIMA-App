<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            // New grading schema
            $table->enum('assessment_type', ['bulanan', 'uts', 'uas'])->nullable()->after('month_name');
            $table->decimal('tugas1', 5, 2)->nullable()->after('assessment_type');
            $table->decimal('tugas2', 5, 2)->nullable()->after('tugas1');
            $table->decimal('sikap', 5, 2)->nullable()->after('tugas2');
            $table->decimal('uts', 5, 2)->nullable()->after('sikap');
            $table->decimal('uas', 5, 2)->nullable()->after('uts');

            // Month becomes nullable to support UTS/UAS rows
            $table->tinyInteger('month')->nullable()->change();
        });

        // Adjust unique constraints using raw SQL since altering composite unique is tricky cross-DB
        // Drop old unique if exists
        try {
            Schema::table('student_grades', function (Blueprint $table) {
                $table->dropUnique('unique_student_subject_monthly_grade');
            });
        } catch (Throwable $e) {
            // ignore if not exists
        }

        // Create new unique indexes for bulanan and uts/uas
        Schema::table('student_grades', function (Blueprint $table) {
            $table->unique(['student_id', 'subject_id', 'class_id', 'academic_year', 'month', 'assessment_type'], 'uniq_grade_bulanan');
            $table->unique(['student_id', 'subject_id', 'class_id', 'academic_year', 'semester', 'assessment_type'], 'uniq_grade_ujian');
        });
    }

    public function down(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            // Drop new uniques
            try { $table->dropUnique('uniq_grade_bulanan'); } catch (Throwable $e) {}
            try { $table->dropUnique('uniq_grade_ujian'); } catch (Throwable $e) {}

            // Recreate old unique
            $table->unique(['student_id', 'subject_id', 'class_id', 'academic_year', 'month'], 'unique_student_subject_monthly_grade');

            // Revert month nullability (best-effort)
            $table->tinyInteger('month')->nullable(false)->change();

            // Drop new columns
            $table->dropColumn(['assessment_type', 'tugas1', 'tugas2', 'sikap', 'uts', 'uas']);
        });
    }
};
