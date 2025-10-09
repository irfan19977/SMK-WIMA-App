<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Helpers\AcademicYearHelper;
use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerAssign;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Teacher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EkstrakurikulerController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('ekstrakurikuler.index');
        $teachers = Teacher::with('user')->get();
        
        // Tambahkan eager loading untuk teacher
        $query = Ekstrakurikuler::with('teacher.user');

        // Filter pencarian berdasarkan nama dan code
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $ekstrakurikulers = $query->paginate(10);

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'ekstrakurikulers' => $ekstrakurikulers->items(),
                'pagination' => [
                    'current_page' => $ekstrakurikulers->currentPage(),
                    'per_page' => $ekstrakurikulers->perPage(),
                    'total' => $ekstrakurikulers->total(),
                    'last_page' => $ekstrakurikulers->lastPage(),
                ],
                'currentPage' => $ekstrakurikulers->currentPage(),
                'perPage' => $ekstrakurikulers->perPage(),
            ]);
        }

        // Return view normal untuk non-AJAX request
        return view('ekstrakurikuler.index', compact('ekstrakurikulers', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teacher,id',
        ]);

        $ekstrakurikulers = Ekstrakurikuler::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'created_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ekstrakurikuler berhasil ditambahkan.',
                'ekstrakurikulers' => $ekstrakurikulers,
            ]);
        }

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler created successfully.');
    }

    public function show(string $id)
    {
        $ekstrakurikulers = Ekstrakurikuler::findOrFail($id);
        
        // Get assigned students for this asrama
        $assignedStudents = EkstrakurikulerAssign::where('ekstrakurikuler_id', $id)
            ->with(['student.user', 'teacher.user'])
            ->get();
        
        // Get students that are assigned to this asrama
        $students = $assignedStudents->pluck('student');
        
        // Get available students (not assigned to any asrama or not assigned to this asrama)
        $availableStudents = Student::whereDoesntHave('ekstrakurikulerAssign')
            ->orWhereHas('ekstrakurikulerAssign', function($query) use ($id) {
                $query->where('ekstrakurikuler_id', '!=', $id);
            })
            ->with('user')
            ->get();
        
        // Get available teachers
        $availableTeachers = Teacher::with('user')->get();
        
        return view('ekstrakurikuler.show', compact('ekstrakurikulers', 'students', 'availableStudents', 'availableTeachers', 'assignedStudents'));
    }

    public function edit(string $id)
    {
        $ekstrakurikulers = Ekstrakurikuler::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'ekstrakurikulers' => $ekstrakurikulers,
                'title' => 'Edit Ekstrakurikuler',
            ]);
        }
    }

    public function update(Request $request, string $id)
    {
        $ekstrakurikulers = Ekstrakurikuler::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teacher,id',
        ]);

        $ekstrakurikulers->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'updated_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ekstrakurikuler berhasil diperbarui.',
                'ekstrakurikulers' => $ekstrakurikulers
            ]);
        }

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler updated successfully.');
    }

    public function destroy(string $id)
    {
        $ekstrakurikulerss = Ekstrakurikuler::findOrFail($id);
        $ekstrakurikulerss->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ekstrakurikuler berhasil dihapus.'
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Ekstrakurikuler deleted successfully.');
    }

    /**
     * Assign students to asrama
     */
    public function bulkAssign(Request $request, string $id)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:student,id',
        ]);

        $ekstrakurikulers = Ekstrakurikuler::findOrFail($id);

        foreach ($request->student_ids as $studentId) {
            // Check if student is already assigned to this asrama
            $existingAssignment = EkstrakurikulerAssign::where('student_id', $studentId)
                ->where('ekstrakurikuler_id', $id)
                ->first();

            if (!$existingAssignment) {
                EkstrakurikulerAssign::create([
                    'student_id' => $studentId,
                    'ekstrakurikuler_id' => $id,
                    'name' => $ekstrakurikulers->name,
                    'academic_year' => AcademicYearHelper::getCurrentAcademicYear(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan ke ekstrakurikuler.',
            ]);
        }

        return redirect()->route('ekstrakurikuler.show', $id)->with('success', 'Siswa berhasil ditambahkan ke ekstrakurikuler.');
    }

//
/**
 * Remove student from ekstrakurikuler
 */
public function removeStudent(Request $request, string $id)
{
    $request->validate([
        'student_id' => 'required|exists:student,id'
    ]);

    $assignment = EkstrakurikulerAssign::where('student_id', $request->student_id)
        ->where('ekstrakurikuler_id', $id)
        ->first();

    if ($assignment) {
        $assignment->delete();
    }

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus dari ekstrakurikuler.'
        ]);
    }

    return redirect()->route('ekstrakurikuler.show', $id)->with('success', 'Siswa berhasil dihapus dari ekstrakurikuler.');
}

