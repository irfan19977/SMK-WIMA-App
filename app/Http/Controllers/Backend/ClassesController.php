<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AcademicYearHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Semester;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassesController extends Controller
{
    use AuthorizesRequests;

    /**
     * Helper function to generate consistent class name
     * Format: {Roman Grade} {Major Short} {Academic Year}
     * Example: X TKJ 2025/2026
     */
    private function generateClassName($grade, $major, $academicYear)
    {
        $majorShortMap = [
            'Teknik Komputer & Jaringan' => 'TKJ',
            'Teknik Bisnis Sepeda Motor' => 'TSM',
            'Teknik Kendaraan Ringan Otomotif' => 'TKR',
            'Teknik Kimia Industri' => 'KI',
        ];

        $romanGradeMap = [
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        $gradeLabel = $romanGradeMap[$grade] ?? (string) $grade;
        $majorLabel = $majorShortMap[$major] ?? $major;
        
        return $gradeLabel . ' ' . $majorLabel . ' ' . $academicYear;
    }

    /**
     * Search for classes based on query
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $showArchived = $request->has('show_archived') && $request->show_archived;

        $classesQuery = Classes::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->orWhere('grade', 'LIKE', "%{$query}%")
            ->orWhere('major', 'LIKE', "%{$query}%");

        // Filter classes based on show_archived parameter
        if ($showArchived) {
            // Show only archived classes
            $classesQuery->where('is_archived', true);
        } else {
            // Show only non-archived classes
            $classesQuery->where('is_archived', false);
        }

        // Sort by grade first (10, 11, 12), then by major in dropdown order
        $majorOrder = [
            'Teknik Kendaraan Ringan Otomotif',
            'Teknik Bisnis dan Sepeda Motor',
            'Teknik Kimian Industri',
            'Teknik Komputer & Jaringan'
        ];

        $classesQuery->orderByRaw("CAST(grade AS UNSIGNED) ASC")
                     ->orderByRaw("FIELD(major, '" . implode("','", $majorOrder) . "') ASC");

        $classes = $classesQuery->get();

        return response()->json($classes->map(function($class) {
            return [
                'id' => $class->id,
                'name' => $class->name,
                'major' => $class->major,
                'code' => $class->code,
                'grade' => $class->grade,
                'is_archived' => $class->is_archived,
            ];
        }));
    }

    public function index(Request $request)
    {
        $this->authorize('classes.index');
        
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }

        if (Auth::check()) {
            // Check if user has student role by checking if they have a student record
            $student = \App\Models\Student::where('user_id', Auth::id())->first();
            if ($student) {
                $studentClass = \App\Models\StudentClass::where('student_id', $student->id)->first();

                if ($studentClass) {
                    return redirect()->route('classes.show', $studentClass->class_id);
                } else {
                    // Jika student tidak memiliki kelas, redirect dengan pesan error
                    return redirect()->back()->with('error', 'Anda belum terdaftar di kelas manapun.');
                }
            }
        }
        
        $query = Classes::query();
        
        // Filter classes based on show_archived parameter
        if ($request->has('show_archived') && $request->show_archived) {
            // Show only archived classes
            $query->where('is_archived', true);
        } else {
            // Show only non-archived classes by default
            $query->where('is_archived', false);
        }
        
        // Search filter
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('major', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Sort by grade first (10, 11, 12), then by major in dropdown order
        $majorOrder = [
            'Teknik Kendaraan Ringan Otomotif',
            'Teknik Bisnis dan Sepeda Motor',
            'Teknik Kimian Industri',
            'Teknik Komputer & Jaringan'
        ];

        $query->orderByRaw("CAST(grade AS UNSIGNED) ASC")
              ->orderByRaw("FIELD(major, '" . implode("','", $majorOrder) . "') ASC");

        // Tampilkan semua kelas (tanpa pagination) sesuai filter
        $classes = $query->withUniqueStudentsCount()->get();
        
        // Add unique_students_count to each class
        $classes->each(function($class) {
            $class->students_count = $class->unique_students_count;
        });
        
        // Return JSON for AJAX requests
        if ($request->ajax() || $request->expectsJson()) {
            $classesData = $classes->map(function($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'major' => $class->major,
                    'code' => $class->code,
                    'grade' => $class->grade,
                    'is_archived' => $class->is_archived,
                ];
            });
            
            return response()->json([
                'success' => true,
                'classes' => $classesData,
                // Pagination tidak digunakan lagi karena semua kelas ditampilkan sekaligus
                'pagination' => null,
            ]);
        }
        
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        if (request()->ajax()) {
            $view = view('classes._form', [
                'class' => null,
                'action' => route('classes.store'),
                'method' => 'POST',
                'title' => __('index.create_class')
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.create_class')
            ]);
        }
        
        return view('classes.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:classes,name',
                'major' => 'required|string|max:255',
                'grade' => 'nullable|integer|min:1|max:12',
                'academic_year' => 'nullable|string|max:255',
            ], [
                'name.required' => 'Nama kelas harus diisi.',
                'name.unique' => 'Nama kelas sudah digunakan. Silakan gunakan nama yang berbeda.',
                'name.max' => 'Nama kelas tidak boleh lebih dari 255 karakter.',
                'major.required' => 'Jurusan harus dipilih.',
                'grade.integer' => 'Grade harus berupa angka.',
                'grade.min' => 'Grade minimal adalah 1.',
                'grade.max' => 'Grade maksimal adalah 12.',
            ]);

            // Generate kode dengan format CLS-XXXXXX
            do {
                $code = 'CLS-' . strtoupper(Str::random(6));
            } while (Classes::where('code', $code)->exists());

            // Gunakan format nama yang konsisten jika grade dan academic_year tersedia
            $className = $request->name;
            if ($request->grade && $request->academic_year) {
                $className = $this->generateClassName($request->grade, $request->major, $request->academic_year);
                
                // Pastikan nama unik, tambahkan suffix jika perlu
                $suffix = 1;
                $finalName = $className;
                while (Classes::where('name', $finalName)->exists()) {
                    $finalName = $className . ' (' . $suffix . ')';
                    $suffix++;
                }
                $className = $finalName;
            }

            $classes = Classes::create([
                'name' => $className,
                'grade' => $request->grade,
                'major' => $request->major,
                'code' => $code,
                'academic_year' => $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear(),
                'is_archived' => false,
                'created_by' => Auth::id(),
            ]);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas berhasil dibuat.',
                    'class' => [
                        'id' => $classes->id,
                        'name' => $classes->name,
                        'major' => $classes->major,
                        'code' => $classes->code,
                        'grade' => $classes->grade,
                        'academic_year' => $classes->academic_year,
                    ]
                ]);
            }

            return redirect()->route('classes.index')->with('success', 'Kelas berhasil dibuat.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dimasukkan tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        } catch (\Exception $e) {
            // Return JSON for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        // Debug logging
        \Log::info('ClassesController@show called', [
            'class_id' => $id,
            'user_id' => Auth::id(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ajax' => request()->ajax(),
            'expects_json' => request()->expectsJson()
        ]);
        
        $classes = Classes::findOrFail($id);
        
        \Log::info('Class found', [
            'class_name' => $classes->name,
            'class_code' => $classes->code
        ]);
        
        // Get students in this class through pivot table
        $students = Student::whereHas('classes', function($query) use ($id) {
            $query->where('classes.id', $id);
        })->with('user')
        ->select('id', 'name', 'nisn', 'no_absen', 'gender', 'user_id')
        ->orderByRaw('CAST(no_absen AS UNSIGNED) ASC')
        ->get();
        
        // Get available students (siswa yang belum memiliki kelas sama sekali dan statusnya 'siswa')
        $availableStudents = Student::whereDoesntHave('classes')
            ->where('status', 'siswa')
            ->with('user')
            ->select('id', 'name', 'nisn', 'no_absen', 'gender', 'user_id')
            ->orderByRaw('CAST(no_absen AS UNSIGNED) ASC')
            ->get();
        
        // Determine current active semester for this class in its academic year
        $currentSemesterLabel = null;
        if ($classes->academic_year) {
            try {
                // Cek apakah ada semester aktif untuk academic year ini
                $activeSemester = Semester::where('academic_year', $classes->academic_year)
                    ->where('is_active', true)
                    ->first();
                
                if ($activeSemester) {
                    $currentSemesterLabel = ucfirst($activeSemester->semester_type);
                } else {
                    // Fallback ke logic lama jika tidak ada semester aktif
                    $activePivot = DB::table('student_class')
                        ->where('class_id', $classes->id)
                        ->where('academic_year', $classes->academic_year)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->first();

                    if ($activePivot) {
                        $sem = $activePivot->semester;
                        if ($sem === null || $sem === '' || $sem === '1' || $sem === 1 || strtolower((string) $sem) === 'ganjil') {
                            $currentSemesterLabel = 'Ganjil';
                        } elseif ($sem === '2' || $sem === 2 || strtolower((string) $sem) === 'genap') {
                            $currentSemesterLabel = 'Genap';
                        } else {
                            $currentSemesterLabel = 'Belum ditentukan';
                        }
                    }
                }
            } catch (\Exception $e) {
                // Handle jika tabel semesters tidak ada atau error lain
                \Log::warning('Semesters table error, using fallback logic', [
                    'error' => $e->getMessage()
                ]);
                
                // Fallback ke logic lama
                try {
                    $activePivot = DB::table('student_class')
                        ->where('class_id', $classes->id)
                        ->where('academic_year', $classes->academic_year)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->first();

                    if ($activePivot) {
                        $sem = $activePivot->semester;
                        if ($sem === null || $sem === '' || $sem === '1' || $sem === 1 || strtolower((string) $sem) === 'ganjil') {
                            $currentSemesterLabel = 'Ganjil';
                        } elseif ($sem === '2' || $sem === 2 || strtolower((string) $sem) === 'genap') {
                            $currentSemesterLabel = 'Genap';
                        } else {
                            $currentSemesterLabel = 'Belum ditentukan';
                        }
                    }
                } catch (\Exception $pivotException) {
                    \Log::error('Both semester and student_class queries failed', [
                        'semester_error' => $e->getMessage(),
                        'pivot_error' => $pivotException->getMessage()
                    ]);
                    $currentSemesterLabel = 'Tidak tersedia';
                }
            }
        }
        
        // Get current month and year
        $currentMonth = request('month', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $currentMonth);
        $daysInMonth = $monthDate->daysInMonth;
        
        // Get attendance data for current month and class
        $attendanceData = Attendance::whereHas('student.classes', function($query) use ($id) {
                $query->where('classes.id', $id);
            })
            ->whereYear('date', $monthDate->year)
            ->whereMonth('date', $monthDate->month)
            ->get()
            ->groupBy(['student_id', function($item) {
                return Carbon::parse($item->date)->day;
            }]);
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'class' => [
                    'id' => $classes->id,
                    'name' => $classes->name,
                    'major' => $classes->major,
                    'code' => $classes->code,
                    'grade' => $classes->grade,
                    'academic_year' => $classes->academic_year,
                    'students_count' => $students->count(),
                    'created_at' => $classes->created_at,
                    'updated_at' => $classes->updated_at,
                ],
                'students' => $students->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                        'email' => $student->user->email ?? '-',
                        'gender' => $student->gender,
                        'created_at' => $student->created_at,
                    ];
                }),
                'available_students' => $availableStudents->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                        'email' => $student->user->email ?? '-',
                    ];
                }),
                'attendance_data' => $attendanceData,
                'current_month' => $currentMonth,
                'days_in_month' => $daysInMonth
            ]);
        }
        
        \Log::info('About to return view', [
            'class_id' => $id,
            'students_count' => $students->count(),
            'available_students_count' => $availableStudents->count()
        ]);
        
        return view('classes.show', compact('classes', 'students', 'availableStudents', 'attendanceData', 'currentMonth', 'daysInMonth', 'currentSemesterLabel'));
    }

    public function getAttendanceData(Request $request, $classId)
    {
        try {
            $month = $request->get('month', Carbon::now()->format('Y-m'));
            
            // PERBAIKAN: Pastikan parsing bulan yang benar
            if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format bulan tidak valid. Gunakan format YYYY-MM'
                ], 400);
            }
            
            $monthDate = Carbon::createFromFormat('Y-m', $month);
            $daysInMonth = $monthDate->daysInMonth;
            
            // DEBUG: Log untuk debugging
            Log::info('Attendance Filter Debug', [
                'requested_month' => $month,
                'parsed_month' => $monthDate->format('Y-m'),
                'month_name' => $monthDate->format('F Y'),
                'days_in_month' => $daysInMonth,
                'year' => $monthDate->year,
                'month_number' => $monthDate->month
            ]);
            
            // Get students in this class
            $students = Student::whereHas('classes', function($query) use ($classId) {
                $query->where('classes.id', $classId);
            })->with('user')->orderByRaw('CAST(no_absen AS UNSIGNED) ASC')->get();
            
            // Get attendance data for the selected month and class
            $attendanceData = Attendance::whereHas('student.classes', function($query) use ($classId) {
                    $query->where('classes.id', $classId);
                })
                ->whereYear('date', $monthDate->year)
                ->whereMonth('date', $monthDate->month)
                ->get()
                ->groupBy(['student_id', function($item) {
                    return Carbon::parse($item->date)->day;
                }]);
            
            // Calculate attendance summary for each student
            $studentsWithAttendance = $students->map(function($student) use ($attendanceData, $daysInMonth, $monthDate) {
                $presentCount = 0;
                $sickCount = 0;
                $permitCount = 0;
                $absentCount = 0;
                
                $dailyAttendance = [];
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $attendance = $attendanceData->get($student->id, collect())->get($day);
                    $checkDate = $monthDate->copy()->day($day);
                    
                    if ($attendance && $attendance->first()) {
                        $status = $this->getAttendanceStatus($attendance->first());
                        $dailyAttendance[$day] = $status;
                        
                        switch($status) {
                            case 'H': $presentCount++; break;
                            case 'S': $sickCount++; break;
                            case 'I': $permitCount++; break;
                            case 'A': $absentCount++; break;
                        }
                    } else {
                        // Perbaikan logika: cek apakah hari sudah berlalu, weekend, atau masih akan datang
                        if ($checkDate->isFuture()) {
                            // Jika tanggal belum tiba, tampilkan sebagai "belum absen"
                            $dailyAttendance[$day] = '-';
                        } elseif ($checkDate->isWeekend()) {
                            // Jika weekend, tampilkan sebagai libur
                            $dailyAttendance[$day] = 'L'; // L untuk Libur
                        } else {
                            // Jika hari sudah berlalu tapi tidak ada data absen, baru tandai Alpha
                            $dailyAttendance[$day] = 'A';
                            $absentCount++;
                        }
                    }
                }
                
                $totalDays = $presentCount + $sickCount + $permitCount + $absentCount;
                $percentage = $totalDays > 0 ? round(($presentCount / $totalDays) * 100, 1) : 0;
                
                return [
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                        'email' => $student->user->email ?? '-',
                    ],
                    'daily_attendance' => $dailyAttendance,
                    'present_count' => $presentCount,
                    'sick_count' => $sickCount,
                    'permit_count' => $permitCount,
                    'absent_count' => $absentCount,
                    'percentage' => $percentage
                ];
            });
            
            // PERBAIKAN UTAMA: Nama bulan dalam bahasa Indonesia yang konsisten
            $monthNameIndonesia = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            // PERBAIKAN: Pastikan menggunakan month number yang benar dari Carbon
            $correctMonthName = $monthNameIndonesia[$monthDate->month] . ' ' . $monthDate->year;
            
            // DEBUG: Tambahkan log untuk memastikan month number yang benar
            Log::info('Month Mapping Debug', [
                'carbon_month' => $monthDate->month,
                'month_name_mapped' => $monthNameIndonesia[$monthDate->month],
                'final_month_name' => $correctMonthName
            ]);
            
            return response()->json([
                'success' => true,
                'students' => $studentsWithAttendance,
                'days_in_month' => $daysInMonth,
                'month_name' => $correctMonthName, // Gunakan nama bulan yang benar
                'debug_info' => [ // Tambahkan info debug (hapus di production)
                    'requested_month' => $month,
                    'parsed_date' => $monthDate->format('Y-m-d'),
                    'month_number' => $monthDate->month,
                    'year' => $monthDate->year,
                    'carbon_debug' => $monthDate->toDateString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Attendance Data Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getAttendanceStatus($attendance)
    {
        if (!$attendance) {
            return 'A';
        }
        
        $status = $attendance->check_in_status ?? $attendance->check_out_status;
        
        switch($status) {
            case 'tepat':
            case 'terlambat':
                return 'H'; 
            case 'sakit':
                return 'S'; 
            case 'izin':
                return 'I'; 
            case 'alpha':
            default:
                return 'A'; 
        }
    }

    public function edit(string $id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $classes = Classes::findOrFail($id);

        if (request()->ajax()) {
            $view = view('classes._form', [
                'class' => $classes,
                'action' => route('classes.update', $classes->id),
                'method' => 'PUT',
                'title' => __('index.edit_class')
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.edit_class')
            ]);
        }

        return view('classes.edit', compact('classes'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $id,
            'major' => 'required|string|max:255',
            'grade' => 'nullable|integer',
            'academic_year' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama kelas harus diisi.',
            'name.unique' => 'Nama kelas sudah digunakan. Silakan gunakan nama yang berbeda.',
            'name.max' => 'Nama kelas tidak boleh lebih dari 255 karakter.',
            'major.required' => 'Jurusan harus dipilih.',
            'grade.integer' => 'Grade harus berupa angka.',
            'grade.min' => 'Grade minimal adalah 1.',
            'grade.max' => 'Grade maksimal adalah 12.',
        ]);

        try {
            $classes = Classes::findOrFail($id);
            $classes->update([
                'name' => $request->name,
                'major' => $request->major,
                'grade' => $request->grade,
                'academic_year' => $request->academic_year,
                'updated_by' => Auth::id(),
            ]);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                $redirectTo = $request->input('redirect_to', 'index');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas berhasil diperbarui.',
                    'data' => [
                        'id' => $classes->id,
                        'name' => $classes->name,
                        'major' => $classes->major,
                        'code' => $classes->code,
                        'grade' => $classes->grade,
                        'academic_year' => $classes->academic_year,
                        'redirect_to' => $redirectTo
                    ]
                ]);
            }

            // Check redirect parameter
            $redirectTo = $request->input('redirect_to', 'index');
            
            if ($redirectTo === 'show') {
                return redirect()->route('classes.show', $id)->with('success', 'Kelas berhasil diperbarui.');
            }
            
            return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dimasukkan tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        } catch (\Exception $e) {
            // Return JSON for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleArchive($id)
    {
        try {
            $class = Classes::findOrFail($id);
            $class->update([
                'is_archived' => !$class->is_archived
            ]);

            $status = $class->is_archived ? 'diarsipkan' : 'dibatalkan dari arsip';

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ' . $status,
                'is_archived' => $class->is_archived
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status arsip: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $class = Classes::findOrFail($id);

            // Optional: detach all students from this class (if using pivot)
            DB::table('student_class')->where('class_id', $class->id)->delete();

            $class->delete();

            // Return JSON for AJAX requests
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas berhasil dihapus.'
                ]);
            }

            return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkAssign(Request $request, string $class)
    {
        try {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:student,id'
            ]);

            $class = Classes::findOrFail($class);
            $successCount = 0;
            $skippedCount = 0;
            $skippedNames = [];

            foreach ($request->student_ids as $studentId) {
                // Check if student is already in this class
                $existingAssignment = DB::table('student_class')
                    ->where('class_id', $class)
                    ->where('student_id', $studentId)
                    ->whereNull('deleted_at')
                    ->first();

                if ($existingAssignment) {
                    $student = Student::find($studentId);
                    $skippedCount++;
                    $skippedNames[] = $student->name;
                } else {
                    // Create assignment in pivot table with start_date
                    DB::table('student_class')->insert([
                        'id' => Str::uuid(),
                        'class_id' => $class->id,
                        'student_id' => $studentId,
                        'academic_year' => $class->academic_year ?? AcademicYearHelper::getCurrentAcademicYear(),
                        'created_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                        'start_date' => now(), 
                    ]);
                    $successCount++;
                }
            }

            $message = $successCount . ' siswa berhasil ditambahkan ke kelas.';
            if ($skippedCount > 0) {
                $message .= ' ' . $skippedCount . ' siswa dilewati karena sudah terdaftar.';
            }

            return redirect()->route('classes.show', $class)->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function removeStudent(Request $request, $class)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:student,id',
            ]);

            $classModel = Classes::findOrFail($class);

            // Remove student from class pivot table
            $deleted = DB::table('student_class')
                ->where('class_id', $classModel->id)
                ->where('student_id', $request->student_id)
                ->delete(); // Tambahkan ->delete() untuk benar-benar menghapus data

            if ($deleted) {
                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Siswa berhasil dihapus dari kelas.'
                    ]);
                }

                return redirect()->route('classes.show', $classModel->id)
                    ->with('success', 'Siswa berhasil dihapus dari kelas.');
            } else {
                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Siswa tidak ditemukan di kelas ini.'
                    ], 404);
                }

                return redirect()->back()
                    ->with('error', 'Siswa tidak ditemukan di kelas ini.');
            }
        } catch (\Exception $e) {
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }    

    /**
     * Remove student from class using student ID in URL
     */
    public function removeStudentFromClass($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            
            // Check if student is assigned to any class
            $classAssignment = DB::table('student_class')
                ->where('student_id', $studentId)
                ->first();

            if (!$classAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan di kelas manapun.'
                ], 404);
            }

            // Remove student from class
            $deleted = DB::table('student_class')
                ->where('student_id', $studentId)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Siswa berhasil dihapus dari kelas.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus siswa dari kelas.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function openNextSemesterBulk(Request $request)
    {
        try {
            $this->authorize('classes.edit');
            // Tentukan tahun akademik aktif berdasarkan kelas yang belum diarsip.
            // Ambil academic_year terbesar dari classes non-arsip.
            $academicYear = Classes::where('is_archived', false)->max('academic_year');
            if (!$academicYear) {
                $academicYear = AcademicYearHelper::getCurrentAcademicYear();
            }
            $now = now();

            $ganjilValues = ['1', 'ganjil', 1];
            $genapValues = ['2', 'genap', 2];

            $classes = Classes::where('is_archived', false)->get();

            if ($classes->isEmpty()) {
                return redirect()->route('classes.index')
                    ->with('error', 'Tidak ada kelas aktif yang dapat diproses.');
            }

            DB::transaction(function () use ($classes, $academicYear, $now, $ganjilValues, $genapValues) {
                // Proses pembuatan/pengaktifan semester genap di tabel semesters
                try {
                    // Cek apakah semester genap sudah ada untuk academic year ini
                    $genapSemester = Semester::where('academic_year', $academicYear)
                        ->where('semester_type', 'genap')
                        ->first();

                    if (!$genapSemester) {
                        // Buat semester genap baru
                        Semester::create([
                            'id' => (string) Str::uuid(),
                            'academic_year' => $academicYear,
                            'semester_type' => 'genap',
                            'is_active' => true,
                            'start_date' => $now,
                            'end_date' => null, // Akan diisi saat semester selesai
                            'description' => "Semester Genap Tahun Ajaran {$academicYear}",
                            'created_by' => Auth::id(),
                        ]);
                    } else {
                        // Aktifkan semester genap yang sudah ada
                        $genapSemester->update([
                            'is_active' => true,
                            'start_date' => $now,
                            'updated_by' => Auth::id(),
                        ]);
                    }

                    // Nonaktifkan semester ganjil untuk academic year ini
                    Semester::where('academic_year', $academicYear)
                        ->where('semester_type', 'ganjil')
                        ->update([
                            'is_active' => false,
                            'end_date' => $now,
                            'updated_by' => Auth::id(),
                        ]);

                } catch (\Exception $semesterError) {
                    // Log error tapi lanjutkan proses student_class
                    Log::warning('Gagal memproses tabel semesters, melanjutkan dengan student_class', [
                        'error' => $semesterError->getMessage()
                    ]);
                }

                foreach ($classes as $class) {
                    $activeGanjil = DB::table('student_class')
                        ->where('class_id', $class->id)
                        ->where('academic_year', $academicYear)
                        ->where(function($q) use ($ganjilValues) {
                            $q->whereIn('semester', $ganjilValues)
                              ->orWhereNull('semester');
                        })
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->get();

                    if ($activeGanjil->isEmpty()) {
                        // Jika tidak ada students di kelas ini, skip
                        continue;
                    }

                    // Update status ganjil ke completed
                    DB::table('student_class')
                        ->where('class_id', $class->id)
                        ->where('academic_year', $academicYear)
                        ->where(function($q) use ($ganjilValues) {
                            $q->whereIn('semester', $ganjilValues)
                              ->orWhereNull('semester');
                        })
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->update([
                            'status' => 'completed',
                            'end_date' => $now,
                            'updated_by' => Auth::id(),
                            'updated_at' => $now,
                        ]);

                    // Create genap records untuk students yang sama (tanpa copy dari arsip)
                    foreach ($activeGanjil as $row) {
                        $existsGenap = DB::table('student_class')
                            ->where('class_id', $class->id)
                            ->where('student_id', $row->student_id)
                            ->where('academic_year', $academicYear)
                            ->whereIn('semester', $genapValues)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existsGenap) {
                            continue;
                        }

                        DB::table('student_class')->insert([
                            'id' => Str::uuid(),
                            'class_id' => $class->id,
                            'student_id' => $row->student_id,
                            'academic_year' => $academicYear,
                            'semester' => 'genap',
                            'start_date' => $now,
                            'end_date' => null,
                            'status' => 'active',
                            'created_by' => Auth::id(),
                            'updated_by' => null,
                            'deleted_by' => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            });

            return redirect()->route('classes.index')
                ->with('success', 'Semester ganjil berhasil ditutup dan semester genap telah dibuka untuk semua kelas pada tahun akademik aktif.');
        } catch (\Exception $e) {
            return redirect()->route('classes.index')
                ->with('error', 'Terjadi kesalahan saat membuka semester genap secara masal: ' . $e->getMessage());
        }
    }

    /**
     * Promosikan semua siswa ke kelas dengan grade berikutnya untuk tahun akademik baru.
     * Versi awal: semua siswa dengan student_class aktif di semester genap tahun berjalan
     * akan dibuatkan record baru di kelas grade+1 dengan tahun akademik berikutnya.
     * Kelas grade 12 dilewati (dianggap lulus / tidak diproses di sini).
     */
    public function promoteStudentsBulk(Request $request)
    {
        try {
            $this->authorize('classes.edit');
            
            // Tentukan tahun akademik aktif berdasarkan kelas yang belum diarsip
            $currentAcademicYear = Classes::where('is_archived', false)->max('academic_year');
            if (!$currentAcademicYear) {
                $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYear();
            }

            // Hitung tahun akademik berikutnya
            [$startYear, $endYear] = array_map('intval', explode('/', $currentAcademicYear));
            $nextStartYear = $startYear + 1;
            $nextEndYear = $endYear + 1;
            $nextAcademicYear = $nextStartYear . '/' . $nextEndYear;

            $now = now();
            $userId = Auth::id() ?: '85fb727b-2080-4b36-bf9d-74de938acf84';

            // 1. Arsipkan semua kelas lama
            $oldClasses = Classes::where('academic_year', $currentAcademicYear)
                ->where('is_archived', false)
                ->get();

            foreach ($oldClasses as $oldClass) {
                $oldClass->is_archived = true;
                $oldClass->save();
            }

            // 2. Buat kelas baru untuk tahun ajaran berikutnya (grade +1) dan copy students
            $processedMajors = [];
            
            foreach ($oldClasses as $oldClass) {
                // Skip kelas 12 (lulus)
                if ($oldClass->grade >= 12) {
                    continue;
                }

                // Skip jika sudah dibuat kelas untuk major dan grade ini
                $key = $oldClass->grade . '|' . $oldClass->major;
                if (isset($processedMajors[$key])) {
                    continue;
                }

                // Buat kelas baru dengan grade +1
                $newGrade = $oldClass->grade + 1;
                $newClassName = $this->generateClassName($newGrade, $oldClass->major, $nextAcademicYear);

                // Generate unique code
                $code = null;
                do {
                    $code = 'CLS-' . strtoupper(Str::random(6));
                } while (Classes::where('code', $code)->exists());

                // Pastikan nama unik
                $suffix = 1;
                $finalName = $newClassName;
                while (Classes::where('name', $finalName)->exists()) {
                    $finalName = $newClassName . ' (' . $suffix . ')';
                    $suffix++;
                }

                $newClass = Classes::create([
                    'name' => $finalName,
                    'grade' => $newGrade,
                    'major' => $oldClass->major,
                    'code' => $code,
                    'academic_year' => $nextAcademicYear,
                    'is_archived' => false,
                    'created_by' => $userId,
                ]);

                // Copy students dari kelas lama ke kelas baru
                $this->copyStudentsToNewClass($oldClass, $newClass, $nextAcademicYear, $userId);

                $processedMajors[$key] = true;
            }

            // 4. Update semester (opsional)
            try {
                // Nonaktifkan semester genap tahun lama
                Semester::where('academic_year', $currentAcademicYear)
                    ->where('semester_type', 'genap')
                    ->update([
                        'is_active' => false,
                        'end_date' => $now,
                        'updated_by' => $userId,
                    ]);

                // Aktifkan semester ganjil tahun baru
                $nextGanjilSemester = Semester::where('academic_year', $nextAcademicYear)
                    ->where('semester_type', 'ganjil')
                    ->first();

                if (!$nextGanjilSemester) {
                    Semester::create([
                        'id' => (string) Str::uuid(),
                        'academic_year' => $nextAcademicYear,
                        'semester_type' => 'ganjil',
                        'is_active' => true,
                        'start_date' => $now,
                        'end_date' => null,
                        'description' => "Semester Ganjil Tahun Ajaran {$nextAcademicYear}",
                        'created_by' => $userId,
                    ]);
                } else {
                    $nextGanjilSemester->update([
                        'is_active' => true,
                        'start_date' => $now,
                        'updated_by' => $userId,
                    ]);
                }
            } catch (\Exception $semesterError) {
                // Log error tapi lanjutkan proses
                Log::warning('Semester processing failed', [
                    'error' => $semesterError->getMessage()
                ]);
            }

            // Return JSON response for AJAX requests
            $isAjax = $request->ajax() || $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';
            
            Log::info('Response type', [
                'ajax' => $request->ajax(),
                'expects_json' => $request->expectsJson(),
                'x_requested_with' => $request->header('X-Requested-With'),
                'is_ajax' => $isAjax
            ]);
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas berhasil dinaikkan. Kelas lama telah diarsipkan dan kelas baru untuk tahun ajaran berikutnya telah dibuat.',
                    'redirect' => route('classes.index')
                ]);
            }

            return redirect()->route('classes.index')
                ->with('success', 'Kelas berhasil dinaikkan. Kelas lama telah diarsipkan dan kelas baru untuk tahun ajaran berikutnya telah dibuat.');

        } catch (\Exception $e) {
            Log::error('promoteStudentsBulk failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('classes.index')
                ->with('error', 'Terjadi kesalahan saat melakukan naik kelas: ' . $e->getMessage());
        }
    }

    /**
     * Copy students from archived class to new class when opening new semester
     */
    private function copyFromArchivedClass($newClass, $academicYear, $now, $genapValues)
    {
        // First, check if there are already any students in this class for any semester
        $existingStudents = DB::table('student_class')
            ->where('class_id', $newClass->id)
            ->where('academic_year', $academicYear)
            ->whereNull('deleted_at')
            ->count();

        if ($existingStudents > 0) {
            Log::info('Class already has students, skipping copy from archived', [
                'class' => $newClass->name,
                'existing_students' => $existingStudents
            ]);
            return;
        }

        // Find the corresponding archived class (same major, previous grade)
        $archivedClass = Classes::where('major', $newClass->major)
            ->where('grade', $newClass->grade - 1)
            ->where('is_archived', true)
            ->where('academic_year', '<', $academicYear)
            ->orderBy('academic_year', 'desc')
            ->first();

        if (!$archivedClass) {
            Log::info('No archived class found', [
                'new_class' => $newClass->name,
                'major' => $newClass->major,
                'grade' => $newClass->grade - 1
            ]);
            return;
        }

        // Get students from archived class
        $students = DB::table('student_class')
            ->where('class_id', $archivedClass->id)
            ->whereNull('deleted_at')
            ->get();

        if ($students->isEmpty()) {
            Log::info('No students found in archived class', [
                'archived_class' => $archivedClass->name
            ]);
            return;
        }

        $copiedCount = 0;
        foreach ($students as $student) {
            // Check if student already exists in new class for ANY semester
            $exists = DB::table('student_class')
                ->where('class_id', $newClass->id)
                ->where('student_id', $student->student_id)
                ->where('academic_year', $academicYear)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                // Create student_class record for genap semester
                DB::table('student_class')->insert([
                    'id' => Str::uuid(),
                    'class_id' => $newClass->id,
                    'student_id' => $student->student_id,
                    'academic_year' => $academicYear,
                    'semester' => 'genap',
                    'start_date' => $now,
                    'end_date' => null,
                    'status' => 'active',
                    'created_by' => Auth::id(),
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $copiedCount++;
            }
        }

        Log::info('Copied students from archived class', [
            'archived_class' => $archivedClass->name,
            'new_class' => $newClass->name,
            'total_students' => $students->count(),
            'copied_students' => $copiedCount
        ]);
    }

    /**
     * Copy students from old class to new class
     */
    private function copyStudentsToNewClass($oldClass, $newClass, $nextAcademicYear, $userId)
    {
        // Get all students from old class (any semester, any status)
        $students = DB::table('student_class')
            ->where('class_id', $oldClass->id)
            ->whereNull('deleted_at')
            ->get();

        $now = now();
        $copiedCount = 0;

        foreach ($students as $student) {
            // Check if student already exists in new class
            $exists = DB::table('student_class')
                ->where('class_id', $newClass->id)
                ->where('student_id', $student->student_id)
                ->where('academic_year', $nextAcademicYear)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                // Create new student_class record for new class
                DB::table('student_class')->insert([
                    'id' => Str::uuid(),
                    'class_id' => $newClass->id,
                    'student_id' => $student->student_id,
                    'academic_year' => $nextAcademicYear,
                    'semester' => 'ganjil', // Default to ganjil for new academic year
                    'start_date' => $now,
                    'end_date' => null,
                    'status' => 'active',
                    'created_by' => $userId,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $copiedCount++;
            }
        }

        Log::info('Students copied to new class', [
            'old_class' => $oldClass->name,
            'new_class' => $newClass->name,
            'total_students' => $students->count(),
            'copied_students' => $copiedCount
        ]);
    }

    public function getPromoteData(Request $request)
    {
        try {
            $this->authorize('classes.edit');

            // Gunakan logika yang sama dengan promoteStudentsBulk untuk menentukan tahun akademik saat ini,
            // yaitu berdasarkan kelas yang belum diarsip. Jika tidak ada, fallback ke AcademicYearHelper.
            $currentAcademicYear = Classes::where('is_archived', false)->max('academic_year');
            if (!$currentAcademicYear) {
                $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYear();
            }
            $genapValues = ['2', 'genap', 2];

            $classes = Classes::where('academic_year', $currentAcademicYear)
                ->where('is_archived', false)
                ->orderBy('grade')
                ->orderBy('name')
                ->get();

            $result = [];

            foreach ($classes as $class) {
                if (empty($class->grade)) {
                    continue;
                }

                $students = DB::table('student_class as sc')
                    ->join('student as s', 's.id', '=', 'sc.student_id')
                    ->where('sc.class_id', $class->id)
                    ->where('sc.academic_year', $currentAcademicYear)
                    ->whereIn('sc.semester', $genapValues)
                    ->where('sc.status', 'active')
                    ->whereNull('sc.deleted_at')
                    ->select('s.id as student_id', 's.name', 's.nisn')
                    ->orderBy('s.name')
                    ->get();

                if ($students->isEmpty()) {
                    continue;
                }

                $result[] = [
                    'class_id' => $class->id,
                    'class_name' => $class->name,
                    'grade' => $class->grade,
                    'major' => $class->major,
                    'students' => $students,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data promot siswa: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function openNextSemester(Request $request, string $classId)
    {
        try {
            $this->authorize('classes.edit');

            $class = Classes::findOrFail($classId);
            $academicYear = $class->academic_year ?? AcademicYearHelper::getCurrentAcademicYear();

            $now = now();

            $ganjilValues = ['1', 'ganjil', 1];
            $genapValues = ['2', 'genap', 2];

            $activeGanjil = DB::table('student_class')
                ->where('class_id', $class->id)
                ->where('academic_year', $academicYear)
                ->where(function($q) use ($ganjilValues) {
                    $q->whereIn('semester', $ganjilValues)
                      ->orWhereNull('semester');
                })
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->get();

            if ($activeGanjil->isEmpty()) {
                return redirect()->route('classes.show', $class->id)
                    ->with('error', 'Tidak ada siswa dengan status aktif di semester ganjil untuk kelas ini.');
            }

            DB::transaction(function () use ($activeGanjil, $class, $academicYear, $now, $ganjilValues, $genapValues) {
                // Tutup semua record semester ganjil yang aktif
                DB::table('student_class')
                    ->where('class_id', $class->id)
                    ->where('academic_year', $academicYear)
                    ->where(function($q) use ($ganjilValues) {
                        $q->whereIn('semester', $ganjilValues)
                          ->orWhereNull('semester');
                    })
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->update([
                        'status' => 'completed',
                        'end_date' => $now,
                        'updated_by' => Auth::id(),
                        'updated_at' => $now,
                    ]);

                foreach ($activeGanjil as $row) {
                    // Cek apakah sudah ada record semester genap untuk siswa ini di tahun yang sama
                    $existsGenap = DB::table('student_class')
                        ->where('class_id', $class->id)
                        ->where('student_id', $row->student_id)
                        ->where('academic_year', $academicYear)
                        ->whereIn('semester', $genapValues)
                        ->whereNull('deleted_at')
                        ->exists();

                    if ($existsGenap) {
                        continue;
                    }

                    DB::table('student_class')->insert([
                        'id' => Str::uuid(),
                        'class_id' => $class->id,
                        'student_id' => $row->student_id,
                        'academic_year' => $academicYear,
                        'semester' => 'genap',
                        'start_date' => $now,
                        'end_date' => null,
                        'status' => 'active',
                        'created_by' => Auth::id(),
                        'updated_by' => null,
                        'deleted_by' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });

            return redirect()->route('classes.show', $class->id)
                ->with('success', 'Semester ganjil berhasil ditutup dan semester genap telah dibuka untuk semua siswa di kelas ini.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuka semester genap: ' . $e->getMessage());
        }
    }
}
