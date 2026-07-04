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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'multiple_choice_complex', 'essay', 'true_false'])->default('multiple_choice');
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->text('explanation')->nullable();
            $table->string('media_type')->nullable();
            $table->string('media_path')->nullable();
            $table->text('media_caption')->nullable();
            $table->timestamps();
            $table->softdeletes();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->index('exam_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
