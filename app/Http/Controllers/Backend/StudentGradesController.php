<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentGrades;
use App\Models\Subject;
use App\Helpers\AcademicYearHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentGradesController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('student-grades.index');
        $user = Auth::user();
        
        // Jika user adalah teacher, ambil kelas dan mapel sesuai schedule
        if ($user->hasRole('teacher')) {
            // Ambil teacher_id dari user (asumsi ada relasi user->teacher)
            $teacherId = $user->teacher->id ?? null;
            
            if (!$teacherId) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            
            // Ambil kelas yang diajar oleh teacher ini
            $classes = Classes::select('classes.*')
                ->join('schedule', 'classes.id', '=', 'schedule.class_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('classes.name')
                ->get();
            
            // Ambil mata pelajaran yang diajar oleh teacher ini
            $subjects = Subject::select('subject.*')
                ->join('schedule', 'subject.id', '=', 'schedule.subject_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('subject.name')
                ->get();
                
        } else {
            // Jika bukan teacher, tampilkan semua kelas dan mapel
            $classes = Classes::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();
        }
        
        return view('grades.index', compact('classes', 'subjects'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $grade = StudentGrades::with(['student', 'class', 'subject'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $grade
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Grade not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getSubjectsByClass(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string|exists:classes,id',
                'academic_year' => 'nullable|string',
                'semester' => 'nullable|in:ganjil,genap'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();

            $user = Auth::user();

            // Query mata pelajaran yang dijadwalkan untuk kelas tertentu
            $query = DB::table('subject')
                ->select([
                    'subject.id',
                    'subject.name',
                    'subject.code'
                ])
                ->join('schedule', 'subject.id', '=', 'schedule.subject_id')
                ->where('schedule.class_id', $request->class_id)
                ->where('schedule.academic_year', $academicYear)
                ->whereNull('subject.deleted_at')
                ->whereNull('schedule.deleted_at');

            // Jika user adalah teacher, filter berdasarkan teacher_id
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                $query->where('schedule.teacher_id', $teacherId);
            }

            // Jika semester ditentukan, batasi ke mapel yang punya entri nilai di semester tsb (opsional)
            if ($request->filled('semester')) {
                $semester = $request->semester;
                $classId = $request->class_id;
                $query->whereExists(function($q) use ($semester, $classId, $academicYear) {
                    $q->from('student_grades as sg')
                      ->whereRaw('sg.subject_id = subject.id')
                      ->where('sg.class_id', $classId)
                      ->where('sg.academic_year', $academicYear)
                      ->where('sg.semester', $semester)
                      ->whereNull('sg.deleted_at');
                });
            }

            $subjects = $query->distinct()
                ->orderBy('subject.name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'count' => $subjects->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getSubjectsByClass: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active students in a class
     */
    public function getStudents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string|exists:classes,id',
                'academic_year' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            
            // Jika user adalah teacher, validasi apakah teacher ini mengajar di kelas tersebut
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                // Cek apakah teacher ini mengajar di kelas yang diminta
                $hasAccess = DB::table('schedule')
                    ->where('class_id', $request->class_id)
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', $request->academic_year)
                    ->whereNull('deleted_at')
                    ->exists();
                
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke kelas ini'
                    ], 403);
                }
            }

            // Query dengan struktur database yang benar dan sorting yang tepat
            $students = DB::table('student')
                ->select([
                    'student.id',
                    'student.name',
                    'student.nisn',
                    'student.no_absen'
                ])
                ->join('student_class', 'student.id', '=', 'student_class.student_id')
                ->where('student_class.class_id', $request->class_id)
                ->where('student_class.academic_year', $request->academic_year)
                ->where('student_class.status', 'active')
                ->whereNull('student.deleted_at')
                ->whereNull('student_class.deleted_at')
                // Perbaikan sorting: Cast no_absen sebagai integer untuk urutan numerik yang benar
                ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
                ->orderBy('student.name') // Fallback sorting jika no_absen sama atau NULL
                ->get();

            return response()->json([
                'success' => true,
                'data' => $students,
                'count' => $students->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getStudents: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateAttendancePercentage($studentId, $classId, $subjectId, $month, $academicYear)
    {
        try {
            // Hitung total hari dalam bulan yang dipilih
            $year = (int) substr($academicYear, 0, 4);
            $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            // Ambil semua attendance record untuk siswa di bulan tersebut
            $attendanceRecords = DB::table('lesson_attendance')
                ->where('student_id', $studentId)
                ->where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->where('academic_year', $academicYear)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->whereNull('deleted_at')
                ->get();
            
            if ($attendanceRecords->isEmpty()) {
                return null; // Tidak ada data attendance
            }
            
            $totalAttendance = $attendanceRecords->count();
            $presentCount = $attendanceRecords->whereIn('check_in_status', ['hadir', 'terlambat'])->count();
            
            // Hitung persentase kehadiran
            $attendancePercentage = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;
            
            return round($attendancePercentage, 2);
            
        } catch (\Exception $e) {
            \Log::error('Error calculating attendance percentage: ' . $e->getMessage());
            return null;
        }
    }

    // Di method getGrades() - line sekitar 174  
    public function getGrades(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string|exists:classes,id',
                'subject_id' => 'required|string|exists:subject,id',
                'academic_year' => 'nullable|string',
                'assessment_type' => 'nullable|in:bulanan,uts,uas',
                'month' => 'nullable|integer|min:1|max:12',
                'semester' => 'nullable|in:ganjil,genap'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();
            $assessmentType = $request->input('assessment_type') ?: 'bulanan';
            $requestMonth = $request->input('month');
            $requestSemester = $request->input('semester');

            if ($assessmentType === 'bulanan') {
                if (!$requestMonth) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Month is required for bulanan assessment'
                    ], 422);
                }
            } else {
                if (!$requestSemester) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Semester is required for UTS/UAS assessment'
                    ], 422);
                }
            }
            $user = Auth::user();
            
            // Validasi akses teacher (kode existing...)
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                $hasAccess = DB::table('schedule')
                    ->where('class_id', $request->class_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', $academicYear)
                    ->whereNull('deleted_at')
                    ->exists();
                
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke mata pelajaran ini di kelas tersebut'
                    ], 403);
                }
            }

            // Get students dengan sorting yang benar
            $students = DB::table('student')
                ->select([
                    'student.id as student_id',
                    'student.name as student_name',
                    'student.nisn as student_nisn',
                    'student.no_absen'
                ])
                ->join('student_class', 'student.id', '=', 'student_class.student_id')
                ->where('student_class.class_id', $request->class_id)
                ->where('student_class.academic_year', $academicYear)
                ->where('student_class.status', 'active')
                ->whereNull('student.deleted_at')
                ->whereNull('student_class.deleted_at')
                ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
                ->orderBy('student.name')
                ->get();

            // Get existing grades
            $existingQuery = StudentGrades::where('class_id', $request->class_id)
                ->where('subject_id', $request->subject_id)
                ->where('academic_year', $academicYear)
                ->where('assessment_type', $assessmentType)
                ->whereNull('deleted_at');

            if ($assessmentType === 'bulanan') {
                $existingQuery->where('month', $requestMonth);
            } else {
                $existingQuery->where('semester', $requestSemester);
            }

            $existingGrades = $existingQuery->get()->keyBy('student_id');

            // Combine student data with grades and attendance
            $gradesData = $students->map(function ($student) use ($existingGrades, $request, $academicYear, $assessmentType) {
                $grade = $existingGrades->get($student->student_id);
                
                if ($assessmentType === 'bulanan') {
                    // Hitung attendance percentage untuk nilai aktif
                    $attendancePercentage = $this->calculateAttendancePercentage(
                        $student->student_id,
                        $request->class_id,
                        $request->subject_id,
                        $request->month,
                        $academicYear
                    );

                    return [
                        'id' => $grade->id ?? null,
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'student_nisn' => $student->student_nisn,
                        'no_absen' => $student->no_absen,
                        // map ke format lama untuk kompatibilitas tampilan saat ini
                        'h1' => null,
                        'h2' => null,
                        'h3' => null,
                        'k1' => null,
                        'k2' => null,
                        'k3' => null,
                        'ck' => null,
                        'p' => null,
                        'k' => null,
                        'aktif' => $attendancePercentage,
                        // nilai bulanan tidak dihitung di sini; biarkan null
                        'nilai' => null,
                        // kirimkan komponen baru juga untuk UI baru (opsional)
                        'tugas1' => $grade->tugas1 ?? null,
                        'tugas2' => $grade->tugas2 ?? null,
                        'sikap' => $grade->sikap ?? null,
                        'created_at' => $grade->created_at ?? null,
                        'updated_at' => $grade->updated_at ?? null,
                    ];
                } else {
                    // UTS/UAS: tampilkan 1 nilai di kolom Nilai
                    $examScore = $assessmentType === 'uts' ? ($grade->uts ?? null) : ($grade->uas ?? null);

                    return [
                        'id' => $grade->id ?? null,
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'student_nisn' => $student->student_nisn,
                        'no_absen' => $student->no_absen,
                        'h1' => null,
                        'h2' => null,
                        'h3' => null,
                        'k1' => null,
                        'k2' => null,
                        'k3' => null,
                        'ck' => null,
                        'p' => null,
                        'k' => null,
                        'aktif' => null,
                        'nilai' => $examScore,
                        'created_at' => $grade->created_at ?? null,
                        'updated_at' => $grade->updated_at ?? null,
                    ];
                }
            });

            return response()->json([
                'success' => true,
                'data' => $gradesData,
                'count' => $gradesData->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getGrades: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch grades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|string|exists:student,id',
                'class_id' => 'required|string|exists:classes,id',
                'subject_id' => 'required|string|exists:subject,id',
                'academic_year' => 'required|string',
                'semester' => 'nullable|in:ganjil,genap',
                'assessment_type' => 'nullable|in:bulanan,uts,uas',
                'month' => 'nullable|integer|min:1|max:12',
                'h1' => 'nullable|numeric|min:0|max:100',
                'h2' => 'nullable|numeric|min:0|max:100',
                'h3' => 'nullable|numeric|min:0|max:100',
                'k1' => 'nullable|numeric|min:0|max:100',
                'k2' => 'nullable|numeric|min:0|max:100',
                'k3' => 'nullable|numeric|min:0|max:100',
                'ck' => 'nullable|numeric|min:0|max:100',
                'p' => 'nullable|numeric|min:0|max:100',
                'k' => 'nullable|numeric|min:0|max:100',
                'aktif' => 'nullable|numeric|min:0|max:100',
                'nilai' => 'nullable|numeric|min:0|max:100',
                // New schema
                'tugas1' => 'nullable|numeric|min:0|max:100',
                'tugas2' => 'nullable|numeric|min:0|max:100',
                'sikap' => 'nullable|numeric|min:0|max:100',
                'uts' => 'nullable|numeric|min:0|max:100',
                'uas' => 'nullable|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            
            // Jika user adalah teacher, validasi akses ke kelas dan mata pelajaran
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                // Cek apakah teacher ini mengajar mata pelajaran di kelas tersebut
                $hasAccess = DB::table('schedule')
                    ->where('class_id', $request->class_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', $request->academic_year)
                    ->whereNull('deleted_at')
                    ->exists();
                
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk input nilai mata pelajaran ini di kelas tersebut'
                    ], 403);
                }
            }

            // Check if grade already exists based on assessment type
            $gradeQuery = StudentGrades::where([
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'academic_year' => $request->academic_year,
                'assessment_type' => $requestAssessmentType,
            ]);

            if ($requestAssessmentType === 'bulanan') {
                $gradeQuery->where('month', $requestMonth);
            } else { // uts/uas
                $gradeQuery->where('semester', $requestSemester);
            }

            $existingGrade = $gradeQuery->first();

            if ($existingGrade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade for this student, subject, and month already exists'
                ], 409);
            }

            DB::beginTransaction();

            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            // Prepare payload per assessment type
            $basePayload = [
                'id' => Str::uuid(),
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'academic_year' => $request->academic_year,
                'semester' => $requestAssessmentType === 'bulanan' ? ($requestSemester ?? AcademicYearHelper::getCurrentSemester()) : $requestSemester,
                'assessment_type' => $requestAssessmentType,
                'created_by' => Auth::id(),
            ];

            if ($requestAssessmentType === 'bulanan') {
                $payload = array_merge($basePayload, [
                    'month' => $requestMonth,
                    'month_name' => $monthNames[$requestMonth] ?? null,
                    'tugas1' => $request->tugas1,
                    'tugas2' => $request->tugas2,
                    'sikap' => $request->sikap,
                ]);
            } elseif ($requestAssessmentType === 'uts') {
                $payload = array_merge($basePayload, [
                    'month' => null,
                    'month_name' => null,
                    'uts' => $request->uts,
                ]);
            } else { // uas
                $payload = array_merge($basePayload, [
                    'month' => null,
                    'month_name' => null,
                    'uas' => $request->uas,
                ]);
            }

            $grade = StudentGrades::create($payload);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grade created successfully',
                'data' => $grade
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'h1' => 'nullable|numeric|min:0|max:100',
                'h2' => 'nullable|numeric|min:0|max:100',
                'h3' => 'nullable|numeric|min:0|max:100',
                'k1' => 'nullable|numeric|min:0|max:100',
                'k2' => 'nullable|numeric|min:0|max:100',
                'k3' => 'nullable|numeric|min:0|max:100',
                'ck' => 'nullable|numeric|min:0|max:100',
                'p' => 'nullable|numeric|min:0|max:100',
                'k' => 'nullable|numeric|min:0|max:100',
                'aktif' => 'nullable|numeric|min:0|max:100',
                'nilai' => 'nullable|numeric|min:0|max:100',
                // New schema
                'tugas1' => 'nullable|numeric|min:0|max:100',
                'tugas2' => 'nullable|numeric|min:0|max:100',
                'sikap' => 'nullable|numeric|min:0|max:100',
                'uts' => 'nullable|numeric|min:0|max:100',
                'uas' => 'nullable|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $grade = StudentGrades::findOrFail($id);
            $user = Auth::user();
            
            // Jika user adalah teacher, validasi akses ke grade tersebut
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                // Cek apakah teacher ini mengajar mata pelajaran di kelas tersebut
                $hasAccess = DB::table('schedule')
                    ->where('class_id', $grade->class_id)
                    ->where('subject_id', $grade->subject_id)
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', $grade->academic_year)
                    ->whereNull('deleted_at')
                    ->exists();
                
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk mengubah nilai ini'
                    ], 403);
                }
            }

            DB::beginTransaction();

            $grade->update([
                'h1' => $request->h1,
                'h2' => $request->h2,
                'h3' => $request->h3,
                'k1' => $request->k1,
                'k2' => $request->k2,
                'k3' => $request->k3,
                'ck' => $request->ck,
                'p' => $request->p,
                'k' => $request->k,
                'aktif' => $request->aktif,
                'nilai' => $request->nilai,
                // New schema
                'tugas1' => $request->tugas1,
                'tugas2' => $request->tugas2,
                'sikap' => $request->sikap,
                'uts' => $request->uts,
                'uas' => $request->uas,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $grade = StudentGrades::findOrFail($id);
            $user = Auth::user();
            
            // Jika user adalah teacher, validasi akses ke grade tersebut
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                // Cek apakah teacher ini mengajar mata pelajaran di kelas tersebut
                $hasAccess = DB::table('schedule')
                    ->where('class_id', $grade->class_id)
                    ->where('subject_id', $grade->subject_id)
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', $grade->academic_year)
                    ->whereNull('deleted_at')
                    ->exists();
                
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk menghapus nilai ini'
                    ], 403);
                }
            }

            DB::beginTransaction();

            $grade->update(['deleted_by' => Auth::id()]);
            $grade->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Jika user adalah teacher, ambil kelas dan mapel sesuai schedule
        if ($user->hasRole('teacher')) {
            $teacherId = $user->teacher->id ?? null;
            
            if (!$teacherId) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            
            $classes = Classes::select('classes.*')
                ->join('schedule', 'classes.id', '=', 'schedule.class_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('classes.name')
                ->get();
            
            $subjects = Subject::select('subject.*')
                ->join('schedule', 'subject.id', '=', 'schedule.subject_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('subject.name')
                ->get();
                
        } else {
            $classes = Classes::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();
        }
        
        return view('grades.create', compact('classes', 'subjects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $grade = StudentGrades::with(['student', 'class', 'subject'])->findOrFail($id);
        $user = Auth::user();
        
        // Jika user adalah teacher, validasi akses ke grade tersebut
        if ($user->hasRole('teacher')) {
            $teacherId = $user->teacher->id ?? null;
            
            if (!$teacherId) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }
            
            // Cek apakah teacher ini mengajar mata pelajaran di kelas tersebut
            $hasAccess = DB::table('schedule')
                ->where('class_id', $grade->class_id)
                ->where('subject_id', $grade->subject_id)
                ->where('teacher_id', $teacherId)
                ->where('academic_year', $grade->academic_year)
                ->whereNull('deleted_at')
                ->exists();
            
            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit nilai ini.');
            }
            
            $classes = Classes::select('classes.*')
                ->join('schedule', 'classes.id', '=', 'schedule.class_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('classes.name')
                ->get();
            
            $subjects = Subject::select('subject.*')
                ->join('schedule', 'subject.id', '=', 'schedule.subject_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('subject.name')
                ->get();
                
        } else {
            $classes = Classes::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();
        }
        
        return view('grades.edit', compact('grade', 'classes', 'subjects'));
    }

    /**
     * Bulk update grades
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'grades' => 'required|array',
                'grades.*.student_id' => 'required|string|exists:student,id',
                'grades.*.class_id' => 'required|string|exists:classes,id',
                'grades.*.subject_id' => 'required|string|exists:subject,id',
                'grades.*.academic_year' => 'required|string',
                'grades.*.semester' => 'nullable|in:ganjil,genap',
                'grades.*.assessment_type' => 'nullable|in:bulanan,uts,uas',
                'grades.*.month' => 'nullable|integer|min:1|max:12',
                'grades.*.h1' => 'nullable|numeric|min:0|max:100',
                'grades.*.h2' => 'nullable|numeric|min:0|max:100',
                'grades.*.h3' => 'nullable|numeric|min:0|max:100',
                'grades.*.k1' => 'nullable|numeric|min:0|max:100',
                'grades.*.k2' => 'nullable|numeric|min:0|max:100',
                'grades.*.k3' => 'nullable|numeric|min:0|max:100',
                'grades.*.ck' => 'nullable|numeric|min:0|max:100',
                'grades.*.aktif' => 'nullable|numeric|min:0|max:100',
                // Remove p, k, nilai from validation as they are calculated
                // New schema
                'grades.*.tugas1' => 'nullable|numeric|min:0|max:100',
                'grades.*.tugas2' => 'nullable|numeric|min:0|max:100',
                'grades.*.sikap' => 'nullable|numeric|min:0|max:100',
                'grades.*.uts' => 'nullable|numeric|min:0|max:100',
                'grades.*.uas' => 'nullable|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            
            // Teacher access validation
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                // Validate access for each class-subject combination
                $uniqueCombinations = collect($request->grades)
                    ->map(function($grade) {
                        return [
                            'class_id' => $grade['class_id'],
                            'subject_id' => $grade['subject_id'],
                            'academic_year' => $grade['academic_year']
                        ];
                    })
                    ->unique()
                    ->values();
                
                foreach ($uniqueCombinations as $combination) {
                    $hasAccess = DB::table('schedule')
                        ->where('class_id', $combination['class_id'])
                        ->where('subject_id', $combination['subject_id'])
                        ->where('teacher_id', $teacherId)
                        ->where('academic_year', $combination['academic_year'])
                        ->whereNull('deleted_at')
                        ->exists();
                    
                    if (!$hasAccess) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda tidak memiliki akses untuk mengubah nilai pada salah satu mata pelajaran/kelas'
                        ], 403);
                    }
                }
            }

            DB::beginTransaction();

            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $updated = 0;
            $created = 0;

            foreach ($request->grades as $gradeData) {
                $assessmentType = $gradeData['assessment_type'] ?? 'bulanan';

                // Validate required fields conditionally
                if ($assessmentType === 'bulanan') {
                    if (empty($gradeData['month'])) {
                        continue; // skip invalid row
                    }
                } else { // uts/uas
                    if (empty($gradeData['semester'])) {
                        continue; // skip invalid row
                    }
                }

                // Find existing grade by combination
                $existingQuery = StudentGrades::where([
                    'student_id' => $gradeData['student_id'],
                    'subject_id' => $gradeData['subject_id'],
                    'class_id' => $gradeData['class_id'],
                    'academic_year' => $gradeData['academic_year'],
                    'assessment_type' => $assessmentType,
                ]);

                if ($assessmentType === 'bulanan') {
                    $existingQuery->where('month', $gradeData['month']);
                } else {
                    $existingQuery->where('semester', $gradeData['semester']);
                }

                $existingGrade = $existingQuery->first();

                // Prepare update data per type
                if ($assessmentType === 'bulanan') {
                    $updateData = [
                        'tugas1' => $gradeData['tugas1'] ?? null,
                        'tugas2' => $gradeData['tugas2'] ?? null,
                        'sikap' => $gradeData['sikap'] ?? null,
                        'aktif' => $gradeData['aktif'] ?? null,
                        // nilai final semester dihitung di laporan; nilai per baris bulanan dibiarkan null
                    ];
                } elseif ($assessmentType === 'uts') {
                    $updateData = [
                        'uts' => $gradeData['uts'] ?? null,
                    ];
                } else { // uas
                    $updateData = [
                        'uas' => $gradeData['uas'] ?? null,
                    ];
                }

                if ($existingGrade) {
                    $existingGrade->update(array_merge($updateData, [
                        'updated_by' => Auth::id()
                    ]));
                    $updated++;
                } else {
                    // Determine if there is any value to persist
                    $hasValues = array_filter($updateData, function($value) {
                        return $value !== null && $value !== '' && $value >= 0;
                    });

                    if (count($hasValues) > 0) {
                        $createPayload = array_merge($updateData, [
                            'id' => Str::uuid(),
                            'student_id' => $gradeData['student_id'],
                            'class_id' => $gradeData['class_id'],
                            'subject_id' => $gradeData['subject_id'],
                            'academic_year' => $gradeData['academic_year'],
                            'semester' => $assessmentType === 'bulanan' ? ($gradeData['semester'] ?? AcademicYearHelper::getCurrentSemester()) : $gradeData['semester'],
                            'assessment_type' => $assessmentType,
                            'month' => $assessmentType === 'bulanan' ? $gradeData['month'] : null,
                            'month_name' => $assessmentType === 'bulanan' && !empty($gradeData['month']) ? $monthNames[$gradeData['month']] : null,
                            'created_by' => Auth::id(),
                        ]);

                        StudentGrades::create($createPayload);
                        $created++;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbarui {$updated} data dan membuat {$created} data nilai baru",
                'stats' => [
                    'updated' => $updated,
                    'created' => $created,
                    'total_processed' => count($request->grades)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the error for debugging
            \Log::error('Bulk update grades error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nilai siswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get grade statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $query = StudentGrades::query();
            $user = Auth::user();

            // Jika user adalah teacher, filter berdasarkan mata pelajaran yang diajar
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }
                
                // Filter berdasarkan schedule teacher
                $query->whereExists(function($subQuery) use ($teacherId) {
                    $subQuery->select(DB::raw(1))
                        ->from('schedule')
                        ->whereColumn('schedule.class_id', 'student_grades.class_id')
                        ->whereColumn('schedule.subject_id', 'student_grades.subject_id')
                        ->whereColumn('schedule.academic_year', 'student_grades.academic_year')
                        ->where('schedule.teacher_id', $teacherId)
                        ->whereNull('schedule.deleted_at');
                });
            }

            if ($request->class_id) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->subject_id) {
                $query->where('subject_id', $request->subject_id);
            }

            if ($request->month) {
                $query->where('month', $request->month);
            }

            if ($request->academic_year) {
                $query->where('academic_year', $request->academic_year);
            }

            $grades = $query->whereNotNull('nilai')->get();

            $statistics = [
                'total_students' => $grades->count(),
                'average_grade' => $grades->avg('nilai'),
                'highest_grade' => $grades->max('nilai'),
                'lowest_grade' => $grades->min('nilai'),
                'passing_students' => $grades->where('nilai', '>=', 70)->count(),
                'failing_students' => $grades->where('nilai', '<', 70)->count(),
                'grade_distribution' => [
                    'excellent' => $grades->where('nilai', '>=', 90)->count(),
                    'good' => $grades->whereBetween('nilai', [80, 89.99])->count(),
                    'fair' => $grades->whereBetween('nilai', [70, 79.99])->count(),
                    'poor' => $grades->where('nilai', '<', 70)->count()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}