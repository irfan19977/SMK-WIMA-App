<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentClassSeeder extends Seeder
{
    public function run(): void
    {
        // Get the class ID for X TKJ
        $class = DB::table('classes')->where('code', 'CLS-TKJ-X-26')->first();
        
        if (!$class) {
            $this->command->error('Class X TKJ not found. Please run ClassSeeder first.');
            return;
        }

        // Get all students
        $students = DB::table('student')->select('id')->get();

        foreach ($students as $student) {
            DB::table('student_class')->insert([
                'id' => Str::uuid()->toString(),
                'class_id' => $class->id,
                'student_id' => $student->id,
                'academic_year' => '2026/2027',
                'semester' => 'ganjil',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonths(6)->format('Y-m-d'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($students) . ' students assigned to X TKJ class.');
    }
}
