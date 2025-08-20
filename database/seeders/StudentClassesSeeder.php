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
        $adminUser = User::where('email', 'superadmin@gmail.com')->first();

        // Define specific assignments based on student emails and class codes
        $assignments = [
            // Student 1, 2, 3 ke Kelas VII (Multimedia)
            'student1@gmail.com' => 'VII',
            'student2@gmail.com' => 'VII',
            'student3@gmail.com' => 'VII',
            
            // Students ke Kelas X (TKJ)
            'andyahmadthariqmaulana@gmail.com' => 'X',
            'azmifawwasfirdausy@gmail.com' => 'X',
            'faishaldanurwedabismawibowo@gmail.com' => 'X',
            'muhammadarshadnaufalmustofa@gmail.com' => 'X',
            'muhammadfuadabdullah@gmail.com' => 'X',
            'muhammadihsanuddinarsyad@gmail.com' => 'X',
            'muhammadradonisbaihaqi@gmail.com' => 'X',
            'muhammadsaidalkatiri@gmail.com' => 'X',
            'muhammadshidqialkautsar@gmail.com' => 'X',
            'musamubarak@gmail.com' => 'X',
            'sholih@gmail.com' => 'X',
            'zidnyzaydanhabibieizzilhatif@gmail.com' => 'X',
            
            // Students ke Kelas XI (TKJ)
            'abdullohazkaibadurrohman@gmail.com' => 'XI',
            'ardanfaiarrafan@gmail.com' => 'XI',
            'arkanthaariiqasadullah@gmail.com' => 'XI',
            'athallahassyarif@gmail.com' => 'XI',
            'chevietoraffiframadhan@gmail.com' => 'XI',
            'fahriabdurrahman@gmail.com' => 'XI',
            'farhanmaulanarizqi@gmail.com' => 'XI',
            'irsyaduddin@gmail.com' => 'XI',
            'muhammadazmyashshiddiqie@gmail.com' => 'XI',
            'muhammadabidnaufal@gmail.com' => 'XI',
            'muhammadfadhlirobbiel@gmail.com' => 'XI',
            'muhammadmishaal@gmail.com' => 'XI',
            'naurakhaula@gmail.com' => 'XI',
            'rakapratama@gmail.com' => 'XI',
            'shahdanalqiarraffi@gmail.com' => 'XI',
            'amuhafganalgazali@gmail.com' => 'XI',
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
                'academic_year' => '2025/2026',
                'semester' => 'ganjil',
                'start_date' => '2025-07-14',
                'status' => 'active',
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
