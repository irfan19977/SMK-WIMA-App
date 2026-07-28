<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesPermissionsSeeder::class,
            AdminSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            ParentSeeder::class,
            SubjectSeeder::class,
            ClassSeeder::class,
            StudentClassSeeder::class,
            ScheduleSeeder::class,
            SettingScheduleSeeder::class,
        ]);
    }
}
