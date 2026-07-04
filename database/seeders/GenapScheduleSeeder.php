<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Classes;
use Illuminate\Support\Str;

class GenapScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $classes = Classes::where('academic_year', '2025/2026')->get();

        $created = 0;
        foreach ($classes as $class) {
            $ganjilSchedules = Schedule::where('class_id', $class->id)
                ->where('academic_year', '2025/2026')
                ->where('semester', 'ganjil')
                ->get();

            foreach ($ganjilSchedules as $schedule) {
                $exists = Schedule::where('class_id', $class->id)
                    ->where('academic_year', '2025/2026')
                    ->where('semester', 'genap')
                    ->where('day', $schedule->day)
                    ->where('start_time', $schedule->start_time)
                    ->where('subject_id', $schedule->subject_id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                Schedule::create([
                    'class_id' => $schedule->class_id,
                    'subject_id' => $schedule->subject_id,
                    'teacher_id' => $schedule->teacher_id,
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'academic_year' => '2025/2026',
                    'semester' => 'genap',
                ]);

                $created++;
            }
        }

        $this->command->info("Created {$created} genap schedule records.");
    }
}
