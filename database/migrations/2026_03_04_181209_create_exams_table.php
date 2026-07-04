<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->uuid('subject_id');
            $table->uuid('class_id');
            $table->uuid('teacher_id');
            $table->integer('duration_minutes');
            $table->boolean('show_results')->default(true);
            $table->boolean('show_status')->default(true);
            $table->boolean('show_review')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->integer('total_questions')->default(0);
            $table->decimal('passing_score', 5, 2)->default(70.00);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softdeletes();

            $table->foreign('subject_id')->references('id')->on('subject')->onDelete('cascade')->name('exams_subject_id_foreign');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade')->name('exams_class_id_foreign');
            $table->foreign('teacher_id')->references('id')->on('teacher')->onDelete('cascade')->name('exams_teacher_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
