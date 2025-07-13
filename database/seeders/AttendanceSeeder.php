<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        // Define specific assignments based on student emails and class codes
        $studentAssignments = [
            'student1@gmail.com' => 'XA',
            'student2@gmail.com' => 'XIB',
            'student3@gmail.com' => 'XIIA',
        ];

        // Get all students with their classes
        $studentsWithClasses = [];
        foreach ($studentAssignments as $studentEmail => $classCode) {
            $studentUser = User::where('email', $studentEmail)->first();
            if (!$studentUser) continue;

            $student = Student::where('user_id', $studentUser->id)->first();
            if (!$student) continue;

            $class = Classes::where('code', $classCode)->first();
            if (!$class) continue;

            $studentsWithClasses[] = [
                'student' => $student,
                'class' => $class
            ];
        }

        // Generate attendance for June 2024 (assuming current year)
        $startDate = Carbon::create(2024, 6, 1);
        $endDate = Carbon::create(2024, 6, 30);

        // Loop through each day in June
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->dayOfWeek == 0 || $date->dayOfWeek == 6) {
                continue;
            }

            foreach ($studentsWithClasses as $studentData) {
                $student = $studentData['student'];
                $class = $studentData['class'];

                // Random chance for different attendance scenarios
                $scenario = $this->getRandomScenario();

                $checkIn = null;
                $checkOut = null;
                $checkInStatus = null;
                $checkOutStatus = null;

                switch ($scenario) {
                    case 'normal':
                        // Normal attendance
                        $checkIn = $this->generateCheckInTime();
                        $checkOut = $this->generateCheckOutTime();
                        $checkInStatus = $checkIn <= '07:00:00' ? 'tepat' : 'terlambat';
                        $checkOutStatus = $checkOut >= '16:00:00' ? 'tepat' : 'lebih_awal';
                        break;

                    case 'late_in':
                        // Late check in
                        $checkIn = $this->generateLateCheckInTime();
                        $checkOut = $this->generateCheckOutTime();
                        $checkInStatus = 'terlambat';
                        $checkOutStatus = $checkOut >= '16:00:00' ? 'tepat' : 'lebih_awal';
                        break;

                    case 'early_out':
                        // Early check out
                        $checkIn = $this->generateCheckInTime();
                        $checkOut = $this->generateEarlyCheckOutTime();
                        $checkInStatus = $checkIn <= '07:00:00' ? 'tepat' : 'terlambat';
                        $checkOutStatus = 'lebih_awal';
                        break;

                    case 'no_checkout':
                        // No check out
                        $checkIn = $this->generateCheckInTime();
                        $checkOut = '17:00:00'; // Default check out time
                        $checkInStatus = $checkIn <= '07:00:00' ? 'tepat' : 'terlambat';
                        $checkOutStatus = 'tidak_absen';
                        break;

                    case 'izin':
                        // Permission (izin)
                        $checkIn = '07:00:00';
                        $checkOut = '07:00:00';
                        $checkInStatus = 'izin';
                        $checkOutStatus = 'izin';
                        break;

                    case 'sakit':
                        // Sick leave
                        $checkIn = '17:00:00';
                        $checkOut = '17:00:00';
                        $checkInStatus = 'sakit';
                        $checkOutStatus = 'sakit';
                        break;

                    case 'alpha':
                        // Absent without permission
                        $checkIn = null;
                        $checkOut = null;
                        $checkInStatus = 'alpha';
                        $checkOutStatus = 'alpha';
                        break;
                }

                Attendance::create([
                    'id' => Str::uuid(),
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'check_in_status' => $checkInStatus,
                    'check_out_status' => $checkOutStatus,
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                ]);
            }
        }
    }

    /**
     * Get random attendance scenario
     */
    private function getRandomScenario(): string
    {
        $scenarios = [
            'normal' => 50,      // 50% normal attendance
            'late_in' => 15,     // 15% late check in
            'early_out' => 10,   // 10% early check out
            'no_checkout' => 5,  // 5% no check out
            'izin' => 8,         // 8% permission
            'sakit' => 7,        // 7% sick
            'alpha' => 5,        // 5% absent without permission
        ];

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($scenarios as $scenario => $percentage) {
            $cumulative += $percentage;
            if ($random <= $cumulative) {
                return $scenario;
            }
        }

        return 'normal';
    }

    /**
     * Generate random check in time (between 06:00 and 08:00)
     */
    private function generateCheckInTime(): string
    {
        $hour = rand(6, 7);
        $minute = rand(0, 59);
        $second = rand(0, 59);

        return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
    }

    /**
     * Generate random late check in time (between 07:01 and 09:00)
     */
    private function generateLateCheckInTime(): string
    {
        $hour = rand(7, 8);
        $minute = $hour == 7 ? rand(1, 59) : rand(0, 59);
        $second = rand(0, 59);

        return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
    }

    /**
     * Generate random check out time (between 15:00 and 17:00)
     */
    private function generateCheckOutTime(): string
    {
        $hour = rand(15, 17);
        $minute = rand(0, 59);
        $second = rand(0, 59);

        return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
    }

    /**
     * Generate random early check out time (between 13:00 and 15:59)
     */
    private function generateEarlyCheckOutTime(): string
    {
        $hour = rand(13, 15);
        $minute = rand(0, 59);
        $second = rand(0, 59);

        return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
    }
}
