<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LessonAttendanceController extends Controller
{
    
    public function index(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
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

        // Build query for lesson attendances
        $query = DB::table('lesson_attendance as la')
            ->select([
                'la.id',
                'la.student_id',
                'la.subject_id',
                'la.date',
                'la.check_in_status as status',
                'la.check_in',
                's.nisn as student_nisn',
                's.name as student_name',
                's.user_id',
                'c.name as class_name',
                'c.id as class_id',
                'sub.name as subject_name'
            ])
            ->join('student as s', 'la.student_id', '=', 's.id')
            ->join('student_class as sc', 's.id', '=', 'sc.student_id')
            ->join('classes as c', 'sc.class_id', '=', 'c.id')
            ->join('subject as sub', 'la.subject_id', '=', 'sub.id')
            ->whereNull('la.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('c.deleted_at');

        // Apply filters
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('s.nisn', 'like', "%{$search}%")
                  ->orWhere('s.name', 'like', "%{$search}%")
                  ->orWhere('c.name', 'like', "%{$search}%")
                  ->orWhere('sub.name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('c.id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('sub.id', $request->subject_id);
        }

        // Apply teacher restriction if user is teacher
        if ($user->hasRole('teacher')) {
            $query->whereExists(function($subQuery) use ($user) {
                $subQuery->select(DB::raw(1))
                    ->from('schedules')
                    ->whereColumn('schedules.class_id', 'c.id')
                    ->whereColumn('schedules.subject_id', 'sub.id')
                    ->where('schedules.teacher_id', $user->id);
            });
        }

        // Order by date desc, then student name
        $query->orderBy('la.date', 'desc')
              ->orderBy('s.name', 'asc');

        // Paginate
        $lessonAttendances = $query->paginate($request->get('per_page', 10));

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportExcel($lessonAttendances);
        }

        // Handle print
        if ($request->has('print') && $request->print === 'pdf') {
            return $this->printPDF($lessonAttendances);
        }

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            $formattedAttendances = collect($lessonAttendances->items())->map(function($item) {
                // Determine status class and text
                $statusClass = 'bg-secondary';
                $statusText = $item->status ?? '-';
                
                switch($item->status) {
                    case 'hadir':
                        $statusClass = 'bg-success';
                        $statusText = 'Hadir';
                        break;
                    case 'terlambat':
                        $statusClass = 'bg-danger';
                        $statusText = 'Terlambat';
                        break;
                    case 'izin':
                        $statusClass = 'bg-light text-dark';
                        $statusText = 'Izin';
                        break;
                    case 'sakit':
                        $statusClass = 'bg-light text-dark';
                        $statusText = 'Sakit';
                        break;
                    case 'alpha':
                        $statusClass = 'bg-danger';
                        $statusText = 'Alpa';
                        break;
                }

                return [
                    'id' => $item->id,
                    'student_nisn' => $item->student_nisn,
                    'student_name' => $item->student_name,
                    'user_id' => $item->user_id,
                    'class_id' => $item->class_id,
                    'class_name' => $item->class_name,
                    'subject_name' => $item->subject_name,
                    'date' => Carbon::parse($item->date)->format('d M Y'),
                    'status' => $item->status,
                    'status_text' => $statusText,
                    'status_class' => $statusClass,
                    'check_in' => $item->check_in ? Carbon::parse($item->check_in)->format('H:i') : '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedAttendances,
                'pagination' => [
                    'total' => $lessonAttendances->total(),
                    'per_page' => $lessonAttendances->perPage(),
                    'current_page' => $lessonAttendances->currentPage(),
                    'last_page' => $lessonAttendances->lastPage()
                ]
            ]);
        }

        return view('lesson_attendance.index', compact('lessonAttendances', 'classes', 'subjects'));
    }

    public function getAttendanceCalendar(Request $request)
    {
        try {
            $classId = $request->class_id;
            $subjectId = $request->subject_id;
            $year = $request->year;
            $month = $request->month;

            if (!$classId || !$subjectId || !$year || !$month) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak lengkap'
                ]);
            }

            // Get students in the class
            $students = DB::table('student as s')
                ->join('student_class as sc', 's.id', '=', 'sc.student_id')
                ->where('sc.class_id', $classId)
                ->whereNull('s.deleted_at')
                ->select('s.id', 's.name', 's.nisn', 's.user_id')
                ->orderBy('s.name')
                ->get();

            // Get attendance data for the month
            $attendanceData = DB::table('lesson_attendance as la')
                ->where('la.class_id', $classId)
                ->where('la.subject_id', $subjectId)
                ->whereYear('la.date', $year)
                ->whereMonth('la.date', $month)
                ->whereNull('la.deleted_at')
                ->select(
                    'la.student_id',
                    'la.date',
                    'la.check_in_status',
                    'la.check_in'
                )
                ->get();

            // Calculate summary
            $summary = [
                'hadir' => $attendanceData->whereIn('check_in_status', ['hadir', 'terlambat'])->count(),
                'izin' => $attendanceData->where('check_in_status', 'izin')->count(),
                'sakit' => $attendanceData->where('check_in_status', 'sakit')->count(),
                'alpha' => $attendanceData->where('check_in_status', 'alpha')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $attendanceData,
                'students' => $students,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $user = Auth::user();
        $preselectedClassId = $request->class_id;
        $preselectedSubjectId = $request->subject_id;
        
        // Get classes based on user role
        if ($user->hasRole('teacher')) {
            $classes = Classes::whereHas('schedules', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->get();
        } else {
            $classes = Classes::all();
        }

        // Get subjects filtered by active semester
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $academicYear = $activeSemester ? $activeSemester->academic_year : null;
        $semesterType = $activeSemester ? $activeSemester->semester_type : null;
        
        $subjectsQuery = Subject::query();
        if ($academicYear && $semesterType) {
            $subjectsQuery->whereHas('schedules', function ($q) use ($academicYear, $semesterType) {
                $q->where('academic_year', $academicYear)
                  ->where('semester', $semesterType);
            });
        }
        $subjects = $subjectsQuery->get();
        
        // Don't load students initially - they will be loaded dynamically based on class selection
        $students = collect();
        
        // Get all students with their classes for NISN search
        $allStudents = Student::with('classes')->get();
        $allClasses = $classes;

        $view = view('lesson_attendance._form', [
            'action' => route('lesson-attendances.store'),
            'method' => 'POST',
            'title' => __('index.add_lesson_attendance'),
            'classes' => $classes,
            'subjects' => $subjects,
            'students' => $students,
            'lessonAttendance' => null,
            'preselectedClassId' => $preselectedClassId,
            'preselectedSubjectId' => $preselectedSubjectId
        ])->render();
        
        return response()->json([
            'success' => true,
            'title' => __('index.add_lesson_attendance'),
            'html' => $view,
            'students' => $allStudents,
            'classes' => $allClasses
        ]);
    }

    public function edit($id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $lessonAttendance = DB::table('lesson_attendance as la')
            ->select([
                'la.id',
                'la.student_id',
                'la.subject_id',
                'la.class_id',
                'la.date',
                'la.check_in_status as status',
                'la.check_in',
                'la.academic_year',
                's.nisn as student_nisn',
                's.name as student_name',
                'c.name as class_name',
                'sub.name as subject_name'
            ])
            ->join('student as s', 'la.student_id', '=', 's.id')
            ->join('student_class as sc', 's.id', '=', 'sc.student_id')
            ->join('classes as c', 'sc.class_id', '=', 'c.id')
            ->join('subject as sub', 'la.subject_id', '=', 'sub.id')
            ->where('la.id', $id)
            ->whereNull('la.deleted_at')
            ->first();

        if (!$lessonAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $user = Auth::user();
        
        // Get classes based on user role
        if ($user->hasRole('teacher')) {
            $classes = Classes::whereHas('schedules', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->get();
        } else {
            $classes = Classes::all();
        }

        // Get all subjects
        $subjects = Subject::all();
        
        // Load students for specific class being edited
        $students = Student::whereHas('classes', function ($query) use ($lessonAttendance) {
            $query->where('classes.id', $lessonAttendance->class_id)
                  ->where('student_class.status', 'active');
            if ($lessonAttendance->academic_year) {
                $query->where('student_class.academic_year', $lessonAttendance->academic_year);
            }
        })->orderBy('no_absen')->get();
        
        // Get all students with their classes for NISN search
        $allStudents = Student::with('classes')->get();

        $view = view('lesson_attendance._form', [
            'action' => route('lesson-attendances.update', $lessonAttendance->id),
            'method' => 'PUT',
            'title' => __('index.edit_lesson_attendance'),
            'classes' => $classes,
            'subjects' => $subjects,
            'students' => $students,
            'lessonAttendance' => $lessonAttendance
        ])->render();
        
        return response()->json([
            'success' => true,
            'title' => __('index.edit_lesson_attendance'),
            'html' => $view,
            'students' => $allStudents,
            'classes' => $classes
        ]);
    }

    public function getSubjectsByClass(Request $request)
    {
        try {
            // Handle both GET and POST requests
            $classId = $request->class_id ?? $request->input('class_id');
            
            // Get active semester's academic year and semester type instead of user input
            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
            $academicYear = $activeSemester ? $activeSemester->academic_year : null;
            $semesterType = $activeSemester ? $activeSemester->semester_type : null;
            $user = Auth::user();

            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class ID is required'
                ], 400);
            }

            \Log::info('getSubjectsByClass called with:', [
                'class_id' => $classId,
                'academic_year' => $academicYear,
                'active_semester' => $activeSemester ? $activeSemester->id : 'none'
            ]);

            $query = Subject::query();

            if ($user->hasRole('teacher')) {
                // For teachers, only show subjects they teach for this class
                $query->whereHas('schedules', function ($q) use ($classId, $user, $academicYear, $semesterType) {
                    $q->where('class_id', $classId)
                      ->where('teacher_id', $user->id);
                    if ($academicYear) {
                        $q->where('academic_year', $academicYear);
                    }
                    if ($semesterType) {
                        $q->where('semester', $semesterType);
                    }
                });
            } else {
                // For admin, show all subjects for the class
                $query->whereHas('schedules', function ($q) use ($classId, $academicYear, $semesterType) {
                    $q->where('class_id', $classId);
                    if ($academicYear) {
                        $q->where('academic_year', $academicYear);
                    }
                    if ($semesterType) {
                        $q->where('semester', $semesterType);
                    }
                });
            }

            $subjects = $query->get();

            \Log::info('Subjects found: ' . $subjects->count());

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'debug' => [
                    'class_id' => $classId,
                    'academic_year' => $academicYear,
                    'subjects_count' => $subjects->count(),
                    'active_semester_id' => $activeSemester ? $activeSemester->id : null
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getSubjectsByClass error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat mata pelajaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudents(Request $request)
    {
        try {
            // Handle both GET and POST requests
            $classId = $request->class_id ?? $request->input('class_id');
            
            // Get active semester's academic year instead of user input
            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
            $academicYear = $activeSemester ? $activeSemester->academic_year : null;

            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class ID is required'
                ], 400);
            }

            // Debug: Log the request parameters
            \Log::info('getStudents called with:', [
                'class_id' => $classId,
                'academic_year' => $academicYear,
                'active_semester' => $activeSemester ? $activeSemester->id : 'none'
            ]);

            // First try without academic year filter to see if students exist
            $studentsWithoutYear = Student::whereHas('classes', function ($query) use ($classId) {
                $query->where('classes.id', $classId)
                      ->where('student_class.status', 'active');
            })->orderBy('no_absen')->get();

            \Log::info('Students without year filter: ' . $studentsWithoutYear->count());

            // Now try with academic year filter
            $students = Student::whereHas('classes', function ($query) use ($classId, $academicYear) {
                $query->where('classes.id', $classId)
                      ->where('student_class.status', 'active');
                if ($academicYear) {
                    $query->where('student_class.academic_year', $academicYear);
                }
            })->orderBy('no_absen')->get();

            \Log::info('Students with year filter: ' . $students->count());

            // If no students found with academic year, return without year filter
            if ($students->count() === 0 && $studentsWithoutYear->count() > 0) {
                \Log::info('Using students without academic year filter');
                $students = $studentsWithoutYear;
            }

            return response()->json([
                'success' => true,
                'data' => $students,
                'debug' => [
                    'class_id' => $classId,
                    'academic_year' => $academicYear,
                    'students_count' => $students->count(),
                    'students_without_year_count' => $studentsWithoutYear->count(),
                    'active_semester_id' => $activeSemester ? $activeSemester->id : null
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('getStudents error: ' . $e->getMessage());
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
        \Log::info('store method called', [
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'attendances_count' => count($request->attendances),
            'attendances_data' => $request->attendances
        ]);
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:hadir,terlambat,izin,sakit,alpha',
            'attendances.*.check_in' => 'nullable|date_format:H:i'
        ]);

        try {
            DB::beginTransaction();

            // Get active semester's academic year
            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
            $academicYear = $activeSemester ? $activeSemester->academic_year : null;

            if (!$academicYear) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada semester aktif yang ditemukan'
                ], 400);
            }

            $savedCount = 0;
            $attendances = $request->attendances;

            foreach ($attendances as $studentId => $attendanceData) {
                // Only save if check_in is filled
                if (!empty($attendanceData['check_in'])) {
                    // Check if attendance already exists
                    $existingAttendance = Lesson::where([
                        'student_id' => $studentId,
                        'class_id' => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'date' => $request->date
                    ])->first();

                    if ($existingAttendance) {
                        continue; // Skip if already exists
                    }

                    Lesson::create([
                        'student_id' => $studentId,
                        'class_id' => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'date' => $request->date,
                        'check_in' => $attendanceData['check_in'],
                        'check_in_status' => $attendanceData['status'],
                        'academic_year' => $academicYear,
                        'semester' => $this->getSemester($request->date),
                        'created_by' => Auth::id()
                    ]);

                    $savedCount++;
                }
            }

            DB::commit();

            if ($savedCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data absensi yang disimpan. Pastikan check-in terisi untuk siswa yang hadir.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "Data absensi berhasil disimpan untuk {$savedCount} siswa",
                'saved_count' => $savedCount
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

    public function getGeneralAttendanceCalendar(Request $request)
    {
        $classId = $request->class_id;
        $year = $request->year;
        $month = $request->month;

        if (!$classId || !$year || !$month) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Get students in the class
        $students = DB::table('student as s')
            ->join('student_class as sc', 's.id', '=', 'sc.student_id')
            ->where('sc.class_id', $classId)
            ->whereNull('s.deleted_at')
            ->select('s.id', 's.name', 's.nisn', 's.user_id')
            ->orderBy('s.name')
            ->get();

        // Get general attendance data (not lesson-specific) for the month
        $attendanceData = DB::table('attendance as a')
            ->where('a.class_id', $classId)
            ->whereYear('a.date', $year)
            ->whereMonth('a.date', $month)
            ->whereNull('a.deleted_at')
            ->select(
                'a.student_id',
                'a.date',
                'a.check_in_status',
                'a.check_in'
            )
            ->orderBy('a.date')
            ->get();

        // Calculate summary
        $summary = [
            'hadir' => $attendanceData->whereIn('check_in_status', ['hadir', 'tepat'])->count(),
            'terlambat' => $attendanceData->where('check_in_status', 'terlambat')->count(),
            'izin' => $attendanceData->where('check_in_status', 'izin')->count(),
            'sakit' => $attendanceData->where('check_in_status', 'sakit')->count(),
            'alpha' => $attendanceData->whereIn('check_in_status', ['alpha', 'alfa'])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $attendanceData,
            'students' => $students,
            'summary' => $summary
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        \Log::info('bulkUpdate method called', [
            'attendances_count' => count($request->attendances),
            'attendances_data' => $request->attendances
        ]);
        
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:student,id',
            'attendances.*.class_id' => 'required|exists:classes,id',
            'attendances.*.subject_id' => 'required|exists:subject,id',
            'attendances.*.date' => 'required|date',
            'attendances.*.check_in_status' => 'required|in:hadir,terlambat,izin,sakit,alpha',
            'attendances.*.check_in' => 'nullable|date_format:H:i'
        ]);

        try {
            DB::beginTransaction();

            // Get active semester's academic year
            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
            $academicYear = $activeSemester ? $activeSemester->academic_year : null;

            if (!$academicYear) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada semester aktif yang ditemukan'
                ], 400);
            }

            $savedCount = 0;
            $updatedCount = 0;

            foreach ($request->attendances as $attendanceData) {
                // Only process if check_in is filled
                if (empty($attendanceData['check_in'])) {
                    continue; // Skip students without check-in
                }
                
                // Add academic year from active semester
                $attendanceData['academic_year'] = $academicYear;

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
                        'academic_year' => $academicYear,
                        'semester' => $this->getSemester($attendanceData['date']),
                        'created_by' => Auth::id()
                    ]);
                    $savedCount++;
                }
            }

            DB::commit();

            if ($savedCount === 0 && $updatedCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data absensi yang disimpan. Pastikan check-in terisi untuk siswa yang hadir.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyimpan {$savedCount} data baru dan memperbarui {$updatedCount} data absensi (hanya siswa dengan check-in)"
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

    public function getCurrentSubjectByClass(Request $request)
    {
        try {
            $classId = $request->class_id;
            
            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class ID is required'
                ], 400);
            }

            // Get current time and day in Indonesian
            $now = now();
            $currentTime = $now->format('H:i:s');
            $currentDay = $this->getIndonesianDay($now->format('l'));
            
            // Get active semester
            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
            $academicYear = $activeSemester ? $activeSemester->academic_year : null;
            $semesterType = $activeSemester ? $activeSemester->semester_type : null;
            
            // Get current schedule for this class
            $schedule = \App\Models\Schedule::where('class_id', $classId)
                ->where('day', $currentDay)
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->when($academicYear, function ($query) use ($academicYear) {
                    $query->where('academic_year', $academicYear);
                })
                ->when($semesterType, function ($query) use ($semesterType) {
                    $query->where('semester', $semesterType);
                })
                ->with('subject')
                ->first();

            if ($schedule && $schedule->subject) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $schedule->subject->id,
                        'name' => $schedule->subject->name,
                        'current_time' => $currentTime,
                        'current_day' => $currentDay,
                        'schedule_start' => $schedule->start_time,
                        'schedule_end' => $schedule->end_time
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal pelajaran untuk saat ini',
                    'data' => [
                        'current_time' => $currentTime,
                        'current_day' => $currentDay
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error getting current subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pelajaran'
            ], 500);
        }
    }

    private function getIndonesianDay($englishDay)
    {
        $days = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu'
        ];
        
        return $days[$englishDay] ?? $englishDay;
    }

    /**
     * Export lesson attendances to Excel
     */
    private function exportExcel($lessonAttendances)
    {
        // Implementation for Excel export
        // You can use Laravel Excel package here
        return response()->json([
            'message' => 'Export Excel feature coming soon!'
        ]);
    }

    /**
     * Print lesson attendances to PDF
     */
    private function printPDF($lessonAttendances)
    {
        // Implementation for PDF print
        // You can use DomPDF or similar package here
        return response()->json([
            'message' => 'Print PDF feature coming soon!'
        ]);
    }

}