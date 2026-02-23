<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Helpers\AcademicYearHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class ScheduleController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('schedules.index');
        // Get all active classes (not archived)
        $classes = Classes::with(['students'])
                        ->where(function($query) {
                            $query->whereNull('is_archived')
                                  ->orWhere('is_archived', false);
                        })
                        ->orderBy('name')
                        ->get();
        
        // Get selected class (default to first class if exists)
        $selectedClassId = $request->get('class_id', $classes->first()?->id);
        $selectedClass = $classes->find($selectedClassId);

        // Tahun akademik filter (default dari semester aktif)
        $currentSemester = Semester::getCurrentActiveSemester();
        $selectedAcademicYear = $request->get('academic_year', 
            $currentSemester ? $currentSemester->academic_year : AcademicYearHelper::getCurrentAcademicYear()
        );

        // Tentukan semester terpilih
        $selectedSemester = $request->get('semester');
        if (!$selectedSemester) {
            // Gunakan semester aktif dari model Semester
            $currentSemester = Semester::getCurrentActiveSemester();
            if ($currentSemester) {
                $selectedSemester = ucfirst($currentSemester->semester_type);
            } else {
                // Fallback ke logic lama jika tidak ada semester aktif
                $ganjilValues = ['1', 'ganjil', 1];
                $genapValues = ['2', 'genap', 2];

                $hasActiveGenap = DB::table('student_class')
                    ->where('academic_year', $selectedAcademicYear)
                    ->whereIn('semester', $genapValues)
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->exists();

                $hasActiveGanjil = DB::table('student_class')
                    ->where('academic_year', $selectedAcademicYear)
                    ->where(function ($q) use ($ganjilValues) {
                        $q->whereIn('semester', $ganjilValues)
                          ->orWhereNull('semester')
                          ->orWhere('semester', '');
                    })
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->exists();

                if ($hasActiveGenap && !$hasActiveGanjil) {
                    $selectedSemester = 'Genap';
                } else {
                    $selectedSemester = 'Ganjil';
                }
            }
        }
        
        $schedules = collect();
        
        if ($selectedClass) {
            $query = Schedule::with(['classRoom', 'subject', 'teacher'])
                           ->where('class_id', $selectedClassId);
            
            // Filter academic_year jika ada
            if ($selectedAcademicYear) {
                $query->where('academic_year', $selectedAcademicYear);
            }

            // Filter semester jika ada
            if ($selectedSemester) {
                if (strtolower($selectedSemester) === 'ganjil') {
                    $query->where(function($q) {
                        $q->whereIn('semester', ['Ganjil', 'ganjil', '1', 1])
                          ->orWhereNull('semester')
                          ->orWhere('semester', '');
                    });
                } elseif (strtolower($selectedSemester) === 'genap') {
                    $query->whereIn('semester', ['Genap', 'genap', '2', 2]);
                }
            }
            
            // Filter pencarian berdasarkan nama guru, mata pelajaran, dan hari
            if ($request->has('q') && !empty($request->q)) {
                $searchTerm = $request->q;
                $query->where(function($q) use ($searchTerm) {
                    $q->whereHas('teacher', function($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('nip', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('subject', function($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('day', 'LIKE', '%' . $searchTerm . '%');
                });
            }
            
            $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                  ->orderBy('start_time');
            
            // Support per_page parameter
            $perPage = $request->get('per_page', 10);
            $schedules = $query->paginate($perPage);
        }
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'schedules' => $schedules->items() ?? [],
                'pagination' => [
                    'current_page' => $schedules->currentPage() ?? 1,
                    'per_page' => $schedules->perPage() ?? 10,
                    'total' => $schedules->total() ?? 0,
                    'last_page' => $schedules->lastPage() ?? 1,
                ],
                'currentPage' => $schedules->currentPage() ?? 1,
                'perPage' => $schedules->perPage() ?? 10,
            ]);
        }
        
        $subjects = Subject::all();
        $teachers = Teacher::whereHas('user', function($query) {
            $query->where('status', 1);
        })->get();
        return view('schedules.index', compact('schedules', 'classes', 'selectedClass', 'subjects', 'teachers', 'selectedSemester', 'selectedAcademicYear'));
    }
    
    public function getSchedulesByClass(Request $request, $classId)
    {
        $query = Schedule::with(['classRoom', 'subject', 'teacher'])
                        ->where('class_id', $classId);
        
        $selectedSemester = $request->get('semester');
        $selectedAcademicYear = $request->get('academic_year');

        if ($selectedAcademicYear) {
            $query->where('academic_year', $selectedAcademicYear);
        }

        if ($selectedSemester) {
            if (strtolower($selectedSemester) === 'ganjil') {
                $query->where(function($q) {
                    $q->whereIn('semester', ['Ganjil', 'ganjil', '1', 1])
                      ->orWhereNull('semester')
                      ->orWhere('semester', '');
                });
            } elseif (strtolower($selectedSemester) === 'genap') {
                $query->whereIn('semester', ['Genap', 'genap', '2', 2]);
            }
        }
        
        // Filter pencarian
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('teacher', function($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('nip', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('subject', function($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('day', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
              ->orderBy('start_time');
        
        $schedules = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'schedules' => $schedules->items(),
            'pagination' => [
                'current_page' => $schedules->currentPage(),
                'per_page' => $schedules->perPage(),
                'total' => $schedules->total(),
                'last_page' => $schedules->lastPage(),
            ],
            'currentPage' => $schedules->currentPage(),
            'perPage' => $schedules->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
            $classes = Classes::where(function($query) {
                            $query->whereNull('is_archived')
                                  ->orWhere('is_archived', false);
                        })
                        ->orderBy('name')
                        ->get();
            $subjects = Subject::orderBy('name')->get();
            $teachers = Teacher::whereHas('user', function($query) {
                $query->where('status', 1);
            })->get();
            
            $view = view('schedules._form', [
                'schedule' => null,
                'action' => route('schedules.store'),
                'method' => 'POST',
                'title' => __('index.add_schedule'),
                'classes' => $classes,
                'subjects' => $subjects,
                'teachers' => $teachers,
                'activeSemester' => Semester::getCurrentActiveSemester()
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.add_schedule')
            ]);
        }
        
        return view('schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Schedule store request', [
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'is_ajax' => $request->ajax()
        ]);

        $validated = $request->validate([
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'class_id' => 'required',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'academic_year' => 'sometimes|required',
            'semester' => 'sometimes|required|in:Ganjil,Genap',
        ]);

        \Log::info('Validation passed', [
            'validated_data' => $validated
        ]);

        // Set academic_year dan semester dari semester aktif jika tidak ada
        $activeSemester = Semester::getCurrentActiveSemester();
        if ($activeSemester) {
            $validated['academic_year'] = $validated['academic_year'] ?? $activeSemester->academic_year;
            $validated['semester'] = $validated['semester'] ?? ucfirst($activeSemester->semester_type);
        } else {
            // Fallback ke helper jika tidak ada semester aktif
            $validated['academic_year'] = $validated['academic_year'] ?? AcademicYearHelper::getCurrentAcademicYear();
            $validated['semester'] = $validated['semester'] ?? 'Ganjil';
        }

        // Cek apakah jadwal bentrok dengan jadwal lain (guru mengajar di kelas lain pada waktu yang sama)
        $conflictingTeacherSchedule = Schedule::where('teacher_id', $request->teacher_id)
        ->where('day', $request->day)
        ->where('academic_year', $validated['academic_year'])
        ->where('semester', $validated['semester'])
        ->where(function ($query) use ($request) {
            $query->where(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
                });
        })->first();

        if ($conflictingTeacherSchedule) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['teacher_id' => ['Guru sudah memiliki jadwal mengajar pada waktu yang sama!']]
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Guru sudah memiliki jadwal mengajar pada waktu yang sama!')
                ->withInput();
        }

        // Cek apakah kelas sudah memiliki jadwal pada waktu yang sama
        $conflictingClassSchedule = Schedule::where('class_id', $request->class_id)
            ->where('day', $request->day)
            ->where('academic_year', $validated['academic_year'])
            ->where('semester', $validated['semester'])
            ->where(function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                    });
            })->first();

        if ($conflictingClassSchedule) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['class_id' => ['Kelas sudah memiliki jadwal pelajaran pada waktu yang sama!']]
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Kelas sudah memiliki jadwal pelajaran pada waktu yang sama!')
                ->withInput();
        }

        try {
            $schedules = Schedule::create($validated);
            
            \Log::info('Schedule created successfully', [
                'schedule_id' => $schedules->id,
                'validated_data' => $validated
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
                    'data' => $schedules
                ]);
            }

            if ($schedules) {
                return redirect()->route('schedules.index')->with('success', 'Data Berhasil Disimpan');
            } else {
                return redirect()->route('schedules.index')->with('error', 'Data Gagal Disimpan');
            }
        } catch (\Exception $e) {
            \Log::error('Schedule creation failed', [
                'error' => $e->getMessage(),
                'validated_data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                    'errors' => ['general' => ['Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']]
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        try {
            $schedule = Schedule::with(['classRoom', 'subject', 'teacher'])->findOrFail($id);
            
            if (request()->ajax()) {
                $classes = Classes::where(function($query) {
                                $query->whereNull('is_archived')
                                      ->orWhere('is_archived', false);
                            })
                            ->orderBy('name')
                            ->get();
                $subjects = Subject::orderBy('name')->get();
                $teachers = Teacher::whereHas('user', function($query) {
                    $query->where('status', 1);
                })->get();
                
                $view = view('schedules._form', [
                    'schedule' => $schedule,
                    'action' => route('schedules.update', $schedule->id),
                    'method' => 'PUT',
                    'title' => __('index.edit_schedule'),
                    'classes' => $classes,
                    'subjects' => $subjects,
                    'teachers' => $teachers,
                    'activeSemester' => Semester::getCurrentActiveSemester()
                ])->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $view,
                    'title' => __('index.edit_schedule')
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $schedule->id,
                    'subject_id' => $schedule->subject_id,
                    'teacher_id' => $schedule->teacher_id,
                    'class_id' => $schedule->class_id,
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'academic_year' => $schedule->academic_year ?? AcademicYearHelper::getCurrentAcademicYear()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'class_id' => 'required',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'academic_year' => 'sometimes|required',
            'semester' => 'sometimes|required|in:Ganjil,Genap',
        ]);

        // Set academic_year dan semester dari semester aktif jika tidak ada
        $activeSemester = Semester::getCurrentActiveSemester();
        if ($activeSemester) {
            $validated['academic_year'] = $validated['academic_year'] ?? $activeSemester->academic_year;
            $validated['semester'] = $validated['semester'] ?? ucfirst($activeSemester->semester_type);
        } else {
            // Fallback ke helper jika tidak ada semester aktif
            $validated['academic_year'] = $validated['academic_year'] ?? AcademicYearHelper::getCurrentAcademicYear();
            $validated['semester'] = $validated['semester'] ?? 'Ganjil';
        }

        try {
            $schedules = Schedule::findOrFail($id);
            
            // Cek apakah jadwal bentrok dengan jadwal lain (guru mengajar di kelas lain pada waktu yang sama)
            // Kecualikan jadwal yang sedang diedit dari pengecekan
            $conflictingTeacherSchedule = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $request->day)
                ->where('academic_year', $validated['academic_year'])
                ->where('semester', $validated['semester'])
                ->where('id', '!=', $id)
                ->where(function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                    });
                })->first();

            if ($conflictingTeacherSchedule) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['teacher_id' => ['Guru sudah memiliki jadwal mengajar pada waktu yang sama!']]
                    ], 422);
                }
                return redirect()->back()
                    ->with('error', 'Guru sudah memiliki jadwal mengajar pada waktu yang sama!')
                    ->withInput();
            }

            // Cek apakah kelas sudah memiliki jadwal pada waktu yang sama
            // Kecualikan jadwal yang sedang diedit dari pengecekan
            $conflictingClassSchedule = Schedule::where('class_id', $request->class_id)
                ->where('day', $request->day)
                ->where('academic_year', $validated['academic_year'])
                ->where('semester', $validated['semester'])
                ->where('id', '!=', $id)
                ->where(function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                    });
                })->first();

            if ($conflictingClassSchedule) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['class_id' => ['Kelas sudah memiliki jadwal pelajaran pada waktu yang sama!']]
                    ], 422);
                }
                return redirect()->back()
                    ->with('error', 'Kelas sudah memiliki jadwal pelajaran pada waktu yang sama!')
                    ->withInput();
            }

            // Update jadwal
            $updated = $schedules->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diperbarui',
                    'data' => $schedules->fresh()
                ]);
            }

            if ($updated) {
                return redirect()->route('schedules.index')->with('success', 'Data Berhasil Diperbarui');
            } else {
                return redirect()->route('schedules.index')->with('error', 'Data Gagal Diperbarui');
            }
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('schedules.index')->with('error', 'Gagal memperbarui data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $schedules = Schedule::findOrFail($id);
            $schedules->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }
            
            return redirect()->route('schedules.index')->with('success', 'Data berhasil dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('schedules.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export schedules to Excel
     */
    public function exportExcel(Request $request)
    {
        // TODO: Implement Excel export functionality
        return response()->json([
            'success' => false,
            'message' => 'Export Excel functionality coming soon!'
        ]);
    }

    /**
     * Print schedules to PDF
     */
    public function printPDF(Request $request)
    {
        // TODO: Implement PDF print functionality
        return response()->json([
            'success' => false,
            'message' => 'Print PDF functionality coming soon!'
        ]);
    }
}
