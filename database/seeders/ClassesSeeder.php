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
        $adminUser = User::where('email', 'superadmin@gmail.com')->first();

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

        foreach ($classes as $class) {
            Classes::create([
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
        }
    }
}
