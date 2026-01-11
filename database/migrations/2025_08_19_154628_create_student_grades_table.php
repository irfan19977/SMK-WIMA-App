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
        Schema::create('student_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('class_id');
            $table->uuid('subject_id');
            $table->string('academic_year'); // 2023/2024
            $table->string('semester'); // 1, 2, ganjil, genap
            
            // Komponen Nilai - Tugas
            $table->decimal('tugas1', 5, 2)->nullable();
            $table->decimal('tugas2', 5, 2)->nullable();
            $table->decimal('tugas3', 5, 2)->nullable();
            $table->decimal('tugas4', 5, 2)->nullable();
            $table->decimal('tugas5', 5, 2)->nullable();
            $table->decimal('tugas6', 5, 2)->nullable();
            // Komponen Nilai - Lainnya
            $table->decimal('sikap', 5, 2)->nullable();
            $table->decimal('uts', 5, 2)->nullable();
            $table->decimal('uas', 5, 2)->nullable();
            $table->decimal('nilai', 5, 2)->nullable(); // Nilai akhir
            
            // Audit fields
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subject')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes untuk performa query
            $table->index(['student_id', 'academic_year', 'semester']);
            $table->index(['class_id', 'subject_id', 'academic_year', 'semester']);
            
            // Unique constraint untuk mencegah duplikasi nilai per siswa per mata pelajaran per semester
            $table->unique(['student_id', 'subject_id', 'class_id', 'academic_year', 'semester'], 'unique_student_subject_semester_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
