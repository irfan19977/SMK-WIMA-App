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
        Schema::create('assign_ekstrakurikuler', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ekstrakurikuler_id');
            $table->uuid('student_id');
            $table->string('name');
            $table->string('academic_year');
            $table->timestamps();

            $table->index(['ekstrakurikuler_id', 'student_id']);
            $table->foreign('ekstrakurikuler_id')->references('id')->on('ekstrakurikuler')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_ekstrakurikuler');
    }
};
