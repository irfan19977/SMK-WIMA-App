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
        Schema::create('asrama_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->integer('sholat_tahajud')->nullable();
            $table->integer('sholat_dhuha')->nullable();
            $table->integer('sholat_syuruq')->nullable();
            $table->integer('sunnah_rawatib')->nullable();
            $table->integer('puasa')->nullable();
            $table->integer('dzikir_pagi_petang')->nullable();
            $table->integer('adab_berbicara')->nullable();
            $table->integer('adab_bersikap')->nullable();
            $table->integer('kejujuran')->nullable();
            $table->integer('waktu_tidur')->nullable();
            $table->integer('pelaksanaan_piket')->nullable();
            $table->integer('kegiatan_mahad')->nullable();
            $table->integer('jasmani_penampilan')->nullable();
            $table->integer('kerapian_lemari')->nullable();
            $table->integer('kerapian_ranjang')->nullable();
            $table->integer('bahasa_arab')->nullable();
            $table->integer('bahasa_inggris')->nullable();
            $table->text('catatan');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asrama_grades');
    }
};
