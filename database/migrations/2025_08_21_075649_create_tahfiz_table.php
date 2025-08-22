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
        Schema::create('tahfiz', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('class_id');
            $table->string('academic_year');
            $table->tinyInteger('month'); // 1-12 (Januari-Desember)
            $table->string('month_name')->nullable(); // Januari, Februari, dst
            $table->string('progres_tahfiz')->nullable();
            $table->string('progres_tahsin')->nullable();
            $table->string('target_hafalan')->nullable();
            $table->integer('efektif_halaqoh')->nullable();
            $table->integer('hadir')->nullable();
            $table->integer('keatifan')->nullable();
            $table->integer('izin')->nullable();
            $table->integer('sakit')->nullable();
            $table->integer('alpha')->nullable();
            // Audit fields
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes untuk performa query
            $table->index(['student_id', 'academic_year', 'month']);
            $table->index(['class_id', 'academic_year', 'month']);
            
            // Unique constraint untuk mencegah duplikasi nilai per siswa per mata pelajaran per bulan
            $table->unique(['student_id', 'class_id', 'academic_year', 'month'], 'unique_student_monthly_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfiz');
    }
};
