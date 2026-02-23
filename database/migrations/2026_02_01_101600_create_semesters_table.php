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
        Schema::create('semesters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('academic_year'); // Format: 2025/2026
            $table->enum('semester_type', ['ganjil', 'genap']); // Tipe semester
            $table->boolean('is_active')->default(false); // Status aktif
            $table->date('start_date')->nullable(); // Tanggal mulai semester
            $table->date('end_date')->nullable(); // Tanggal selesai semester
            $table->text('description')->nullable(); // Keterangan tambahan
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['academic_year', 'semester_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
