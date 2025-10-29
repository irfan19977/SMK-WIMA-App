<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdministratorSeeder::class,
            TeacherSeeder::class,
            // StudentSeeder::class,
            // ParentSeeder::class,
            // ClassesSeeder::class,
            // StudentClassesSeeder::class,
            // SubjectSeeder::class,
            // ScheduleSeeder::class,
            SettingScheduleSeeder::class,
            PermissionsSeeder::class,
            // AttendanceXTKJSeeder::class,
            // AttendanceXITKJSeeder::class
            NewsSeeder::class,
        ]);
    }
}
