<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AcademicYearHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassesController extends Controller
{
    use AuthorizesRequests;

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
        $classes = $query->get();
        
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

            $classes = Classes::create([
                'name' => $request->name,
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
        try {
            $classes = Classes::findOrFail($id);
            
            // Get students in this class through pivot table
            $students = Student::whereHas('classes', function($query) use ($id) {
                $query->where('classes.id', $id);
            })->with('user')
            ->orderByRaw('CAST(no_absen AS UNSIGNED) ASC')
            ->get();
            
            // Get available students (siswa yang belum memiliki kelas sama sekali)
            $availableStudents = Student::whereDoesntHave('classes')->with('user')->orderByRaw('CAST(no_absen AS UNSIGNED) ASC')->get();
            
            // Determine current active semester for this class in its academic year
            $currentSemesterLabel = null;
            if ($classes->academic_year) {
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
            
            return view('classes.show', compact('classes', 'students', 'availableStudents', 'attendanceData', 'currentMonth', 'daysInMonth', 'currentSemesterLabel'));
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan.'
                ], 404);
            }
            
            return redirect()->route('classes.index')->with('error', 'Kelas tidak ditemukan.');
        }
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
        $classes = Classes::findOrFail($id);

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
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas berhasil diperbarui.',
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
                        continue;
                    }

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
            // Tentukan tahun akademik aktif berdasarkan kelas yang belum diarsip (tahun berjalan).
            $currentAcademicYear = Classes::where('is_archived', false)->max('academic_year');
            if (!$currentAcademicYear) {
                $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYear();
            }

            // Hitung tahun akademik berikutnya dari format "YYYY/YYYY+1"
            [$startYear, $endYear] = array_map('intval', explode('/', $currentAcademicYear));
            $nextStartYear = $startYear + 1;
            $nextEndYear = $endYear + 1;
            $nextAcademicYear = $nextStartYear . '/' . $nextEndYear;

            $now = now();

            $genapValues = ['2', 'genap', 2];

            // Ambil semua kelas aktif pada tahun akademik saat ini
            $classes = Classes::where('academic_year', $currentAcademicYear)
                ->where('is_archived', false)
                ->get();

            if ($classes->isEmpty()) {
                return redirect()->route('classes.index')
                    ->with('error', 'Tidak ada kelas aktif pada tahun akademik saat ini yang dapat diproses.');
            }

            // Siapkan mapping data siswa dari request (jika ada)
            $studentsMap = [];
            $studentsJson = $request->input('students_json');
            if ($studentsJson) {
                $decoded = json_decode($studentsJson, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $item) {
                        if (!isset($item['student_id'], $item['class_id'])) {
                            continue;
                        }
                        $key = $item['class_id'] . ':' . $item['student_id'];
                        $studentsMap[$key] = [
                            'promote' => isset($item['promote']) ? (bool)$item['promote'] : true,
                        ];
                    }
                }
            }

            // Siapkan cache kelas tujuan tahun depan berdasarkan (grade, major)
            $nextYearClasses = Classes::where('academic_year', $nextAcademicYear)
                ->where('is_archived', false)
                ->get()
                ->groupBy(function ($cls) {
                    return $cls->grade . '|' . $cls->major;
                });

            DB::transaction(function () use ($classes, $currentAcademicYear, $nextAcademicYear, $now, $genapValues, $studentsMap, $nextYearClasses) {
                $majorShortMap = [
                    'Teknik Komputer & Jaringan' => 'TKJ',
                    'Teknik Bisnis Sepeda Motor' => 'TSM',
                    'Teknik Kendaraan Ringan Otomotif' => 'TKR',
                    'Teknik Kimia Industri' => 'KI',
                ];
                $romanGradeMap = [10 => 'X', 11 => 'XI', 12 => 'XII'];
                foreach ($classes as $class) {
                    if (empty($class->grade)) {
                        continue;
                    }

                    $currentGrade = (int) $class->grade;

                    // Ambil semua student_class aktif di semester genap untuk kelas ini
                    $activeGenap = DB::table('student_class')
                        ->where('class_id', $class->id)
                        ->where('academic_year', $currentAcademicYear)
                        ->whereIn('semester', $genapValues)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->get();

                    if ($activeGenap->isEmpty()) {
                        continue;
                    }

                    // Tutup semua record semester genap yang aktif untuk kelas ini
                    DB::table('student_class')
                        ->where('class_id', $class->id)
                        ->where('academic_year', $currentAcademicYear)
                        ->whereIn('semester', $genapValues)
                        ->where('status', 'active')
                        ->whereNull('deleted_at')
                        ->update([
                            'status' => 'completed',
                            'end_date' => $now,
                            'updated_by' => Auth::id(),
                            'updated_at' => $now,
                        ]);

                    foreach ($activeGenap as $row) {
                        $key = $class->id . ':' . $row->student_id;
                        $promote = $studentsMap[$key]['promote'] ?? true; // default: naik

                        // Jika tidak dicentang (tidak naik) -> siswa mengulang di grade yang sama (10, 11, atau 12)
                        if (!$promote) {
                            if (in_array($currentGrade, [10, 11, 12], true)) {
                                $repeatGrade = $currentGrade;
                                $gradeLabel = $romanGradeMap[$repeatGrade] ?? (string) $repeatGrade;
                                $majorLabel = $majorShortMap[$class->major] ?? $class->major;
                                $repeatGroupKey = $repeatGrade . '|' . $class->major;
                                $repeatClass = $nextYearClasses->get($repeatGroupKey)?->first();

                                if (!$repeatClass) {
                                    $code = null;
                                    do {
                                        $code = 'CLS-' . strtoupper(Str::random(6));
                                    } while (Classes::where('code', $code)->exists());

                                    // Pastikan nama kelas ulang unik, misal "XI TKJ 2026/2027"
                                    $baseName = $gradeLabel . ' ' . $majorLabel . ' ' . $nextAcademicYear;
                                    $targetName = $baseName;
                                    $suffix = 1;
                                    while (Classes::where('name', $targetName)->exists()) {
                                        $targetName = $baseName . ' (' . $suffix . ')';
                                        $suffix++;
                                    }

                                    $repeatClass = Classes::create([
                                        'name' => $targetName,
                                        'grade' => $repeatGrade,
                                        'major' => $class->major,
                                        'code' => $code,
                                        'academic_year' => $nextAcademicYear,
                                        'is_archived' => false,
                                        'created_by' => Auth::id(),
                                    ]);

                                    $nextYearClasses = $nextYearClasses->put($repeatGroupKey, collect([$repeatClass]));
                                }

                                $existsRepeat = DB::table('student_class')
                                    ->where('class_id', $repeatClass->id)
                                    ->where('student_id', $row->student_id)
                                    ->where('academic_year', $nextAcademicYear)
                                    ->whereNull('deleted_at')
                                    ->exists();

                                if (!$existsRepeat) {
                                    DB::table('student_class')->insert([
                                        'id' => Str::uuid(),
                                        'class_id' => $repeatClass->id,
                                        'student_id' => $row->student_id,
                                        'academic_year' => $nextAcademicYear,
                                        'semester' => 'ganjil',
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

                            continue;
                        }

                        // Tentukan grade tujuan untuk siswa yang dinaikkan
                        if ($currentGrade >= 12) {
                            // Kelas 12 yang dinaikkan: dianggap lulus, tidak dibuat record baru
                            continue;
                        }
                        $targetGrade = $currentGrade + 1;

                        $groupKey = $targetGrade . '|' . $class->major;
                        $targetClass = $nextYearClasses->get($groupKey)?->first();

                        if (!$targetClass) {
                            $code = null;
                            do {
                                $code = 'CLS-' . strtoupper(Str::random(6));
                            } while (Classes::where('code', $code)->exists());

                            // Pastikan nama kelas tujuan unik (kolom name unik di tabel classes)
                            $gradeLabel = $romanGradeMap[$targetGrade] ?? (string) $targetGrade;
                            $majorLabel = $majorShortMap[$class->major] ?? $class->major;
                            $baseName = $gradeLabel . ' ' . $majorLabel . ' ' . $nextAcademicYear;
                            $targetName = $baseName;
                            $suffix = 1;
                            while (Classes::where('name', $targetName)->exists()) {
                                $targetName = $baseName . ' (' . $suffix . ')';
                                $suffix++;
                            }

                            $targetClass = Classes::create([
                                'name' => $targetName,
                                'grade' => $targetGrade,
                                'major' => $class->major,
                                'code' => $code,
                                'academic_year' => $nextAcademicYear,
                                'is_archived' => false,
                                'created_by' => Auth::id(),
                            ]);

                            $nextYearClasses = $nextYearClasses->put($groupKey, collect([$targetClass]));
                        }

                        // Cek apakah sudah ada record di tahun ajaran berikutnya untuk kelas tujuan ini
                        $existsNext = DB::table('student_class')
                            ->where('class_id', $targetClass->id)
                            ->where('student_id', $row->student_id)
                            ->where('academic_year', $nextAcademicYear)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existsNext) {
                            continue;
                        }

                        DB::table('student_class')->insert([
                            'id' => Str::uuid(),
                            'class_id' => $targetClass->id,
                            'student_id' => $row->student_id,
                            'academic_year' => $nextAcademicYear,
                            'semester' => 'ganjil',
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

                    // Arsipkan kelas lama setelah memproses semua siswa
                    $class->is_archived = true;
                    $class->save();
                }
            });

            // Tambahan: buat kelas X (grade 10) kosong untuk tahun akademik berikutnya per jurusan,
            // berdasarkan kelas grade 10 yang ada di tahun akademik saat ini.
            $grade10Classes = Classes::where('academic_year', $currentAcademicYear)
                ->where('grade', 10)
                ->get()
                ->groupBy('major');

            foreach ($grade10Classes as $major => $grade10Group) {
                $existsNextYearGrade10 = Classes::where('academic_year', $nextAcademicYear)
                    ->where('grade', 10)
                    ->where('major', $major)
                    ->exists();

                if ($existsNextYearGrade10) {
                    continue;
                }

                $sampleClass = $grade10Group->first();

                // Generate unique code untuk kelas baru
                $code = null;
                do {
                    $code = 'CLS-' . strtoupper(Str::random(6));
                } while (Classes::where('code', $code)->exists());

                // Pastikan nama kelas baru unik dengan format "X {short_major} {nextAcademicYear}"
                $majorShortMap = [
                    'Teknik Komputer & Jaringan' => 'TKJ',
                    'Teknik Bisnis Sepeda Motor' => 'TSM',
                    'Teknik Kendaraan Ringan Otomotif' => 'TKR',
                    'Teknik Kimia Industri' => 'KI',
                ];
                $majorLabel = $majorShortMap[$major] ?? $major;
                $baseName = 'X ' . $majorLabel . ' ' . $nextAcademicYear;
                $targetName = $baseName;
                $suffix = 1;
                while (Classes::where('name', $targetName)->exists()) {
                    $targetName = $baseName . ' (' . $suffix . ')';
                    $suffix++;
                }

                Classes::create([
                    'name' => $targetName,
                    'grade' => 10,
                    'major' => $major,
                    'code' => $code,
                    'academic_year' => $nextAcademicYear,
                    'is_archived' => false,
                    'created_by' => Auth::id(),
                ]);
            }

            // Hapus semua kelas yang benar-benar tidak memiliki siswa sama sekali (kelas kosong)
            // Kriteria: tidak memiliki relasi students pada pivot student_class
            // KECUALI kelas grade 10 pada tahun ajaran baru ($nextAcademicYear),
            // karena kelas tersebut memang disiapkan kosong untuk siswa baru.
            $emptyClasses = Classes::whereDoesntHave('students')
                ->where(function ($query) use ($nextAcademicYear) {
                    $query->where('academic_year', '!=', $nextAcademicYear)
                          ->orWhere('grade', '!=', 10);
                })
                ->get();

            foreach ($emptyClasses as $emptyClass) {
                // Pastikan kembali tidak ada data di pivot untuk keamanan tambahan
                $hasPivot = DB::table('student_class')
                    ->where('class_id', $emptyClass->id)
                    ->exists();

                if (!$hasPivot) {
                    $emptyClass->delete();
                }
            }

            return redirect()->route('classes.index')
                ->with('success', 'Semua siswa berhasil dipromosikan ke kelas dengan grade berikutnya untuk tahun akademik baru (jika kelas tujuan tersedia).');
        } catch (\Exception $e) {
            return redirect()->route('classes.index')
                ->with('error', 'Terjadi kesalahan saat melakukan naik kelas masal: ' . $e->getMessage());
        }
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
