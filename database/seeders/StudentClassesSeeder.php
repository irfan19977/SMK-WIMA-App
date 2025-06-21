<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        // Define specific assignments based on student emails and class codes
        $assignments = [
            'student1@gmail.com' => 'XA',
            'student2@gmail.com' => 'XIB', 
            'student3@gmail.com' => 'XIIA',
        ];

        foreach ($assignments as $studentEmail => $classCode) {
            // Get student by user email
            $studentUser = User::where('email', $studentEmail)->first();
            if (!$studentUser) continue;

            $student = Student::where('user_id', $studentUser->id)->first();
            if (!$student) continue;

            // Get class by code
            $class = Classes::where('code', $classCode)->first();
            if (!$class) continue;

            StudentClass::create([
                'id' => Str::uuid(),
                'class_id' => $class->id,
                'student_id' => $student->id,
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
