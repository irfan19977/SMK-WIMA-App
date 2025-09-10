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
        Schema::create('ekstrakurikuler_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ekstrakurikuler_id');
            $table->uuid('student_id');
            
            $table->integer('keaktifan')->nullable();
            $table->integer('keterampilan')->nullable();
            $table->integer('nilai_rapor')->nullable();
            $table->text('capaian_kompetensi');
            $table->string('academic_year'); // 2023/2024
            $table->tinyInteger('month'); // 1-12 (Januari-Desember)
            $table->string('month_name')->nullable(); // Januari, Februari, dst
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->index(['ekstrakurikuler_id', 'student_id', 'academic_year', 'month_name'], 'idx_ekskul_grades_composite');
            $table->foreign('ekstrakurikuler_id')->references('id')->on('ekstrakurikuler')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler_grades');
    }
};
