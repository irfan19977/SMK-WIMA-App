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
            $table->string('jurusan_utama')->nullable()->after('sertifikat');
            $table->string('jurusan_cadangan')->nullable()->after('jurusan_utama');
            $table->string('academic_year')->nullable()->after('jurusan_cadangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn('jurusan_utama');
            $table->dropColumn('jurusan_cadangan');
            $table->dropColumn('academic_year');
        });
    }
};
