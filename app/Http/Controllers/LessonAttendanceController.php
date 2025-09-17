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
    
    public function index()
    {
        $user = Auth::user();
        
        // Get classes based on user role
        if ($user->hasRole('teacher')) {
            // For teachers, only show classes they teach
            $classes = Classes::whereHas('schedules', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->get();
        } else {
            // For admin and other roles, show all classes
            $classes = Classes::all();
        }

        // Get all subjects (will be filtered by AJAX based on class selection)
        $subjects = Subject::all();

        return view('lesson_attendance.index', compact('classes', 'subjects'));
    }

    public function getSubjectsByClass(Request $request)
    {
        try {
            $classId = $request->class_id;
            $academicYear = $request->academic_year;
            $user = Auth::user();

            $query = Subject::query();

            if ($user->hasRole('teacher')) {
                // For teachers, only show subjects they teach for this class
                $query->whereHas('schedules', function ($q) use ($classId, $user, $academicYear) {
                    $q->where('class_id', $classId)
                      ->where('teacher_id', $user->id);
                    if ($academicYear) {
                        $q->where('academic_year', $academicYear);
                    }
                });
            } else {
                // For admin, show all subjects for the class
                $query->whereHas('schedules', function ($q) use ($classId, $academicYear) {
                    $q->where('class_id', $classId);
                    if ($academicYear) {
                        $q->where('academic_year', $academicYear);
                    }
                });
            }

            $subjects = $query->get();

            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudents(Request $request)
    {
        try {
            $classId = $request->class_id;
            $academicYear = $request->academic_year;

            // Fixed: Use relationship through student_class pivot table
            $students = Student::whereHas('classes', function ($query) use ($classId, $academicYear) {
                $query->where('classes.id', $classId)
                      ->where('student_class.status', 'active');
                if ($academicYear) {
                    $query->where('student_class.academic_year', $academicYear);
                }
            })->orderBy('no_absen')->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAttendance(Request $request)
    {
        try {
            $classId = $request->class_id;
            $subjectId = $request->subject_id;
            $date = $request->date;
            $academicYear = $request->academic_year;

            // Fixed: Use relationship through student_class pivot table
            $students = Student::whereHas('classes', function ($query) use ($classId, $academicYear) {
                $query->where('classes.id', $classId)
                      ->where('student_class.status', 'active');
                if ($academicYear) {
                    $query->where('student_class.academic_year', $academicYear);
                }
            })
            ->with(['lessonAttendances' => function ($query) use ($subjectId, $date, $classId) {
                $query->where('subject_id', $subjectId)
                      ->where('class_id', $classId)
                      ->where('date', $date);
            }])
            ->orderBy('no_absen')
            ->get();

            // Format the data
            $attendanceData = $students->map(function ($student) use ($classId, $subjectId, $date, $academicYear) {
                $attendance = $student->lessonAttendances->first();
                
                return [
                    'id' => $attendance->id ?? null,
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'student_nisn' => $student->nisn,
                    'no_absen' => $student->no_absen,
                    'class_id' => $classId,
                    'subject_id' => $subjectId,
                    'date' => $date,
                    'check_in' => $attendance->check_in ?? null,
                    'check_in_status' => $attendance->check_in_status ?? 'alpha',
                    'academic_year' => $academicYear,
                    'semester' => $this->getSemester($date)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $attendanceData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'date' => 'required|date',
            'check_in_status' => 'required|in:hadir,terlambat,izin,sakit,alpha',
            'check_in' => 'nullable|date_format:H:i',
            'academic_year' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // Check if attendance already exists
            $existingAttendance = Lesson::where([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date
            ])->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi untuk siswa ini sudah ada'
                ], 422);
            }

            $attendance = Lesson::create([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
                'check_in' => $request->check_in,
                'check_in_status' => $request->check_in_status,
                'academic_year' => $request->academic_year,
                'semester' => $this->getSemester($request->date),
                'created_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil disimpan',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'check_in_status' => 'required|in:hadir,terlambat,izin,sakit,alpha',
            'check_in' => 'nullable|date_format:H:i'
        ]);

        try {
            DB::beginTransaction();

            $attendance = Lesson::findOrFail($id);
            
            $attendance->update([
                'check_in' => $request->check_in,
                'check_in_status' => $request->check_in_status,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diperbarui',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $attendance = Lesson::findOrFail($id);
            $attendance->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:student,id',
            'attendances.*.class_id' => 'required|exists:classes,id',
            'attendances.*.subject_id' => 'required|exists:subject,id',
            'attendances.*.date' => 'required|date',
            'attendances.*.check_in_status' => 'required|in:hadir,terlambat,izin,sakit,alpha',
            'attendances.*.check_in' => 'nullable|date_format:H:i',
            'attendances.*.academic_year' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $savedCount = 0;
            $updatedCount = 0;

            foreach ($request->attendances as $attendanceData) {
                // Check if attendance already exists
                $existingAttendance = Lesson::where([
                    'student_id' => $attendanceData['student_id'],
                    'class_id' => $attendanceData['class_id'],
                    'subject_id' => $attendanceData['subject_id'],
                    'date' => $attendanceData['date']
                ])->first();

                if ($existingAttendance) {
                    // Update existing attendance
                    $existingAttendance->update([
                        'check_in' => $attendanceData['check_in'] ?? null,
                        'check_in_status' => $attendanceData['check_in_status'],
                        'updated_by' => Auth::id()
                    ]);
                    $updatedCount++;
                } else {
                    // Create new attendance
                    Lesson::create([
                        'student_id' => $attendanceData['student_id'],
                        'class_id' => $attendanceData['class_id'],
                        'subject_id' => $attendanceData['subject_id'],
                        'date' => $attendanceData['date'],
                        'check_in' => $attendanceData['check_in'] ?? null,
                        'check_in_status' => $attendanceData['check_in_status'],
                        'academic_year' => $attendanceData['academic_year'],
                        'semester' => $this->getSemester($attendanceData['date']),
                        'created_by' => Auth::id()
                    ]);
                    $savedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyimpan {$savedCount} data baru dan memperbarui {$updatedCount} data absensi"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSemester($date)
    {
        $month = Carbon::parse($date)->month;
        return ($month >= 7 && $month <= 12) ? 'ganjil' : 'genap';
    }

}