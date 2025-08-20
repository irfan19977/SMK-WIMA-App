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
            $table->enum('semester', ['ganjil', 'genap']);
            $table->tinyInteger('month'); // 1-12 (Januari-Desember)
            $table->string('month_name')->nullable(); // Januari, Februari, dst
            
            // Komponen Nilai
            $table->decimal('h1', 5, 2)->nullable(); // Harian 1
            $table->decimal('h2', 5, 2)->nullable(); // Harian 2  
            $table->decimal('h3', 5, 2)->nullable(); // Harian 3
            $table->decimal('k1', 5, 2)->nullable(); // Kuis 1
            $table->decimal('k2', 5, 2)->nullable(); // Kuis 2
            $table->decimal('k3', 5, 2)->nullable(); // Kuis 3
            $table->decimal('ck', 5, 2)->nullable(); // Cek Kemampuan
            $table->decimal('p', 5, 2)->nullable();  // Praktik
            $table->decimal('k', 5, 2)->nullable();  // Keaktifan
            $table->decimal('aktif', 5, 2)->nullable(); // Aktivitas
            $table->decimal('nilai', 5, 2)->nullable(); // Nilai Akhir/Total
            
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
            $table->index(['student_id', 'academic_year', 'month']);
            $table->index(['class_id', 'subject_id', 'academic_year', 'month']);
            
            // Unique constraint untuk mencegah duplikasi nilai per siswa per mata pelajaran per bulan
            $table->unique(['student_id', 'subject_id', 'class_id', 'academic_year', 'month'], 'unique_student_subject_monthly_grade');
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
