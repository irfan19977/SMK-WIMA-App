<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttendanceXTKJSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'superadmin@gmail.com')->first();
        
        // Custom attendance data untuk setiap student
        $attendanceData = [
            'andyahmadthariqmaulana@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'azmifawwasfirdausy@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'faishaldanurwedabismawibowo@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'muhammadarshadnaufalmustofa@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'muhammadfuadabdullah@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'muhammadihsanuddinarsyad@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'muhammadradonisbaihaqi@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'izin',
                    'check_out_status' => 'izin',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'muhammadsaidalkatiri@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'muhammadshidqialkautsar@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'musamubarak@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'sholih@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            'zidnyzaydanhabibieizzilhatif@gmail.com' => [
                [
                    'date' => '2025-07-18',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-07-25',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-01',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-08',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],
                [
                    'date' => '2025-08-15',
                    'check_in' => '06:30:00',
                    'check_out' => '15:30:00',
                    'check_in_status' => 'tepat',
                    'check_out_status' => 'tepat',
                ],

            ],
            
        ];

        // Get default class (you can modify this logic)
        $defaultClass = Classes::first();
        
        if (!$defaultClass) {
            $this->command->warn('No classes found. Please create some classes first.');
            return;
        }

        // Process each student's attendance data
        foreach ($attendanceData as $email => $attendances) {
            // Find student by EMAIL
            $student = Student::whereHas('user', function($query) use ($email) {
                $query->where('email', $email);
            })->first();
            
            if (!$student) {
                $this->command->warn("Student with EMAIL {$email} not found. Skipping...");
                continue;
            }
            
            // Create attendance records for this student
            foreach ($attendances as $attendanceRecord) {
                // Check if attendance already exists
                $existingAttendance = Attendance::where('student_id', $student->id)
                    ->where('date', $attendanceRecord['date'])
                    ->first();
                
                if ($existingAttendance) {
                    $this->command->warn("Attendance already exists for {$student->name} on {$attendanceRecord['date']}. Skipping...");
                    continue;
                }
                
                Attendance::create([
                    'id' => Str::uuid(),
                    'student_id' => $student->id,
                    'class_id' => $defaultClass->id, // You can customize this per record if needed
                    'date' => $attendanceRecord['date'],
                    'check_in' => $attendanceRecord['check_in'],
                    'check_out' => $attendanceRecord['check_out'],
                    'check_in_status' => $attendanceRecord['check_in_status'],
                    'check_out_status' => $attendanceRecord['check_out_status'],
                    'academic_year' => '2025/2026',
                    'semester' => 'ganjil',
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'created_at' => $attendanceRecord['date'] . ' ' . $attendanceRecord['check_in'],
                    'updated_at' => $attendanceRecord['date'] . ' ' . $attendanceRecord['check_out'],
                ]);
                
                $this->command->info("Attendance created for {$student->name} on {$attendanceRecord['date']} - Check-in: {$attendanceRecord['check_in_status']}, Check-out: {$attendanceRecord['check_out_status']}");
            }
        }
        
        $this->command->info('All custom attendance records have been created successfully!');
    }
}
