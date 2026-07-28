<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Get the class ID for X TKJ
        $class = DB::table('classes')->where('code', 'CLS-TKJ-X-26')->first();
        
        if (!$class) {
            $this->command->error('Class X TKJ not found. Please run ClassSeeder first.');
            return;
        }

        // Get teachers
        $teachers = DB::table('teacher')->select('id', 'name')->get();
        
        if ($teachers->isEmpty()) {
            $this->command->error('No teachers found. Please run TeacherSeeder first.');
            return;
        }

        // Get TKJ-relevant subjects
        $tkjSubjects = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Pendidikan Agama dan Budi Pekerti',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Fisika',
            'Kimia',
            'Biologi',
            'Sejarah',
            'Seni Budaya',
            'PJOK',
            'Informatika',
            'Simulasi dan Komunikasi Digital',
            'Pemrograman Dasar',
            'Dasar-Dasar Kejuruan',
            'Pemrograman Web dan Perangkat Bergerak',
            'Pemrograman Berorientasi Objek',
            'Basis Data',
        ];

        $subjects = DB::table('subject')->whereIn('name', $tkjSubjects)->get();

        if ($subjects->isEmpty()) {
            $this->command->error('No subjects found. Please run SubjectSeeder first.');
            return;
        }

        // Schedule template (5 days: Senin - Jumat)
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $timeSlots = [
            ['start' => '07:00:00', 'end' => '08:30:00'],
            ['start' => '08:30:00', 'end' => '10:00:00'],
            ['start' => '10:15:00', 'end' => '11:45:00'],
            ['start' => '12:30:00', 'end' => '14:00:00'],
            ['start' => '14:00:00', 'end' => '15:30:00'],
        ];

        $scheduleCount = 0;
        $teacherIndex = 0;
        $subjectIndex = 0;

        foreach ($days as $day) {
            foreach ($timeSlots as $slot) {
                $subject = $subjects[$subjectIndex % $subjects->count()];
                $teacher = $teachers[$teacherIndex % $teachers->count()];

                DB::table('schedule')->insert([
                    'id' => Str::uuid()->toString(),
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'day' => $day,
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'semester' => 'ganjil',
                    'academic_year' => '2026/2027',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $scheduleCount++;
                $teacherIndex++;
                $subjectIndex++;
            }
        }

        $this->command->info($scheduleCount . ' schedules seeded for X TKJ class.');
    }
}
