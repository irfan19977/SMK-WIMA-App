<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LessonAttendanceController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('lesson_attendances.index');
        
        $query = $request->get('q');
        $user = Auth::user();
        
        // Query untuk data lesson attendance
        $lessonAttendancesQuery = DB::table('lesson_attendance as la')
            ->select([
                'la.id as lesson_attendance_id',
                'la.student_id',
                'la.class_id',
                'la.subject_id', 
                'la.date',
                's.name as student_name',
                'c.name as class_name',
                'sub.name as subject_name',
                'la.check_in',
                'la.check_in_status'
            ])
            ->join('student as s', 'la.student_id', '=', 's.id')
            ->join('classes as c', 'la.class_id', '=', 'c.id')
            ->join('subject as sub', 'la.subject_id', '=', 'sub.id')
            ->whereNull('la.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('c.deleted_at')
            ->whereNull('sub.deleted_at');
        
        // Jika user adalah parent, batasi hanya data anak mereka
        if ($user->hasRole('parent')) {
            $parent = DB::table('parent')
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();
            
            if ($parent && $parent->student_id) {
                $lessonAttendancesQuery->where('la.student_id', $parent->student_id);
            } else {
                // Jika parent tidak memiliki student_id, return empty collection
                $lessonAttendances = collect();
            }
            
            // Parent tidak bisa melakukan pencarian
            $query = null;
        }
        
        // Jika bukan parent, bisa melakukan pencarian
        if (!$user->hasRole('parent') && $query) {
            $lessonAttendancesQuery->where(function($subQuery) use ($query) {
                $subQuery->where('s.name', 'LIKE', "%{$query}%")
                        ->orWhere('c.name', 'LIKE', "%{$query}%")
                        ->orWhere('sub.name', 'LIKE', "%{$query}%")
                        ->orWhere('la.date', 'LIKE', "%{$query}%");
            });
        }
        
        $lessonAttendances = isset($lessonAttendances) ? $lessonAttendances : $lessonAttendancesQuery
            ->orderBy('la.date', 'desc')
            ->orderBy('la.check_in', 'asc')
            ->get();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'lesson_attendances' => $lessonAttendances
            ]);
        }
        
        return view('lesson_attendance.index', compact('lessonAttendances'));
    }

    public function create()
    {
        $students = Student::all();
        $classes = Classes::all();
        $subjects = Subject::all();
        
        return response()->json([
            'success' => true,
            'title' => 'Tambah Data Absensi Pelajaran',
            'students' => $students,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_in_status' => 'nullable|in:tepat,terlambat,izin,sakit,alpha'
        ]);

        try {
            DB::beginTransaction();

            // ===== VALIDASI BARU: Cek apakah siswa sudah absensi kedatangan =====
            $attendanceCheck = DB::table('attendance')
                ->where('student_id', $request->student_id)
                ->where('class_id', $request->class_id)
                ->where('date', $request->date)
                ->whereNull('deleted_at')
                ->first();

            if (!$attendanceCheck) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum melakukan absensi kedatangan hari ini. Harap lakukan absensi kedatangan terlebih dahulu sebelum absensi pelajaran.'
                ]);
            }

            // Validasi tambahan: Pastikan siswa sudah check_in (tidak hanya tercatat tapi belum datang)
            if (!$attendanceCheck->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum melakukan check-in kedatangan. Harap lakukan check-in terlebih dahulu.'
                ]);
            }

            // Validasi status kedatangan - jika alpha, tidak bisa absen pelajaran
            if ($attendanceCheck->check_in_status === 'alpha') {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tercatat alpha pada absensi kedatangan. Tidak dapat melakukan absensi pelajaran.'
                ]);
            }
            // ===== END VALIDASI BARU =====

            // Cek apakah sudah ada data untuk student, class, subject, dan date yang sama
            $existingLessonAttendance = Lesson::where([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date
            ])->first();

            if ($existingLessonAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa sudah melakukan absen untuk pelajaran ini pada tanggal tersebut'
                ]);
            }

            // Buat record lesson attendance baru
            $lessonAttendanceData = [
                'id' => Str::uuid(),
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
                'check_in' => $request->check_in,
                'check_in_status' => $request->check_in_status ?? 'hadir',
                'created_by' => Auth::id(),
            ];

            // Buat record lesson attendance baru
            Lesson::create($lessonAttendanceData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi pelajaran berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        // Detail absensi pelajaran
        $lessonAttendance = DB::table('lesson_attendance as la')
            ->select([
                'la.student_id',
                'la.class_id',
                'la.subject_id', 
                'la.date',
                's.name as student_name',
                'c.name as class_name',
                'sub.name as subject_name',
                'la.check_in',
                'la.check_in_status'
            ])
            ->join('student as s', 'la.student_id', '=', 's.id')
            ->join('classes as c', 'la.class_id', '=', 'c.id')
            ->join('subject as sub', 'la.subject_id', '=', 'sub.id')
            ->where('la.id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'lesson_attendance' => $lessonAttendance
        ]);
    }

    public function edit($id)
    {
        // Ambil data berdasarkan lesson attendance ID
        $lessonAttendance = Lesson::find($id);
        
        if (!$lessonAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $students = Student::all();
        $classes = Classes::all();
        $subjects = Subject::all();

        return response()->json([
            'success' => true,
            'title' => 'Edit Data Absensi Pelajaran',
            'lesson_attendance' => [
                'student_id' => $lessonAttendance->student_id,
                'class_id' => $lessonAttendance->class_id,
                'subject_id' => $lessonAttendance->subject_id,
                'date' => $lessonAttendance->date,
                'check_in' => $lessonAttendance->check_in,
                'check_in_status' => $lessonAttendance->check_in_status ?? 'tepat'
            ],
            'students' => $students,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_in_status' => 'nullable|in:tepat,terlambat,izin,sakit,alpha'
        ]);

        try {
            DB::beginTransaction();

            $lessonAttendance = Lesson::find($id);
            
            if (!$lessonAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            // Update data
            $lessonAttendance->update([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
                'check_in' => $request->check_in,
                'check_in_status' => $request->check_in_status,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi pelajaran berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $lessonAttendance = Lesson::find($id);
            if (!$lessonAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            $lessonAttendance->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi pelajaran berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function findByNisn($nisn) 
    {
        try {
            // Get current time and day using Carbon
            $now = Carbon::now('Asia/Jakarta');
            $currentTime = $now->format('H:i:s');
            $currentDay = $now->format('l');
            $currentDate = $now->format('Y-m-d');

            // Day mapping - sesuai dengan enum di database
            $dayMap = [
                'Monday' => 'senin',
                'Tuesday' => 'selasa',
                'Wednesday' => 'rabu',
                'Thursday' => 'kamis',
                'Friday' => 'jumat',
                'Saturday' => 'sabtu',
                'Sunday' => 'minggu' // jika diperlukan
            ];
            $indonesianDay = $dayMap[$currentDay] ?? null;
            
            if (!$indonesianDay) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hari ini tidak ada jadwal pelajaran'
                ], 404);
            }

            // Find student by NISN
            $student = DB::table('student')
                ->where('nisn', $nisn)
                ->whereNull('deleted_at')
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa dengan NISN tersebut tidak ditemukan'
                ], 404);
            }

            // Get student's class from pivot table student_class
            $studentClassRelation = DB::table('student_class')
                ->where('student_id', $student->id)
                ->whereNull('deleted_at')
                ->first();

            if (!$studentClassRelation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak terdaftar di kelas manapun'
                ], 404);
            }
            
            // Get class details
            $studentClass = DB::table('classes')
                ->where('id', $studentClassRelation->class_id)
                ->whereNull('deleted_at')
                ->first();
                
            if (!$studentClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas siswa tidak ditemukan'
                ], 404);
            }

            // ===== VALIDASI BARU: Cek apakah siswa sudah absensi kedatangan =====
            $attendanceCheck = DB::table('attendance')
                ->where('student_id', $student->id)
                ->where('class_id', $studentClass->id)
                ->where('date', $currentDate)
                ->whereNull('deleted_at')
                ->first();

            if (!$attendanceCheck) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum melakukan absensi kedatangan hari ini. Harap lakukan absensi kedatangan terlebih dahulu.'
                ], 400);
            }

            // Validasi tambahan: Pastikan siswa sudah check_in
            if (!$attendanceCheck->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum melakukan check-in kedatangan. Harap lakukan check-in terlebih dahulu.'
                ], 400);
            }

            // Validasi status kedatangan - jika alpha, tidak bisa absen pelajaran
            if ($attendanceCheck->check_in_status === 'alpha') {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tercatat alpha pada absensi kedatangan. Tidak dapat melakukan absensi pelajaran.'
                ], 400);
            }
            // ===== END VALIDASI BARU =====

            // Find current schedule for the student's class on current day
            $currentSchedule = DB::table('schedule as sch')
                ->select([
                    'sch.*',
                    's.name as subject_name',
                    't.name as teacher_name'
                ])
                ->join('subject as s', 'sch.subject_id', '=', 's.id')
                ->join('teacher as t', 'sch.teacher_id', '=', 't.id')
                ->where('sch.class_id', $studentClass->id)
                ->where('sch.day', $indonesianDay)
                ->where('sch.start_time', '<=', $currentTime)
                ->where('sch.end_time', '>=', $currentTime)
                ->whereNull('sch.deleted_at')
                ->whereNull('s.deleted_at')
                ->whereNull('t.deleted_at')
                ->first();

            if (!$currentSchedule) {
                // Coba cari jadwal terdekat untuk hari ini (untuk debugging)
                $allSchedulesToday = DB::table('schedule as sch')
                    ->select(['sch.start_time', 'sch.end_time', 's.name as subject_name'])
                    ->join('subject as s', 'sch.subject_id', '=', 's.id')
                    ->where('sch.class_id', $studentClass->id)
                    ->where('sch.day', $indonesianDay)
                    ->whereNull('sch.deleted_at')
                    ->whereNull('s.deleted_at')
                    ->orderBy('sch.start_time')
                    ->get();
                    
                $message = 'Tidak ada jadwal pelajaran yang sedang berlangsung saat ini';
                if ($allSchedulesToday->isNotEmpty()) {
                    $scheduleList = $allSchedulesToday->map(function($item) {
                        return $item->subject_name . ' (' . $item->start_time . ' - ' . $item->end_time . ')';
                    })->implode(', ');
                    $message .= '. Jadwal hari ini: ' . $scheduleList;
                } else {
                    $message .= '. Tidak ada jadwal untuk hari ' . ucfirst($indonesianDay);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'debug_info' => [
                        'current_time' => $currentTime,
                        'current_day' => $indonesianDay,
                        'class_id' => $studentClass->id,
                        'class_name' => $studentClass->name,
                        'schedules_today' => $allSchedulesToday
                    ]
                ], 404);
            }

            // Check if student has already attended this subject today
            $existingAttendance = DB::table('lesson_attendance')
                ->where('student_id', $student->id)
                ->where('subject_id', $currentSchedule->subject_id)
                ->where('class_id', $studentClass->id)
                ->whereDate('date', $currentDate)
                ->whereNull('deleted_at')
                ->exists();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa sudah melakukan absensi untuk mata pelajaran ' . $currentSchedule->subject_name . ' hari ini'
                ], 400);
            }

            // Return student and schedule data with proper structure for frontend
            return response()->json([
                'success' => true,
                'message' => 'Siswa ditemukan dan sudah melakukan absensi kedatangan',
                'data' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'nisn' => $student->nisn,
                    'class_id' => $studentClass->id,
                    'class_name' => $studentClass->name,
                    'subject_id' => $currentSchedule->subject_id,
                    'subject_name' => $currentSchedule->subject_name,
                    'teacher_id' => $currentSchedule->teacher_id,
                    'teacher_name' => $currentSchedule->teacher_name ?? 'Tidak ada guru',
                    'current_schedule' => [
                        'start_time' => $currentSchedule->start_time,
                        'end_time' => $currentSchedule->end_time,
                        'day' => $indonesianDay
                    ],
                    'attendance_info' => [
                        'check_in_time' => $attendanceCheck->check_in,
                        'check_in_status' => $attendanceCheck->check_in_status
                    ]
                ]
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error in findByNisn: ' . $e->getMessage(), [
                'nisn' => $nisn,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subjects by class ID
     */
    public function getSubjectsByClass($classId)
    {
        try {
            $subjects = DB::table('subject_schedule')
                ->join('subject', 'subject_schedule.subject_id', '=', 'subject.id')
                ->where('subject_schedule.class_id', $classId)
                ->whereNull('subject.deleted_at')
                ->select('subject.id', 'subject.name')
                ->distinct()
                ->get();

            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}