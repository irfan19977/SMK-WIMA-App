<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Get admin user for created_by
            $adminUser = User::where('email', 'administrator@gmail.com')->first();
            if (!$adminUser) {
                $this->command->error('Admin user not found: administrator@gmail.com');
                return;
            }
            
            // Get teachers by email
            $teacherUser = User::where('email', 'teacher1@gmail.com')->first();
            $hekelUser = User::where('email', 'teacher2@gmail.com')->first();
            
            if (!$teacherUser) {
                $this->command->error('Teacher user not found: teacher1@gmail.com');
                return;
            }
            
            if (!$hekelUser) {
                $this->command->error('Teacher user not found: teacher2@gmail.com');
                return;
            }
            
            $teacher = Teacher::where('user_id', $teacherUser->id)->first();
            $hekelTeacher = Teacher::where('user_id', $hekelUser->id)->first();
            
            if (!$teacher) {
                $this->command->error('Teacher record not found for user: teacher@gmail.com');
                return;
            }
            
            if (!$hekelTeacher) {
                $this->command->error('Teacher record not found for user: hekel@gmail.com');
                return;
            }
            
            // Define schedules using actual class codes from ClassesSeeder
            $schedules = [
                // Kelas X (Grade 10)
                [
                    'class_code' => 'X',
                    'subject_code' => 'MTK',
                    'day' => 'senin',
                    'start_time' => '07:00:00',
                    'end_time' => '08:30:00',
                    'teacher_id' => $teacher->id,
                ],
                [
                    'class_code' => 'X',
                    'subject_code' => 'BIND',
                    'day' => 'senin',
                    'start_time' => '08:30:00',
                    'end_time' => '10:00:00',
                    'teacher_id' => $teacher->id,
                ],
                [
                    'class_code' => 'X',
                    'subject_code' => 'BING',
                    'day' => 'selasa',
                    'start_time' => '07:00:00',
                    'end_time' => '08:30:00',
                    'teacher_id' => $hekelTeacher->id,
                ],
                [
                    'class_code' => 'X',
                    'subject_code' => 'IPA',
                    'day' => 'selasa',
                    'start_time' => '08:30:00',
                    'end_time' => '10:00:00',
                    'teacher_id' => $hekelTeacher->id,
                ],
                [
                    'class_code' => 'X',
                    'subject_code' => 'IPS',
                    'day' => 'rabu',
                    'start_time' => '07:00:00',
                    'end_time' => '08:30:00',
                    'teacher_id' => $teacher->id,
                ],
                
                // Kelas XI (Grade 11)
                [
                    'class_code' => 'XI',
                    'subject_code' => 'MTK',
                    'day' => 'rabu',
                    'start_time' => '08:30:00',
                    'end_time' => '10:00:00',
                    'teacher_id' => $teacher->id,
                ],
                [
                    'class_code' => 'XI',
                    'subject_code' => 'BIND',
                    'day' => 'kamis',
                    'start_time' => '07:00:00',
                    'end_time' => '08:30:00',
                    'teacher_id' => $hekelTeacher->id,
                ],
                [
                    'class_code' => 'XI',
                    'subject_code' => 'BING',
                    'day' => 'kamis',
                    'start_time' => '08:30:00',
                    'end_time' => '10:00:00',
                    'teacher_id' => $hekelTeacher->id,
                ],
                [
                    'class_code' => 'XI',
                    'subject_code' => 'PAI',
                    'day' => 'jumat',
                    'start_time' => '07:00:00',
                    'end_time' => '08:30:00',
                    'teacher_id' => $teacher->id,
                ],
            ];
            
            $successCount = 0;
            $failedCount = 0;
            
            foreach ($schedules as $scheduleData) {
                // Get class by code
                $class = Classes::where('code', $scheduleData['class_code'])->first();
                if (!$class) {
                    $this->command->warn("Class not found: {$scheduleData['class_code']}");
                    $failedCount++;
                    continue;
                }
                
                // Get subject by code
                $subject = Subject::where('code', $scheduleData['subject_code'])->first();
                if (!$subject) {
                    $this->command->warn("Subject not found: {$scheduleData['subject_code']}");
                    $failedCount++;
                    continue;
                }
                
                // Check if schedule already exists
                $existingSchedule = Schedule::where([
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'day' => $scheduleData['day'],
                    'start_time' => $scheduleData['start_time'],
                    'academic_year' => '2025/2026',
                    'semester' => 'Ganjil'
                ])->first();
                
                if ($existingSchedule) {
                    $this->command->info("Schedule already exists: {$scheduleData['class_code']} - {$scheduleData['subject_code']} on {$scheduleData['day']}");
                    continue;
                }
                
                try {
                    Schedule::create([
                        'id' => Str::uuid(),
                        'class_id' => $class->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $scheduleData['teacher_id'],
                        'day' => $scheduleData['day'],
                        'start_time' => $scheduleData['start_time'],
                        'end_time' => $scheduleData['end_time'],
                        'semester' => 'Ganjil',
                        'academic_year' => '2025/2026',
                        'created_by' => $adminUser->id,
                        'updated_by' => null,
                        'deleted_by' => null,
                    ]);
                    
                    $successCount++;
                    $this->command->info("Created schedule: {$scheduleData['class_code']} - {$scheduleData['subject_code']} on {$scheduleData['day']}");
                    
                } catch (\Exception $e) {
                    $this->command->error("Failed to create schedule: {$scheduleData['class_code']} - {$scheduleData['subject_code']} on {$scheduleData['day']}");
                    $this->command->error("Error: " . $e->getMessage());
                    $failedCount++;
                }
            }
            
            $this->command->info("Schedule seeding completed: {$successCount} success, {$failedCount} failed");
            
        } catch (\Exception $e) {
            $this->command->error('Schedule seeder failed: ' . $e->getMessage());
            Log::error('Schedule seeder error: ' . $e->getMessage());
        }
    }
}