/**
 * Get ekstrakurikuler grades for specific month and academic year
 */
public function getGrades(Request $request, $ekstrakurikulerId)
{
    try {
        $month = $request->get('month');
        $academicYear = $request->get('academic_year', date('Y') . '/' . (date('Y') + 1));

        if (!$month) {
            return response()->json([
                'success' => false,
                'message' => 'Bulan harus dipilih'
            ]);
        }

        // Get students in this ekstrakurikuler
        $assignedStudents = EkstrakurikulerAssign::where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->with(['student'])
            ->get();

        $studentsData = [];

        foreach ($assignedStudents as $assignment) {
            $student = $assignment->student;
            
            // Get existing grade for this student, month, and academic year
            $existingGrade = DB::table('ekstrakurikuler_grades')
                ->where('student_id', $student->id)
                ->where('ekstrakurikuler_id', $ekstrakurikulerId)
                ->where('month', $month)
                ->where('academic_year', $academicYear)
                ->first();

            $gradeData = [
                'id' => $existingGrade->id ?? null,
                'student_id' => $student->id,
                'student_name' => $student->name,
                'student_nisn' => $student->nisn,
                'keaktifan' => $existingGrade->keaktifan ?? null,
                'keterampilan' => $existingGrade->keterampilan ?? null,
                'nilai_rapor' => $existingGrade->nilai_rapor ?? null,
                'capaian_kompetensi' => $existingGrade->capaian_kompetensi ?? '',
            ];

            $studentsData[] = $gradeData;
        }

        return response()->json([
            'success' => true,
            'data' => $studentsData,
            'message' => 'Data berhasil dimuat'
        ]);

    } catch (\Exception $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data nilai ekstrakurikuler',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Store or update ekstrakurikuler grade
 */
public function storeGrade(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:student,id',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikuler,id',
            'month' => 'required|integer|min:1|max:12',
            'academic_year' => 'required|string',
            'keaktifan' => 'nullable|integer|min:0|max:100',
            'keterampilan' => 'nullable|integer|min:0|max:100',
            'nilai_rapor' => 'nullable|integer|min:0|max:100',
            'capaian_kompetensi' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Check if grade already exists
        $existingGrade = DB::table('ekstrakurikuler_grades')
            ->where('student_id', $request->student_id)
            ->where('ekstrakurikuler_id', $request->ekstrakurikuler_id)
            ->where('month', $request->month)
            ->where('academic_year', $request->academic_year)
            ->first();

        $gradeData = [
            'student_id' => $request->student_id,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
            'month' => $request->month,
            'month_name' => $monthNames[$request->month],
            'academic_year' => $request->academic_year,
            'keaktifan' => $request->keaktifan,
            'keterampilan' => $request->keterampilan,
            'nilai_rapor' => $request->nilai_rapor,
            'capaian_kompetensi' => $request->capaian_kompetensi,
            'created_by' => Auth::user()->name ?? 'System',
            'updated_by' => Auth::user()->name ?? 'System',
        ];

        if ($existingGrade) {
            // Update existing grade
            $gradeData['updated_at'] = now();
            
            DB::table('ekstrakurikuler_grades')
                ->where('id', $existingGrade->id)
                ->update($gradeData);

            $message = 'Nilai ekstrakurikuler berhasil diperbarui';
        } else {
            // Create new grade
            $gradeData['id'] = Str::uuid();
            $gradeData['created_at'] = now();
            $gradeData['updated_at'] = now();
            
            DB::table('ekstrakurikuler_grades')->insert($gradeData);

            $message = 'Nilai ekstrakurikuler berhasil disimpan';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan nilai ekstrakurikuler',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Update ekstrakurikuler grade
 */
public function updateGrade(Request $request, $gradeId)
{
    try {
        $validator = Validator::make($request->all(), [
            'keaktifan' => 'nullable|integer|min:0|max:100',
            'keterampilan' => 'nullable|integer|min:0|max:100',
            'nilai_rapor' => 'nullable|integer|min:0|max:100',
            'capaian_kompetensi' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $grade = DB::table('ekstrakurikuler_grades')->where('id', $gradeId)->first();
        
        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Nilai tidak ditemukan'
            ], 404);
        }

        $updateData = [
            'keaktifan' => $request->keaktifan,
            'keterampilan' => $request->keterampilan,
            'nilai_rapor' => $request->nilai_rapor,
            'capaian_kompetensi' => $request->capaian_kompetensi,
            'updated_by' => Auth::user()->name ?? 'System',
            'updated_at' => now(),
        ];

        DB::table('ekstrakurikuler_grades')
            ->where('id', $gradeId)
            ->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Nilai ekstrakurikuler berhasil diperbarui'
        ]);

    } catch (\Exception $e) {        
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui nilai ekstrakurikuler',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Bulk update ekstrakurikuler grades
 */
public function bulkUpdateGrades(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'grades' => 'required|array|min:1',
            'grades.*.student_id' => 'required|exists:student,id',
            'grades.*.ekstrakurikuler_id' => 'required|exists:ekstrakurikuler,id',
            'grades.*.month' => 'required|integer|min:1|max:12',
            'grades.*.academic_year' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $processedCount = 0;
        $updatedCount = 0;
        $createdCount = 0;

        DB::beginTransaction();

        foreach ($request->grades as $gradeData) {
            // Check if grade already exists
            $existingGrade = DB::table('ekstrakurikuler_grades')
                ->where('student_id', $gradeData['student_id'])
                ->where('ekstrakurikuler_id', $gradeData['ekstrakurikuler_id'])
                ->where('month', $gradeData['month'])
                ->where('academic_year', $gradeData['academic_year'])
                ->first();

            $data = [
                'student_id' => $gradeData['student_id'],
                'ekstrakurikuler_id' => $gradeData['ekstrakurikuler_id'],
                'month' => $gradeData['month'],
                'month_name' => $monthNames[$gradeData['month']],
                'academic_year' => $gradeData['academic_year'],
                'keaktifan' => $gradeData['keaktifan'] ?? null,
                'keterampilan' => $gradeData['keterampilan'] ?? null,
                'nilai_rapor' => $gradeData['nilai_rapor'] ?? null,
                'capaian_kompetensi' => $gradeData['capaian_kompetensi'] ?? '',
                'updated_by' => Auth::user()->name ?? 'System',
            ];

            if ($existingGrade) {
                // Update existing grade
                $data['updated_at'] = now();
                
                DB::table('ekstrakurikuler_grades')
                    ->where('id', $existingGrade->id)
                    ->update($data);
                
                $updatedCount++;
            } else {
                // Create new grade
                $data['id'] = Str::uuid();
                $data['created_by'] = Auth::user()->name ?? 'System';
                $data['created_at'] = now();
                $data['updated_at'] = now();
                
                DB::table('ekstrakurikuler_grades')->insert($data);
                
                $createdCount++;
            }
            
            $processedCount++;
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Berhasil memproses {$processedCount} data nilai. {$createdCount} data baru dibuat, {$updatedCount} data diperbarui.",
            'processed_count' => $processedCount,
            'created_count' => $createdCount,
            'updated_count' => $updatedCount
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan nilai ekstrakurikuler',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Delete ekstrakurikuler grade
 */
public function deleteGrade($gradeId)
{
    try {
        $grade = DB::table('ekstrakurikuler_grades')->where('id', $gradeId)->first();
        
        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Nilai tidak ditemukan'
            ], 404);
        }

        // Hard delete since there's no deleted_at column in the migration
        DB::table('ekstrakurikuler_grades')
            ->where('id', $gradeId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nilai ekstrakurikuler berhasil dihapus'
        ]);

    } catch (\Exception $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus nilai ekstrakurikuler',
            'error' => $e->getMessage()
        ], 500);
    }
}
    
}
