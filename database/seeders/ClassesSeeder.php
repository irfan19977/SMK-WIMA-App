<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        $classes = [
            [
                'name' => 'Kelas VII',
                'code' => 'VII',
                'grade' => '7',
                'major' => 'Design Komunikasi Visual',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas X',
                'code' => 'X',
                'grade' => '10',
                'major' => 'Teknik Komputer dan Jaringan',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XI',
                'code' => 'XI',
                'grade' => '11',
                'major' => 'Teknik Komputer dan Jaringan',
                'academic_year' => '2025/2026',
            ],
        ];

        $createdClasses = [];

        foreach ($classes as $class) {
            $createdClass = Classes::create([
                'id' => Str::uuid(),
                'name' => $class['name'],
                'code' => $class['code'],
                'grade' => $class['grade'],
                'major' => $class['major'],
                'academic_year' => $class['academic_year'],
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
            
            $createdClasses[] = $createdClass;
        }

        // Get students for assignment
        $students = Student::whereHas('user', function($query) {
            $query->whereIn('email', [
                'student1@gmail.com',
                'student2@gmail.com',
                'student3@gmail.com'
            ]);
        })->with('user')->get();

        // Assign students to classes
        if ($students->count() > 0 && count($createdClasses) > 0) {
            // Assign student1 to Kelas VII
            $student1 = $students->filter(function($student) {
                return $student->user->email === 'student1@gmail.com';
            })->first();
            
            if ($student1 && isset($createdClasses[0])) {
                StudentClass::create([
                    'id' => Str::uuid(),
                    'class_id' => $createdClasses[0]->id, // Kelas VII
                    'student_id' => $student1->id,
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]);
            }

            // Assign student2 to Kelas X
            $student2 = $students->filter(function($student) {
                return $student->user->email === 'student2@gmail.com';
            })->first();
            
            if ($student2 && isset($createdClasses[1])) {
                StudentClass::create([
                    'id' => Str::uuid(),
                    'class_id' => $createdClasses[1]->id, // Kelas X
                    'student_id' => $student2->id,
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]);
            }

            // Assign student3 to Kelas XI
            $student3 = $students->filter(function($student) {
                return $student->user->email === 'student3@gmail.com';
            })->first();
            
            if ($student3 && isset($createdClasses[2])) {
                StudentClass::create([
                    'id' => Str::uuid(),
                    'class_id' => $createdClasses[2]->id, // Kelas XI
                    'student_id' => $student3->id,
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]);
            }
        }
    }
}
