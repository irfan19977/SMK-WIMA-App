<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change `content` column to LONGTEXT to support large HTML with images/base64
        DB::statement('ALTER TABLE `news` MODIFY `content` LONGTEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert `content` back to TEXT
        DB::statement('ALTER TABLE `news` MODIFY `content` TEXT');
    }
};
