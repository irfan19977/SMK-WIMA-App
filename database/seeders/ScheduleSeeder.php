<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();
        
        // Get teachers by email
        $teacherUser = User::where('email', 'teacher@gmail.com')->first();
        $hekelUser = User::where('email', 'hekel@gmail.com')->first();
        
        if (!$teacherUser || !$hekelUser) return;
        
        $teacher = Teacher::where('user_id', $teacherUser->id)->first();
        $hekelTeacher = Teacher::where('user_id', $hekelUser->id)->first();
        
        if (!$teacher || !$hekelTeacher) return;
        
        // Define schedules using actual class codes from ClassesSeeder
        $schedules = [
            // Kelas X-A (Grade 10)
            [
                'class_code' => 'XA',
                'subject_code' => 'MTK',
                'day' => 'senin',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'teacher_id' => $teacher->id,
            ],
            [
                'class_code' => 'XA',
                'subject_code' => 'BIND',
                'day' => 'senin',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'teacher_id' => $teacher->id,
            ],
            [
                'class_code' => 'XA',
                'subject_code' => 'BING',
                'day' => 'selasa',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XA',
                'subject_code' => 'IPA',
                'day' => 'selasa',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XA',
                'subject_code' => 'IPS',
                'day' => 'rabu',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'teacher_id' => $teacher->id,
            ],
            
            // Kelas X-B (Grade 10)
            [
                'class_code' => 'XB',
                'subject_code' => 'MTK',
                'day' => 'rabu',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'teacher_id' => $teacher->id,
            ],
            [
                'class_code' => 'XB',
                'subject_code' => 'BIND',
                'day' => 'kamis',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XB',
                'subject_code' => 'BING',
                'day' => 'kamis',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XB',
                'subject_code' => 'PAI',
                'day' => 'jumat',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'teacher_id' => $teacher->id,
            ],
            
            // Kelas XI-A (Grade 11)
            [
                'class_code' => 'XIA',
                'subject_code' => 'MTK',
                'day' => 'senin',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'teacher_id' => $teacher->id,
            ],
            [
                'class_code' => 'XIA',
                'subject_code' => 'BING',
                'day' => 'selasa',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XIA',
                'subject_code' => 'SBUD', // Design subject for DKV major
                'day' => 'rabu',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XIA',
                'subject_code' => 'PPKn',
                'day' => 'kamis',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'teacher_id' => $teacher->id,
            ],
            
            // Kelas XI-B (Grade 11)
            [
                'class_code' => 'XIB',
                'subject_code' => 'MTK',
                'day' => 'jumat',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'teacher_id' => $teacher->id,
            ],
            [
                'class_code' => 'XIB',
                'subject_code' => 'BIND',
                'day' => 'senin',
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XIB',
                'subject_code' => 'IPS', // Relevant for Accounting major
                'day' => 'selasa',
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
                'teacher_id' => $teacher->id,
            ],
            
            // Kelas XII-A (Grade 12)
            [
                'class_code' => 'XIIA',
                'subject_code' => 'MTK',
                'day' => 'rabu',
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
                'teacher_id' => $teacher->id,
            ],
            [
                'class_code' => 'XIIA',
                'subject_code' => 'IPA', // Relevant for Nursing major
                'day' => 'kamis',
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XIIA',
                'subject_code' => 'PJOK', // Health education for Nursing
                'day' => 'jumat',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'teacher_id' => $teacher->id,
            ],
            
            // Kelas XII-B (Grade 12)
            [
                'class_code' => 'XIIB',
                'subject_code' => 'SBUD', // Art subject for DKV major
                'day' => 'senin',
                'start_time' => '14:30:00',
                'end_time' => '16:00:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XIIB',
                'subject_code' => 'BING',
                'day' => 'selasa',
                'start_time' => '14:30:00',
                'end_time' => '16:00:00',
                'teacher_id' => $hekelTeacher->id,
            ],
            [
                'class_code' => 'XIIB',
                'subject_code' => 'MTK',
                'day' => 'rabu',
                'start_time' => '14:30:00',
                'end_time' => '16:00:00',
                'teacher_id' => $teacher->id,
            ],
        ];
        
        foreach ($schedules as $scheduleData) {
            // Get class by code
            $class = Classes::where('code', $scheduleData['class_code'])->first();
            if (!$class) continue;
            
            // Get subject by code
            $subject = Subject::where('code', $scheduleData['subject_code'])->first();
            if (!$subject) continue;
            
            Schedule::create([
                'id' => Str::uuid(),
                'class_id' => $class->id,
                'subject_id' => $subject->id,
                'teacher_id' => $scheduleData['teacher_id'],
                'day' => $scheduleData['day'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'semester' => 'Ganjil',
                'academic_year' => '2025/2026', // Updated to match Classes academic year
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
