<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SettingSchedule;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Schedule;
use App\Models\LessonAttendance;
use App\Models\StudentPermission;
use App\Models\Semester;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RFIDController extends Controller
{
    public function detect(Request $request)
    {
        // Cek apakah ada UID yang dikirim dari RFID reader
        if ($request->has('uid')) {
            // Mode: menerima UID dari RFID reader
            $request->validate([
                'uid' => 'required|string',
            ]);

            $uid = $request->input('uid');

            // Log untuk debugging
            \Log::info('RFID Scan diterima: ' . $uid);

            // Simpan ke cache untuk polling
            Cache::put('latest_rfid_uid', $uid, now()->addSeconds(30));

            // Cek apakah kartu terdaftar pada siswa
            $student = Student::where('no_card', $uid)->first();

            if ($student) {
                // Kartu terdaftar → cek absen dan auto-attendance
                \Log::info('Kartu terdaftar pada siswa: ' . $student->name);
                
                // Langsung proses auto-attendance
                return $this->autoAttendance($request);
            } else {
                // Kartu tidak terdaftar → return info untuk pendaftaran
                \Log::info('Kartu tidak terdaftar, siap untuk pendaftaran: ' . $uid);
                
                return response()->json([
                    'status'  => 'unregistered',
                    'message' => 'Kartu RFID tidak terdaftar pada siswa manapun',
                    'uid'     => $uid,
                    'timestamp' => now()->toISOString(),
                    'action' => 'register_student',
                    'suggestion' => 'Silakan daftarkan siswa baru dengan kartu ini'
                ], 200);
            }
        } else {
            // Mode: deteksi kartu RFID untuk form siswa
            // Cek cache untuk UID terbaru
            $uid = Cache::get('latest_rfid_uid');
            
            if (!$uid) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada kartu RFID yang terdeteksi. Silakan tempelkan kartu ke reader terlebih dahulu.',
                ], 404);
            }

            // Cek apakah nomor kartu sudah digunakan oleh siswa lain
            $existingStudent = Student::where('no_card', $uid)->first();
            if ($existingStudent) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Nomor kartu {$uid} sudah digunakan oleh siswa: {$existingStudent->name}",
                ], 422);
            }

            return response()->json([
                'status' => 'success',
                'uid' => $uid,
                'message' => 'Kartu RFID berhasil terdeteksi',
                'timestamp' => now()->toISOString(),
            ]);
        }
    }
    
    public function validateCard(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
        ]);

        $uid = $request->input('uid');

        // Cek apakah nomor kartu sudah digunakan oleh siswa lain
        $existingStudent = Student::where('no_card', $uid)->first();
        if ($existingStudent) {
            return response()->json([
                'valid' => false,
                'message' => "Nomor kartu {$uid} sudah digunakan oleh siswa: {$existingStudent->name}",
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Kartu valid dan tersedia',
        ]);
    }

    public function clearCache()
    {
        Cache::forget('latest_rfid_uid');
        return response()->json([
            'status' => 'success',
            'message' => 'Cache cleared'
        ]);
    }
    
    public function getLatest()
    {
        $uid = Cache::get('latest_rfid_uid');
        return response()->json([
            'status' => 'success',
            'uid' => $uid,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Fungsi untuk membuat absensi otomatis ketika kartu RFID terdeteksi
     */
    public function autoAttendance(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
        ]);

        $uid = $request->input('uid');
        $currentTime = Carbon::now('Asia/Jakarta');
        $currentDate = $currentTime->format('Y-m-d');
        $dayName = $this->convertDayToIndonesian($currentTime->format('l'));
        $dayNameLower = strtolower($dayName); // Untuk query tabel schedule (enum lowercase)

        // Get active semester dari DB, fallback ke AcademicYearHelper jika tidak ada
        $activeSemester = Semester::where('is_active', true)->first();
        $academicYear = $activeSemester ? $activeSemester->academic_year : \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
        $semesterType = $activeSemester ? $activeSemester->semester_type : \App\Helpers\AcademicYearHelper::getCurrentSemester();
        // Cover kemungkinan simpan dengan ucfirst (Ganjil/Genap) atau lowercase (ganjil/genap)
        $semesterTypeVariants = [strtolower($semesterType), ucfirst($semesterType)];

        try {
            DB::beginTransaction();

            // Cari siswa berdasarkan nomor kartu
            $student = Student::where('no_card', $uid)->first();
            
            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Kartu dengan UID {$uid} tidak terdaftar pada siswa manapun"
                ], 404);
            }

            // Cari kelas aktif siswa berdasarkan semester aktif
            $studentClassQuery = StudentClass::join('classes', 'student_class.class_id', '=', 'classes.id')
                ->where('student_class.student_id', $student->id)
                ->whereNull('student_class.deleted_at')
                ->where('student_class.status', 'active')
                ->select('classes.id as class_id', 'classes.name as class_name');

            if ($academicYear) {
                $studentClassQuery->where('student_class.academic_year', $academicYear);
            }
            if ($semesterType) {
                $studentClassQuery->where('student_class.semester', $semesterType);
            }

            $studentClass = $studentClassQuery->first();

            if (!$studentClass) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Siswa {$student->name} tidak memiliki kelas aktif"
                ], 404);
            }

            // Cek apakah siswa memiliki izin aktif yang menyebabkan tidak boleh absen
            $activePermission = StudentPermission::getActivePermission($student->id, $currentDate);
            if ($activePermission && in_array($activePermission->type, ['sakit', 'agenda'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Siswa {$student->name} memiliki izin aktif ({$activePermission->type_label}) hari ini, tidak dapat melakukan absensi."
                ], 422);
            }

            // Cek apakah siswa sudah absen hari ini
            $existingAttendance = Attendance::where([
                'student_id' => $student->id,
                'class_id' => $studentClass->class_id,
                'date' => $currentDate
            ])->first();

            if ($existingAttendance && $existingAttendance->check_in) {
                // Jika sudah check-in dan sudah check-out
                if ($existingAttendance->check_out) {
                    return response()->json([
                        'status' => 'info',
                        'message' => "Siswa {$student->name} sudah absen masuk dan pulang hari ini",
                        'data' => [
                            'student_name' => $student->name,
                            'class_name' => $studentClass->class_name,
                            'check_in_time' => $existingAttendance->check_in,
                            'check_in_status' => $existingAttendance->check_in_status,
                            'check_out_time' => $existingAttendance->check_out,
                            'check_out_status' => $existingAttendance->check_out_status
                        ]
                    ], 200);
                }

                // Prioritas 1: Cek apakah ada pelajaran aktif saat ini
                $currentTimeOnly = $currentTime->format('H:i:s');
                $activeScheduleQuery = Schedule::where('class_id', $studentClass->class_id)
                    ->where('day', $dayNameLower)
                    ->where('start_time', '<=', $currentTimeOnly)
                    ->where('end_time', '>=', $currentTimeOnly);

                if ($academicYear) {
                    $activeScheduleQuery->where('academic_year', $academicYear);
                }
                if (!empty($semesterTypeVariants)) {
                    $activeScheduleQuery->whereIn('semester', $semesterTypeVariants);
                }

                $activeSchedule = $activeScheduleQuery->first();

                if ($activeSchedule) {
                    // Ada pelajaran aktif → rekam lesson attendance jika belum ada
                    $existingLessonAttendance = LessonAttendance::where([
                        'student_id' => $student->id,
                        'class_id' => $studentClass->class_id,
                        'subject_id' => $activeSchedule->subject_id,
                        'date' => $currentDate
                    ])->first();

                    if ($existingLessonAttendance) {
                        return response()->json([
                            'status' => 'info',
                            'message' => "Siswa {$student->name} sudah tercatat hadir di pelajaran ini.",
                            'data' => [
                                'student_name' => $student->name,
                                'class_name' => $studentClass->class_name,
                                'check_in_time' => $existingAttendance->check_in,
                                'check_in_status' => $existingAttendance->check_in_status,
                                'lesson_already_recorded' => true,
                            ]
                        ], 200);
                    }

                    LessonAttendance::create([
                        'id' => Str::uuid(),
                        'student_id' => $student->id,
                        'class_id' => $studentClass->class_id,
                        'subject_id' => $activeSchedule->subject_id,
                        'date' => $currentDate,
                        'check_in' => $currentTimeOnly,
                        'check_in_status' => 'hadir',
                        'academic_year' => $academicYear ?? '2025/2026',
                        'semester' => $semesterType ?? 'ganjil',
                    ]);

                    $lessonSubject = \App\Models\Subject::find($activeSchedule->subject_id);

                    DB::commit();

                    // Kirim notifikasi WA ke orang tua: 1 notif pelajaran saja (sudah check-in sebelumnya)
                    $studentId = $student->id;
                    $attendanceId = $existingAttendance->id;
                    $lessonSubjectId = $activeSchedule->subject_id;
                    dispatch(function () use ($studentId, $attendanceId, $lessonSubjectId) {
                        try {
                            $student = Student::find($studentId);
                            $attendance = Attendance::find($attendanceId);
                            $lessonSubject = \App\Models\Subject::find($lessonSubjectId);
                            if ($student && $attendance && $lessonSubject) {
                                $wa = app(WhatsAppService::class);
                                $wa->sendLessonAttendanceNotification($student, $attendance, $lessonSubject, 'hadir');
                            }
                        } catch (\Exception $e) {
                            \Log::warning('WA notification failed after lesson attendance: ' . $e->getMessage());
                        }
                    })->afterResponse();

                    return response()->json([
                        'status' => 'success',
                        'message' => "Absensi pelajaran berhasil dicatat untuk {$student->name}" . ($lessonSubject ? " - {$lessonSubject->name}" : ""),
                        'data' => [
                            'student_name' => $student->name,
                            'class_name' => $studentClass->class_name,
                            'check_in_time' => $existingAttendance->check_in,
                            'check_in_status' => $existingAttendance->check_in_status,
                            'lesson_attendance_created' => true,
                            'subject_name' => $lessonSubject->name ?? null,
                        ]
                    ], 200);
                }

                // Prioritas 2: Tidak ada pelajaran aktif → cek apakah sudah waktunya checkout
                $schedule = SettingSchedule::where('day', $dayName)->first();
                if ($schedule) {
                    $scheduleEndCarbon = Carbon::createFromTimeString($schedule->end_time, 'Asia/Jakarta');
                    if ($currentTime->lt($scheduleEndCarbon)) {
                        return response()->json([
                            'status' => 'info',
                            'message' => "Siswa {$student->name} belum bisa absen pulang. Jam pelajaran berakhir pukul " . $scheduleEndCarbon->format('H:i') . ".",
                            'data' => [
                                'student_name' => $student->name,
                                'class_name' => $studentClass->class_name,
                                'check_in_time' => $existingAttendance->check_in,
                                'check_in_status' => $existingAttendance->check_in_status,
                            ]
                        ], 200);
                    }
                } else {
                    // Fallback jika tidak ada setting_schedule: minimal 1 jam setelah check-in
                    $checkInCarbon = Carbon::parse($existingAttendance->check_in, 'Asia/Jakarta');
                    $minCheckOutTime = $checkInCarbon->copy()->addHour();
                    if ($currentTime->lt($minCheckOutTime)) {
                        return response()->json([
                            'status' => 'info',
                            'message' => "Siswa {$student->name} sudah absen masuk pukul {$existingAttendance->check_in}. Absen pulang dapat dilakukan setelah pukul " . $minCheckOutTime->format('H:i') . ".",
                            'data' => [
                                'student_name' => $student->name,
                                'class_name' => $studentClass->class_name,
                                'check_in_time' => $existingAttendance->check_in,
                                'check_in_status' => $existingAttendance->check_in_status,
                            ]
                        ], 200);
                    }
                }

                // Proses checkout
                $checkOutTime = $currentTime->format('H:i');
                $checkOutStatus = 'tepat';
                if ($activePermission) {
                    $checkOutStatus = $activePermission->checkout_status;
                } elseif ($schedule) {
                    $scheduleEndTime = Carbon::parse($schedule->end_time);
                    if ($currentTime->lt($scheduleEndTime)) {
                        $checkOutStatus = 'lebih_awal';
                    } else {
                        $checkOutStatus = 'tepat';
                    }
                }

                $existingAttendance->update([
                    'check_out' => $checkOutTime,
                    'check_out_status' => $checkOutStatus,
                    'updated_by' => Auth::id() ?? null,
                ]);

                DB::commit();

                // Kirim notifikasi WA check-out ke orang tua
                $studentId = $student->id;
                $attendanceId = $existingAttendance->id;
                dispatch(function () use ($studentId, $attendanceId) {
                    try {
                        $student = Student::find($studentId);
                        $attendance = Attendance::find($attendanceId);
                        if ($student && $attendance) {
                            $wa = app(WhatsAppService::class);
                            $wa->sendCheckOutNotification($student, $attendance);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('WA notification failed after RFID checkout: ' . $e->getMessage());
                    }
                })->afterResponse();

                return response()->json([
                    'status' => 'success',
                    'message' => "Siswa {$student->name} berhasil absen pulang pada pukul {$checkOutTime} dengan status {$checkOutStatus}",
                    'data' => [
                        'student_name' => $student->name,
                        'class_name' => $studentClass->class_name,
                        'check_in_time' => $existingAttendance->check_in,
                        'check_in_status' => $existingAttendance->check_in_status,
                        'check_out_time' => $checkOutTime,
                        'check_out_status' => $checkOutStatus,
                        'permission_type' => $activePermission->type ?? null
                    ]
                ], 200);
            }

            // Cek jadwal untuk hari ini
            $schedule = SettingSchedule::where('day', $dayName)->first();
            
            // Tentukan status check-in berdasarkan waktu
            $checkInStatus = 'tepat';
            $checkInTime = $currentTime->format('H:i');
            
            if ($schedule) {
                $scheduleStartCarbon = Carbon::createFromTimeString($schedule->start_time, 'Asia/Jakarta');
                $scheduleEndCarbon   = Carbon::createFromTimeString($schedule->end_time, 'Asia/Jakarta');

                // Absensi dibuka 1 jam sebelum start_time
                $openTime = $scheduleStartCarbon->copy()->subHour();

                // Belum buka
                if ($currentTime->lt($openTime)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Belum waktu absensi. Absensi dibuka mulai pukul " . $openTime->format('H:i') . ". Waktu sekarang: " . $checkInTime,
                    ], 422);
                }

                // Jam pelajaran sudah selesai
                if ($currentTime->gte($scheduleEndCarbon)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Tidak dapat absen masuk. Jam pelajaran sudah selesai pukul " . $scheduleEndCarbon->format('H:i') . ".",
                    ], 422);
                }

                // Terlambat jika setelah start_time
                $checkInStatus = $currentTime->gt($scheduleStartCarbon) ? 'terlambat' : 'tepat';

            } else {
                // Fallback jika tidak ada schedule: buka 06:00, terlambat setelah 07:00
                $currentTimeInMinutes = ($currentTime->format('H') * 60) + $currentTime->format('i');

                if ($currentTimeInMinutes < 360) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Belum waktu absensi. Absensi dibuka mulai pukul 06:00. Waktu sekarang: " . $checkInTime,
                    ], 422);
                }

                $checkInStatus = $currentTimeInMinutes >= 420 ? 'terlambat' : 'tepat';
            }

            // Buat absensi baru
            $attendance = Attendance::create([
                'id' => Str::uuid(),
                'student_id' => $student->id,
                'class_id' => $studentClass->class_id,
                'date' => $currentDate,
                'check_in' => $checkInTime,
                'check_in_status' => $checkInStatus,
                'created_by' => Auth::id() ?? null, // null jika auto oleh sistem
            ]);

            // Cek apakah ada pelajaran yang sedang berjalan pada jam tersebut
            $currentTimeOnly = $currentTime->format('H:i:s');
            $currentScheduleQuery = Schedule::where('class_id', $studentClass->class_id)
                ->where('day', $dayNameLower)
                ->where('start_time', '<=', $currentTimeOnly)
                ->where('end_time', '>=', $currentTimeOnly);

            if ($academicYear) {
                $currentScheduleQuery->where('academic_year', $academicYear);
            }
            if (!empty($semesterTypeVariants)) {
                $currentScheduleQuery->whereIn('semester', $semesterTypeVariants);
            }

            $currentSchedule = $currentScheduleQuery->first();

            \Log::info('RFID check-in schedule debug', [
                'class_id'   => $studentClass->class_id,
                'day'        => $dayNameLower,
                'time'       => $currentTimeOnly,
                'academicYear' => $academicYear,
                'semesterVariants' => $semesterTypeVariants,
                'schedule_found' => $currentSchedule ? $currentSchedule->id : null,
            ]);

            $lessonAttendanceCreated = false;
            if ($currentSchedule) {
                // Cek apakah siswa sudah absen pelajaran ini hari ini
                $existingLessonAttendance = LessonAttendance::where([
                    'student_id' => $student->id,
                    'class_id' => $studentClass->class_id,
                    'subject_id' => $currentSchedule->subject_id,
                    'date' => $currentDate
                ])->first();

                if (!$existingLessonAttendance) {
                    // Tentukan status check-in pelajaran berdasarkan waktu berakhir pelajaran
                    $scheduleEndTime = Carbon::parse($currentSchedule->end_time);
                    $checkInTimeCarbon = Carbon::parse($currentTimeOnly);
                    
                    // Selama pelajaran masih berjalan (belum lewat end_time), dianggap hadir
                    $lessonCheckInStatus = $checkInTimeCarbon->gt($scheduleEndTime) ? 'terlambat' : 'hadir';

                    // Buat absensi pelajaran
                    LessonAttendance::create([
                        'id' => Str::uuid(),
                        'student_id' => $student->id,
                        'class_id' => $studentClass->class_id,
                        'subject_id' => $currentSchedule->subject_id,
                        'date' => $currentDate,
                        'check_in' => $currentTimeOnly,
                        'check_in_status' => $lessonCheckInStatus,
                        'academic_year' => $academicYear ?? '2025/2026',
                        'semester' => $semesterType ?? 'ganjil',
                    ]);

                    $lessonAttendanceCreated = true;
                }
            }

            DB::commit();

            // Kirim notifikasi WhatsApp ke orang tua (async, tidak blok response ESP)
            $studentId = $student->id;
            $attendanceId = $attendance->id;
            $status = $checkInStatus;
            $lessonSubjectId = $lessonAttendanceCreated && $currentSchedule ? $currentSchedule->subject_id : null;
            dispatch(function () use ($studentId, $attendanceId, $status, $lessonSubjectId) {
                try {
                    $student = Student::find($studentId);
                    $attendance = Attendance::find($attendanceId);
                    if ($student && $attendance) {
                        $wa = app(WhatsAppService::class);
                        // Notif 1: kehadiran
                        if ($status === 'terlambat') {
                            $wa->sendLateNotification($student, $attendance);
                        } else {
                            $wa->sendAttendanceNotification($student, $attendance);
                        }
                        // Notif 2: pelajaran (jika ada)
                        if ($lessonSubjectId) {
                            $lessonSubject = \App\Models\Subject::find($lessonSubjectId);
                            if ($lessonSubject) {
                                $lessonStatus = $status === 'terlambat' ? 'terlambat' : 'hadir';
                                $wa->sendLessonAttendanceNotification($student, $attendance, $lessonSubject, $lessonStatus);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('WA notification failed after RFID attendance: ' . $e->getMessage());
                }
            })->afterResponse();

            return response()->json([
                'status' => 'success',
                'message' => "Absensi berhasil dibuat untuk {$student->name} - {$studentClass->class_name}" . ($lessonAttendanceCreated ? " (termasuk absensi pelajaran)" : ""),
                'data' => [
                    'attendance_id' => $attendance->id,
                    'student_name' => $student->name,
                    'nisn' => $student->nisn,
                    'class_name' => $studentClass->class_name,
                    'check_in_time' => $checkInTime,
                    'check_in_status' => $checkInStatus,
                    'date' => $currentDate,
                    'status_text' => $checkInStatus === 'tepat' ? 'Tepat Waktu' : 'Terlambat',
                    'lesson_attendance_created' => $lessonAttendanceCreated,
                    'current_schedule' => $currentSchedule ? [
                        'subject_id' => $currentSchedule->subject_id,
                        'start_time' => $currentSchedule->start_time,
                        'end_time' => $currentSchedule->end_time,
                    ] : null
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Auto Attendance Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat absensi otomatis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function untuk mengubah nama hari dalam bahasa Indonesia
     */
    private function convertDayToIndonesian($day)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $days[$day] ?? $day;
    }
}