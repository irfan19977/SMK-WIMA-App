<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use Carbon\Carbon;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Semester Ganjil 2025/2026
        Semester::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'academic_year' => '2025/2026',
            'semester_type' => 'ganjil',
            'is_active' => true, // Aktifkan semester ganjil sebagai default
            'start_date' => '2025-07-01', // 1 Juli 2025
            'end_date' => '2025-12-31', // 31 Desember 2025
            'description' => 'Semester Ganjil Tahun Ajaran 2025/2026',
            'created_by' => null, // Akan diisi oleh sistem jika ada user yang login
        ]);

        // Semester Genap 2025/2026
        Semester::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'academic_year' => '2025/2026',
            'semester_type' => 'genap',
            'is_active' => false,
            'start_date' => '2026-01-01', // 1 Januari 2026
            'end_date' => '2026-06-30', // 30 Juni 2026
            'description' => 'Semester Genap Tahun Ajaran 2025/2026',
            'created_by' => null,
        ]);

        // Semester Ganjil 2026/2027 (untuk persiapan tahun ajaran baru)
        Semester::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'academic_year' => '2026/2027',
            'semester_type' => 'ganjil',
            'is_active' => false,
            'start_date' => '2026-07-01', // 1 Juli 2026
            'end_date' => '2026-12-31', // 31 Desember 2026
            'description' => 'Semester Ganjil Tahun Ajaran 2026/2027',
            'created_by' => null,
        ]);

        $this->command->info('Semester data seeded successfully.');
    }
}
