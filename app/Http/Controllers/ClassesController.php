<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        try {
            $classes = Classes::findOrFail($id);
            
            // Get students in this class through pivot table
            $students = Student::whereHas('classes', function($query) use ($id) {
                $query->where('classes.id', $id);
            })->with('user')->get();
            
            // Get available students (not in any class or not in this class)
            $availableStudents = Student::whereDoesntHave('classes', function($query) use ($id) {
                $query->where('classes.id', $id);
            })->with('user')->get();
            
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
                    })
                ]);
            }
            
            return view('classes.show', compact('classes', 'students', 'availableStudents'));
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $classes = Classes::findOrFail($id);

        return view('classes.edit', compact('classes'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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

    /**
     * Bulk assign students to a class
     */
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

    /**
     * Remove a student from a class - Updated Method
     */
    /**
 * Remove a student from a class - Updated Method
 */
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
