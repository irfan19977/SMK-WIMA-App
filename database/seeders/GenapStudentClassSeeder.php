<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenapStudentClassSeeder extends Seeder
{
    public function run(): void
    {
        $ganjilRecords = DB::table('student_class')
            ->where('academic_year', '2025/2026')
            ->where('semester', 'ganjil')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->get();

        $created = 0;
        foreach ($ganjilRecords as $record) {
            $exists = DB::table('student_class')
                ->where('student_id', $record->student_id)
                ->where('class_id', $record->class_id)
                ->where('academic_year', '2025/2026')
                ->where('semester', 'genap')
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('student_class')->insert([
                'id' => (string) Str::uuid(),
                'student_id' => $record->student_id,
                'class_id' => $record->class_id,
                'academic_year' => '2025/2026',
                'semester' => 'genap',
                'start_date' => now()->format('Y-m-d'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $created++;
        }

        $this->command->info("Created {$created} genap student_class records.");
    }
}
