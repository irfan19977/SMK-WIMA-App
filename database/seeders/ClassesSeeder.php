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
                'name' => 'Kelas X TKRO',
                'code' => 'X-TKRO',
                'grade' => '10',
                'major' => 'Teknik Kendaraan Ringan Otomotif',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XI TKRO',
                'code' => 'XI-TKRO',
                'grade' => '11',
                'major' => 'Teknik Kendaraan Ringan Otomotif',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XII TKRO',
                'code' => 'XII-TKRO',
                'grade' => '12',
                'major' => 'Teknik Kendaraan Ringan Otomotif',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas X TBSM',
                'code' => 'X-TBSM',
                'grade' => '10',
                'major' => 'Teknik Bisnis dan Sepeda Motor',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XI TBSM',
                'code' => 'XI-TBSM',
                'grade' => '11',
                'major' => 'Teknik Bisnis dan Sepeda Motor',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XII TBSM',
                'code' => 'XII-TBSM',
                'grade' => '12',
                'major' => 'Teknik Bisnis dan Sepeda Motor',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas X TKI',
                'code' => 'X-TKI',
                'grade' => '10',
                'major' => 'Teknik Kimian Industri',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XI TKI',
                'code' => 'XI-TKI',
                'grade' => '11',
                'major' => 'Teknik Kimian Industri',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XII TKI',
                'code' => 'XII-TKI',
                'grade' => '12',
                'major' => 'Teknik Kimian Industri',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas X TKJ',
                'code' => 'X-TKJ',
                'grade' => '10',
                'major' => 'Teknik Komputer & Jaringan',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XI TKJ',
                'code' => 'XI-TKJ',
                'grade' => '11',
                'major' => 'Teknik Komputer & Jaringan',
                'academic_year' => '2025/2026',
            ],
            [
                'name' => 'Kelas XII TKJ',
                'code' => 'XII-TKJ',
                'grade' => '12',
                'major' => 'Teknik Komputer & Jaringan',
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
                'is_archived' => false,
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
