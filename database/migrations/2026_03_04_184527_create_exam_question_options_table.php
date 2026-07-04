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
        Schema::create('exam_question_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_question_id');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->char('option_label', 1); // A, B, C, D, etc.
            $table->timestamps();
            $table->softdeletes();

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onDelete('cascade');
            $table->index('exam_question_id');
            $table->index(['exam_question_id', 'is_correct'], 'question_correct_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_question_options');
    }
};
