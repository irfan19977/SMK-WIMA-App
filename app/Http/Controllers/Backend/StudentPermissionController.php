<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AcademicYearHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\LessonAttendance;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentPermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentPermission::with(['student', 'approvedBy']);

        // Search by student name or NISN
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $permissions = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);
        $classes = Classes::all();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $data = [];
            foreach ($permissions as $permission) {
                try {
                    $currentClass = $permission->student->getCurrentClass();
                    $className = $currentClass ? $currentClass->name : '-';
                } catch (\Exception $e) {
                    \Log::error('Error getting current class for student ' . $permission->student_id . ': ' . $e->getMessage());
                    $className = '-';
                }
                
                $data[] = [
                    'id' => $permission->id,
                    'student_nisn' => $permission->student->nisn ?? '-',
                    'student_name' => $permission->student->name ?? '-',
                    'class_name' => $className,
                    'type_label' => $permission->type_label,
                    'type_badge' => $permission->type_badge,
                    'date' => $permission->end_date 
                        ? \Carbon\Carbon::parse($permission->start_date)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($permission->end_date)->format('d M Y')
                        : \Carbon\Carbon::parse($permission->start_date)->format('d M Y'),
                    'reason_truncated' => \Illuminate\Support\Str::limit($permission->reason, 50),
                    'status_label' => $permission->status_label,
                    'status_badge' => $permission->status_badge,
                    'status' => $permission->status,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $permissions->currentPage(),
                    'last_page' => $permissions->lastPage(),
                    'per_page' => $permissions->perPage(),
                    'total' => $permissions->total(),
                ]
            ]);
        }

        return view('izin.index', compact('permissions', 'classes'));
    }

    public function create()
    {
        $classes = Classes::all();
        $students = Student::with('classes')->get();
        
        return response()->json([
            'success' => true,
            'html' => view('izin._form', [
                'action' => route('izin.store'),
                'method' => 'POST',
                'classes' => $classes,
                'students' => $students,
                'permission' => null
            ])->render(),
            'title' => 'Tambah Izin Siswa'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'type' => 'required|in:sakit,agenda,pulang_awal',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        try {
            StudentPermission::create([
                'id' => (string) Str::uuid(),
                'student_id' => $request->student_id,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan izin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $permission = StudentPermission::with('student')->findOrFail($id);
        $classes = Classes::all();
        $students = Student::with('classes')->get();
        
        return response()->json([
            'success' => true,
            'html' => view('izin._form', [
                'action' => route('izin.update', $id),
                'method' => 'PUT',
                'classes' => $classes,
                'students' => $students,
                'permission' => $permission
            ])->render(),
            'title' => 'Edit Izin Siswa'
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = StudentPermission::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:student,id',
            'type' => 'required|in:sakit,agenda,pulang_awal',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $permission->update([
                'student_id' => $request->student_id,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
            ]);

            if ($permission->status === 'approved') {
                $this->syncAttendanceWithPermission($permission);
            }

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui izin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve($id)
    {
        $permission = StudentPermission::findOrFail($id);

        try {
            $permission->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $this->syncAttendanceWithPermission($permission);

            // Kirim notifikasi WA ke orang tua
            try {
                $student = Student::find($permission->student_id);
                if ($student) {
                    app(WhatsAppService::class)->sendPermissionNotification($student, $permission, 'approved');
                }
            } catch (\Exception $e) {
                \Log::warning('WA notification failed for permission approval: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui izin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $permission = StudentPermission::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        try {
            $permission->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Kirim notifikasi WA ke orang tua
            try {
                $student = Student::find($permission->student_id);
                if ($student) {
                    app(WhatsAppService::class)->sendPermissionNotification($student, $permission, 'rejected');
                }
            } catch (\Exception $e) {
                \Log::warning('WA notification failed for permission rejection: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak izin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $permission = StudentPermission::findOrFail($id);

        try {
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus izin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentsByClass(Request $request)
    {
        $classId = $request->class_id;
        $students = Student::whereHas('classes', function($query) use ($classId) {
                $query->where('classes.id', $classId)
                      ->where('student_class.status', 'active');
            })
            ->select('id', 'name', 'nisn')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Sync attendance and lesson attendance records for an approved permission.
     * For sakit/agenda, mark every day in the range as sakit/izin so the student cannot check in.
     */
    private function syncAttendanceWithPermission(StudentPermission $permission)
    {
        // Only fully-absent permission types affect daily attendance
        if ($permission->type === 'pulang_awal') {
            return;
        }

        $student = Student::find($permission->student_id);
        if (!$student) {
            return;
        }

        $class = $student->getCurrentClass();
        if (!$class) {
            return;
        }

        $status = $permission->type === 'sakit' ? 'sakit' : 'izin';

        $start = Carbon::parse($permission->start_date);
        $end = $permission->end_date ? Carbon::parse($permission->end_date) : $start->copy();

        $current = $start->copy();
        while ($current->lte($end)) {
            $dateString = $current->format('Y-m-d');

            // Use the academic year/semester that the permission date falls into
            $dateAcademicYear = AcademicYearHelper::getAcademicYear($current->month, $current->year);
            $dateSemester = AcademicYearHelper::getSemesterFromMonth($current->month);

            // Upsert daily attendance
            Attendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'date' => $dateString,
                ],
                [
                    'check_in_status' => $status,
                    'check_out_status' => $status,
                    'academic_year' => $dateAcademicYear,
                    'semester' => $dateSemester,
                    'updated_by' => Auth::id(),
                ]
            );

            // Upsert lesson attendance for each subject scheduled that day
            $dayName = strtolower($current->locale('id')->isoFormat('dddd'));
            $schedules = Schedule::where('class_id', $class->id)
                ->where('day', $dayName)
                ->where('academic_year', $dateAcademicYear)
                ->where('semester', $dateSemester)
                ->get();

            foreach ($schedules as $schedule) {
                LessonAttendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'class_id' => $class->id,
                        'subject_id' => $schedule->subject_id,
                        'date' => $dateString,
                    ],
                    [
                        'check_in_status' => $status,
                        'academic_year' => $schedule->academic_year,
                        'semester' => $schedule->semester,
                        'updated_by' => Auth::id(),
                    ]
                );
            }

            $current->addDay();
        }
    }
}
