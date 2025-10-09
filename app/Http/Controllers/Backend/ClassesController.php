<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassesController extends Controller
{

    public function index(Request $request)
    {
        if (Auth::user()->hasRole('student')) {
            $student = \App\Models\Student::where('user_id', Auth::id())->first();
            $studentClass = $student ? \App\Models\StudentClass::where('student_id', $student->id)->first() : null;
            
            if ($studentClass) {
                return redirect()->route('classes.show', $studentClass->class_id);
            } else {
                // Jika student tidak memiliki kelas, redirect dengan pesan error
                return redirect()->back()->with('error', 'Anda belum terdaftar di kelas manapun.');
            }
        }
        $query = Classes::query();
        
        // Search filter
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('major', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $classes = $query->paginate(10);
        
        // Return JSON for AJAX requests
        if ($request->ajax() || $request->expectsJson()) {
            $classesData = $classes->getCollection()->map(function($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'major' => $class->major,
                    'code' => $class->code,
                    'grade' => $class->grade,
                ];
            });
            
            return response()->json([
                'success' => true,
                'classes' => $classesData,
                'pagination' => [
                    'current_page' => $classes->currentPage(),
                    'per_page' => $classes->perPage(),
                    'total' => $classes->total(),
                    'last_page' => $classes->lastPage(),
                ]
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
                'name' => 'required|string|max:255',
                'major' => 'required|string|max:255',
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
            ]);

            return redirect()->route('classes.index')->with('success', 'Kelas berhasil dibuat.');
        } catch (\Exception $e) {
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
            
            return view('classes.show', compact('classes', 'students', 'availableStudents', 'attendanceData', 'currentMonth', 'daysInMonth'));
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
            'name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'grade' => 'nullable|integer',
            'academic_year' => 'nullable|string|max:255',
        ]);

        try {
            $classes = Classes::findOrFail($id);
            $classes->update([
                'name' => $request->name,
                'major' => $request->major,
                'grade' => $request->grade,
                'academic_year' => $request->academic_year,
            ]);

            return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
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
                    // Create assignment in pivot table
                    DB::table('student_class')->insert([
                        'id' => Str::uuid(),
                        'class_id' => $class->id,
                        'student_id' => $studentId,
                        'created_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
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
}
