<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        
        if (!$adminUser) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        // Get all students from student_class relationships
        $studentClasses = DB::table('student_class')
            ->join('student', 'student_class.student_id', '=', 'student.id')
            ->join('classes', 'student_class.class_id', '=', 'classes.id')
            ->whereNull('student_class.deleted_at')
            ->whereNull('student.deleted_at')
            ->whereNull('classes.deleted_at')
            ->select(
                'student.id as student_id',
                'student.name as student_name',
                'classes.id as class_id',
                'classes.name as class_name'
            )
            ->get();

        if ($studentClasses->isEmpty()) {
            $this->command->error('No student-class relationships found. Please run ClassesSeeder first.');
            return;
        }

        // Define date range from July 1, 2025 to August 7, 2025 (excluding weekends)
        $startDate = Carbon::create(2025, 7, 1);
        $endDate = Carbon::create(2025, 8, 7);
        
        // Get school schedule (Monday-Saturday)
        $schoolDays = [];
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            // Add weekdays including Saturday (Monday = 1, Saturday = 6)
            if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 6) {
                $schoolDays[] = $current->copy();
            }
            $current->addDay();
        }

        $this->command->info('Generating attendance for ' . count($schoolDays) . ' school days from July 1 - August 7, 2025...');

        // Define possible check-in statuses with probabilities
        $checkInStatuses = [
            'tepat' => 70,        // 70% hadir tepat waktu
            'terlambat' => 20,    // 20% terlambat
            'izin' => 5,          // 5% izin
            'sakit' => 3,         // 3% sakit
            'alpha' => 2          // 2% alpha
        ];

        foreach ($studentClasses as $studentClass) {
            foreach ($schoolDays as $date) {
                // Random check-in status based on probability
                $checkInStatus = $this->getRandomStatus($checkInStatuses);
                
                // Generate check-in time based on status
                $checkInTime = null;
                if (in_array($checkInStatus, ['tepat', 'terlambat'])) {
                    if ($checkInStatus === 'tepat') {
                        // Check-in between 06:30 - 06:59 (sebelum jam 07:00)
                        $checkInTime = $this->generateRandomTime('06:30', '06:59');
                    } else {
                        // Check-in between 07:00 - 08:30 (jam 07:00 ke atas untuk terlambat)
                        $checkInTime = $this->generateRandomTime('07:00', '08:30');
                    }
                }

                // Set created_at to match the date and check_in time
                if ($checkInTime) {
                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', 
                        $date->format('Y-m-d') . ' ' . $checkInTime . ':00',
                        'Asia/Jakarta'
                    );
                } else {
                    // If no check_in, set created_at to 8 AM on that date
                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', 
                        $date->format('Y-m-d') . ' 08:00:00',
                        'Asia/Jakarta'
                    );
                }

                // Create attendance record for check-in only
                Attendance::create([
                    'id' => Str::uuid(),
                    'student_id' => $studentClass->student_id,
                    'class_id' => $studentClass->class_id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkInTime,
                    'check_out' => null, // Tidak ada check_out di record ini
                    'check_in_status' => $checkInStatus,
                    'check_out_status' => null, // Tidak ada status check_out
                    'created_by' => $adminUser->id,
                    'updated_by' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $totalRecords = count($studentClasses) * count($schoolDays);
        $this->command->info("Successfully created {$totalRecords} attendance records (check-in only) from July 1 - August 7, 2025.");
        $this->command->info("Total: " . count($studentClasses) . " students × " . count($schoolDays) . " school days = {$totalRecords} records");
    }

    /**
     * Get random status based on probability weights
     */
    private function getRandomStatus(array $statuses): string
    {
        $random = rand(1, 100);
        $cumulative = 0;
        
        foreach ($statuses as $status => $probability) {
            $cumulative += $probability;
            if ($random <= $cumulative) {
                return $status;
            }
        }
        
        return array_key_first($statuses);
    }

    /**
     * Generate random time between two time ranges
     */
    private function generateRandomTime(string $startTime, string $endTime): string
    {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);
        
        $randomMinutes = rand(0, $end->diffInMinutes($start));
        
        return $start->addMinutes($randomMinutes)->format('H:i');
    }
}
