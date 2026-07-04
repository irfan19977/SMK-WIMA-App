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
        Schema::create('exam_submission_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_submission_id');
            $table->uuid('exam_question_id');
            $table->text('answer')->nullable();
            $table->uuid('selected_option_id')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('points_earned', 5, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('exam_submission_id')->references('id')->on('exam_submissions')->onDelete('cascade');
            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onDelete('cascade');
            $table->foreign('selected_option_id')->references('id')->on('exam_question_options')->onDelete('set null');
            
            $table->index('exam_submission_id');
            $table->index('exam_question_id');
            $table->index('selected_option_id');
            $table->index(['exam_submission_id', 'exam_question_id'], 'submission_question_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_submission_answers');
    }
};
