<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SettingSchedule;
use App\Models\Student;
use App\Models\StudentClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RFIDController extends Controller
{
    public function detectRFID(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'rfid_card' => 'required|string'
            ]);
            
            $rfidCard = $request->input('rfid_card');
            
            // Find student by RFID card number (no_card)
            $student = Student::where('no_card', $rfidCard)->first();
            
            if (!$student) {
                // Check if the card is already registered to someone else
                $existingStudent = Student::where('no_card', $rfidCard)->first();
                $isUsed = $existingStudent ? true : false;
                
                // Store the RFID value in cache with timestamp
                Cache::put('latest_rfid', [
                    'value' => $rfidCard,
                    'is_used' => $isUsed,
                    'user_name' => $isUsed ? $existingStudent->name : null,
                    'timestamp' => Carbon::now()->timestamp
                ], now()->addMinutes(5)); // Keep in cache for 5 minutes
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Kartu RFID tidak terdaftar',
                    'rfid_value' => $rfidCard
                ]);
            }
            
            // Get current time and date using Carbon with Asia/Jakarta timezone
            $now = Carbon::now('Asia/Jakarta');
            $currentTime = $now->format('H:i:s');
            $currentDate = $now->format('Y-m-d');
            $currentDay = $this->convertDayToIndonesian($now->format('l'));
            
            // Get student's class ID from student_class pivot table
            $studentClass = StudentClass::where('student_id', $student->id)->first();
            
            if (!$studentClass) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Siswa tidak memiliki kelas',
                    'student' => [
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                    ]
                ]);
            }
            
            // Get schedule for current day
            $schedule = SettingSchedule::where('day', $currentDay)->first();
            
            if (!$schedule) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada jadwal untuk hari ini',
                    'student' => [
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                        'class' => $studentClass->class->name ?? 'Tidak ada kelas',
                    ]
                ]);
            }
            
            // Check existing attendance for today
            $existingAttendance = Attendance::where([
                'student_id' => $student->id,
                'class_id' => $studentClass->class_id,
                'date' => $currentDate
            ])->first();
            
            DB::beginTransaction();
            
            if (!$existingAttendance) {
                // First scan - Check if it's already past end time
                $currentTimeCarbon = Carbon::createFromFormat('H:i:s', $currentTime, 'Asia/Jakarta');
                $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                
                // If current time is past end time, create Alpha attendance
                if ($currentTimeCarbon->gte($scheduleEndTime)) {
                    return $this->processAlphaAttendance($student, $studentClass, $schedule, $currentTime, $currentDate);
                }
                
                // Otherwise, process normal check in
                return $this->processCheckIn($student, $studentClass, $schedule, $currentTime, $currentDate);
            } else {
                // Second scan - Check if it's time for Check Out
                if ($existingAttendance->check_out) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Siswa sudah melakukan absen masuk dan pulang hari ini',
                        'student' => [
                            'name' => $student->name,
                            'nisn' => $student->nisn,
                            'class' => $studentClass->class->name ?? 'Tidak ada kelas',
                            'check_in' => $existingAttendance->check_in,
                            'check_in_status' => $existingAttendance->check_in_status,
                            'check_out' => $existingAttendance->check_out,
                            'check_out_status' => $existingAttendance->check_out_status,
                        ]
                    ]);
                }
                
                // Check if it's time for check out
                $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                $currentTimeCarbon = Carbon::createFromFormat('H:i:s', $currentTime, 'Asia/Jakarta');
                
                if ($currentTimeCarbon->format('H:i:s') < $schedule->end_time) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Siswa sudah melakukan absen masuk. Waktu pulang: ' . $scheduleEndTime->format('H:i'),
                        'student' => [
                            'name' => $student->name,
                            'nisn' => $student->nisn,
                            'class' => $studentClass->class->name ?? 'Tidak ada kelas',
                            'check_in' => $existingAttendance->check_in,
                            'check_in_status' => $existingAttendance->check_in_status,
                        ]
                    ]);
                }
                
                // Process Check Out
                return $this->processCheckOut($existingAttendance, $schedule, $currentTime, $student, $studentClass);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            // Log the error
            \Illuminate\Support\Facades\Log::error('RFID Attendance Error: ' . $e->getMessage());
            
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan absensi: ' . $e->getMessage()
            ]);
        }
    }
    
    private function processCheckIn($student, $studentClass, $schedule, $currentTime, $currentDate)
    {
        // Determine check in status
        $checkInTimeCarbon = Carbon::createFromFormat('H:i:s', $currentTime, 'Asia/Jakarta');
        $scheduleStartTime = Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
        
        $checkInStatus = 'tepat';
        if ($checkInTimeCarbon->gt($scheduleStartTime)) {
            $checkInStatus = 'terlambat';
        }
        
        // Create attendance record
        $attendanceData = [
            'id' => Str::uuid(),
            'student_id' => $student->id,
            'class_id' => $studentClass->class_id,
            'date' => $currentDate,
            'check_in' => $checkInTimeCarbon->format('H:i'),
            'check_in_status' => $checkInStatus,
            'created_by' => Auth::id(),
        ];
        
        $attendance = Attendance::create($attendanceData);
        
        DB::commit();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Absen masuk berhasil dicatat',
            'type' => 'check_in',
            'student' => [
                'name' => $student->name,
                'nisn' => $student->nisn,
                'class' => $studentClass->class->name ?? 'Tidak ada kelas',
                'check_in' => $checkInTimeCarbon->format('H:i'),
                'check_in_status' => $checkInStatus,
                'schedule_start' => $scheduleStartTime->format('H:i'),
            ]
        ]);
    }
    
    private function processCheckOut($existingAttendance, $schedule, $currentTime, $student, $studentClass)
    {
        // Determine check out status
        $checkOutTimeCarbon = Carbon::createFromFormat('H:i:s', $currentTime, 'Asia/Jakarta');
        $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
        
        $checkOutStatus = 'tepat';
        if ($checkOutTimeCarbon->lt($scheduleEndTime)) {
            $checkOutStatus = 'lebih_awal';
        }
        
        // Update existing attendance with check out
        $existingAttendance->update([
            'check_out' => $checkOutTimeCarbon->format('H:i'),
            'check_out_status' => $checkOutStatus,
            'updated_by' => Auth::id(),
        ]);
        
        DB::commit();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Absen pulang berhasil dicatat',
            'type' => 'check_out',
            'student' => [
                'name' => $student->name,
                'nisn' => $student->nisn,
                'class' => $studentClass->class->name ?? 'Tidak ada kelas',
                'check_in' => $existingAttendance->check_in,
                'check_in_status' => $existingAttendance->check_in_status,
                'check_out' => $checkOutTimeCarbon->format('H:i'),
                'check_out_status' => $checkOutStatus,
                'schedule_end' => $scheduleEndTime->format('H:i'),
            ]
        ]);
    }
    
    /**
     * Process Alpha attendance when student didn't attend class
     */
    private function processAlphaAttendance($student, $studentClass, $schedule, $currentTime, $currentDate)
    {
        $currentTimeCarbon = Carbon::createFromFormat('H:i:s', $currentTime, 'Asia/Jakarta');
        $scheduleStartTime = Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
        $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
        
        // Create attendance record with Alfa status for both check in and check out
        $attendanceData = [
            'id' => Str::uuid(),
            'student_id' => $student->id,
            'class_id' => $studentClass->class_id,
            'date' => $currentDate,
            'check_in' => $currentTimeCarbon->format('H:i'),
            'check_in_status' => 'alpha',
            'check_out' => $currentTimeCarbon->format('H:i'),
            'check_out_status' => 'alpha',
            'created_by' => Auth::id(),
        ];
        
        $attendance = Attendance::create($attendanceData);
        
        DB::commit();
        
        return response()->json([
            'status' => 'warning',
            'message' => 'Siswa tidak mengikuti mata pelajaran hari ini (Alpha)',
            'type' => 'alpha',
            'student' => [
                'name' => $student->name,
                'nisn' => $student->nisn,
                'class' => $studentClass->class->name ?? 'Tidak ada kelas',
                'check_in' => $currentTimeCarbon->format('H:i'),
                'check_in_status' => 'alpha',
                'check_out' => $currentTimeCarbon->format('H:i'),
                'check_out_status' => 'alpha',
                'schedule_start' => $scheduleStartTime->format('H:i'),
                'schedule_end' => $scheduleEndTime->format('H:i'),
                'tap_time' => $currentTimeCarbon->format('H:i:s'),
            ]
        ]);
    }
    
    /**
     * Convert English day names to Indonesian
     */
    private function convertDayToIndonesian($englishDay)
    {
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $dayMap[$englishDay] ?? $englishDay;
    }
    
    /**
     * Get the latest RFID card detected
     * 
     * @return \Illuminate\Http\Response
     */
    public function getLatestRFID()
    {
        $latestRFID = Cache::get('latest_rfid');
        
        if (!$latestRFID) {
            return response()->json([
                'status' => 'error',
                'message' => 'No RFID card detected recently',
                'rfid' => null,
                'is_used' => false,
            ]);
        }
        
        // Only return RFID values that are relatively new (within the last minute)
        $now = Carbon::now()->timestamp;
        if ($now - $latestRFID['timestamp'] > 60) {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID detection has expired',
                'rfid' => null,
                'is_used' => false,
            ]);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => $latestRFID['is_used'] ? 'RFID card is already in use' : 'RFID card detected',
            'rfid' => $latestRFID['value'],
            'is_used' => $latestRFID['is_used'],
            'user_name' => $latestRFID['user_name'] ?? null,
        ]);
    }
    
    /**
     * Clear the RFID cache
     * 
     * @return \Illuminate\Http\Response
     */
    public function clearRFIDCache()
    {
        Cache::forget('latest_rfid');
        
        return response()->json([
            'status' => 'success',
            'message' => 'RFID cache cleared successfully'
        ]);
    }
}
