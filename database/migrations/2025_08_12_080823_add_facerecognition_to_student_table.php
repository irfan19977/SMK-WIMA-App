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
        Schema::table('student', function (Blueprint $table) {
            $table->text('face_encoding')->nullable()->comment('Face encoding data untuk recognition');
            $table->string('face_photo')->nullable()->comment('Path foto wajah siswa');
            $table->timestamp('face_registered_at')->nullable()->comment('Waktu registrasi wajah');
            $table->boolean('face_recognition_enabled')->default(false)->comment('Status aktif face recognition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn(['face_encoding', 'face_photo', 'face_registered_at', 'face_recognition_enabled']);
        });
    }
};
