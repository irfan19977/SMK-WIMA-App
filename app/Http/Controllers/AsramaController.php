<?php

namespace App\Http\Controllers;

use App\Models\Asrama;
use App\Models\AssignAsrama;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AsramaController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('asrama.index');
        $teachers = Teacher::with('user')->get();
        
        // Tambahkan eager loading untuk teacher
        $query = Asrama::with('teacher.user');

        // Filter pencarian berdasarkan nama dan code
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $asramas = $query->paginate(10);

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'asramas' => $asramas->items(),
                'pagination' => [
                    'current_page' => $asramas->currentPage(),
                    'per_page' => $asramas->perPage(),
                    'total' => $asramas->total(),
                    'last_page' => $asramas->lastPage(),
                ],
                'currentPage' => $asramas->currentPage(),
                'perPage' => $asramas->perPage(),
            ]);
        }

        // Return view normal untuk non-AJAX request
        return view('asrama.index', compact('asramas', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teacher,id',
        ]);

        $asramas = Asrama::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'created_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asrama berhasil ditambahkan.',
                'asramas' => $asramas,
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Asrama created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asrama = Asrama::findOrFail($id);
        
        // Get assigned students for this asrama
        $assignedStudents = AssignAsrama::where('asrama_id', $id)
            ->with(['student.user', 'teacher.user'])
            ->get();
        
        // Get students that are assigned to this asrama
        $students = $assignedStudents->pluck('student');
        
        // Get available students (not assigned to any asrama or not assigned to this asrama)
        $availableStudents = Student::whereDoesntHave('assignAsrama')
            ->orWhereHas('assignAsrama', function($query) use ($id) {
                $query->where('asrama_id', '!=', $id);
            })
            ->with('user')
            ->get();
        
        // Get available teachers
        $availableTeachers = Teacher::with('user')->get();
        
        return view('asrama.show', compact('asrama', 'students', 'availableStudents', 'availableTeachers', 'assignedStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asramas = Asrama::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'asramas' => $asramas,
                'title' => 'Edit Asrama'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asramas = Asrama::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teacher,id',
        ]);

        $asramas->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'updated_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asrama berhasil diperbarui.',
                'asramas' => $asramas
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Asrama updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asramas = Asrama::findOrFail($id);
        $asramas->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asrama berhasil dihapus.'
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Asrama deleted successfully.');
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

        $asrama = Asrama::findOrFail($id);

        foreach ($request->student_ids as $studentId) {
            // Check if student is already assigned to this asrama
            $existingAssignment = AssignAsrama::where('student_id', $studentId)
                ->where('asrama_id', $id)
                ->first();

            if (!$existingAssignment) {
                AssignAsrama::create([
                    'student_id' => $studentId,
                    'asrama_id' => $id,
                    'name' => $asrama->name,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan ke asrama.',
            ]);
        }

        return redirect()->route('asrama.show', $id)->with('success', 'Siswa berhasil ditambahkan ke asrama.');
    }

    /**
     * Remove student from asrama
     */
    public function removeStudent(Request $request, string $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id'
        ]);

        $assignment = AssignAsrama::where('student_id', $request->student_id)
            ->where('asrama_id', $id)
            ->first();

        if ($assignment) {
            $assignment->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus dari asrama.'
            ]);
        }

        return redirect()->route('asrama.show', $id)->with('success', 'Siswa berhasil dihapus dari asrama.');
    }

    public function getGrades(Request $request, $asramaId)
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

            // Get students in this asrama
            $assignedStudents = AssignAsrama::where('asrama_id', $asramaId)
                ->with(['student'])
                ->get();

            $studentsData = [];

            foreach ($assignedStudents as $assignment) {
                $student = $assignment->student;
                
                // Get existing grade for this student, month, and academic year
                $existingGrade = DB::table('asrama_grades')
                    ->where('student_id', $student->id)
                    ->where('month', $month)
                    ->where('academic_year', $academicYear)
                    ->whereNull('deleted_at')
                    ->first();

                $gradeData = [
                    'id' => $existingGrade->id ?? null,
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'student_nisn' => $student->nisn,
                    'sholat_tahajud' => $existingGrade->sholat_tahajud ?? null,
                    'sholat_dhuha' => $existingGrade->sholat_dhuha ?? null,
                    'sholat_syuruq' => $existingGrade->sholat_syuruq ?? null,
                    'sunnah_rawatib' => $existingGrade->sunnah_rawatib ?? null,
                    'puasa' => $existingGrade->puasa ?? null,
                    'dzikir_pagi_petang' => $existingGrade->dzikir_pagi_petang ?? null,
                    'adab_berbicara' => $existingGrade->adab_berbicara ?? null,
                    'adab_bersikap' => $existingGrade->adab_bersikap ?? null,
                    'kejujuran' => $existingGrade->kejujuran ?? null,
                    'waktu_tidur' => $existingGrade->waktu_tidur ?? null,
                    'pelaksanaan_piket' => $existingGrade->pelaksanaan_piket ?? null,
                    'kegiatan_mahad' => $existingGrade->kegiatan_mahad ?? null,
                    'jasmani_penampilan' => $existingGrade->jasmani_penampilan ?? null,
                    'kerapian_lemari' => $existingGrade->kerapian_lemari ?? null,
                    'kerapian_ranjang' => $existingGrade->kerapian_ranjang ?? null,
                    'bahasa_arab' => $existingGrade->bahasa_arab ?? null,
                    'bahasa_inggris' => $existingGrade->bahasa_inggris ?? null,
                    'catatan' => $existingGrade->catatan ?? '',
                ];

                $studentsData[] = $gradeData;
            }

            return response()->json([
                'success' => true,
                'data' => $studentsData,
                'message' => 'Data berhasil dimuat'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading asrama grades: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data nilai asrama',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store or update asrama grade
     */
    public function storeGrade(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:student,id',
                'asrama_id' => 'required|exists:asrama,id',
                'month' => 'required|integer|min:1|max:12',
                'academic_year' => 'required|string',
                'sholat_tahajud' => 'nullable|integer|min:0|max:100',
                'sholat_dhuha' => 'nullable|integer|min:0|max:100',
                'sholat_syuruq' => 'nullable|integer|min:0|max:100',
                'sunnah_rawatib' => 'nullable|integer|min:0|max:100',
                'puasa' => 'nullable|integer|min:0|max:100',
                'dzikir_pagi_petang' => 'nullable|integer|min:0|max:100',
                'adab_berbicara' => 'nullable|integer|min:0|max:100',
                'adab_bersikap' => 'nullable|integer|min:0|max:100',
                'kejujuran' => 'nullable|integer|min:0|max:100',
                'waktu_tidur' => 'nullable|integer|min:0|max:100',
                'pelaksanaan_piket' => 'nullable|integer|min:0|max:100',
                'kegiatan_mahad' => 'nullable|integer|min:0|max:100',
                'jasmani_penampilan' => 'nullable|integer|min:0|max:100',
                'kerapian_lemari' => 'nullable|integer|min:0|max:100',
                'kerapian_ranjang' => 'nullable|integer|min:0|max:100',
                'bahasa_arab' => 'nullable|integer|min:0|max:100',
                'bahasa_inggris' => 'nullable|integer|min:0|max:100',
                'catatan' => 'required|string|max:1000',
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
            $existingGrade = DB::table('asrama_grades')
                ->where('student_id', $request->student_id)
                ->where('month', $request->month)
                ->where('academic_year', $request->academic_year)
                ->whereNull('deleted_at')
                ->first();

            $gradeData = [
                'student_id' => $request->student_id,
                'month' => $request->month,
                'month_name' => $monthNames[$request->month],
                'academic_year' => $request->academic_year,
                'sholat_tahajud' => $request->sholat_tahajud,
                'sholat_dhuha' => $request->sholat_dhuha,
                'sholat_syuruq' => $request->sholat_syuruq,
                'sunnah_rawatib' => $request->sunnah_rawatib,
                'puasa' => $request->puasa,
                'dzikir_pagi_petang' => $request->dzikir_pagi_petang,
                'adab_berbicara' => $request->adab_berbicara,
                'adab_bersikap' => $request->adab_bersikap,
                'kejujuran' => $request->kejujuran,
                'waktu_tidur' => $request->waktu_tidur,
                'pelaksanaan_piket' => $request->pelaksanaan_piket,
                'kegiatan_mahad' => $request->kegiatan_mahad,
                'jasmani_penampilan' => $request->jasmani_penampilan,
                'kerapian_lemari' => $request->kerapian_lemari,
                'kerapian_ranjang' => $request->kerapian_ranjang,
                'bahasa_arab' => $request->bahasa_arab,
                'bahasa_inggris' => $request->bahasa_inggris,
                'catatan' => $request->catatan,
                'created_by' => auth()->user()->name ?? 'System',
                'updated_by' => auth()->user()->name ?? 'System',
            ];

            if ($existingGrade) {
                // Update existing grade
                $gradeData['updated_at'] = now();
                
                DB::table('asrama_grades')
                    ->where('id', $existingGrade->id)
                    ->update($gradeData);

                $message = 'Nilai asrama berhasil diperbarui';
            } else {
                // Create new grade
                $gradeData['id'] = \Str::uuid();
                $gradeData['created_at'] = now();
                $gradeData['updated_at'] = now();
                
                DB::table('asrama_grades')->insert($gradeData);

                $message = 'Nilai asrama berhasil disimpan';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            \Log::error('Error storing asrama grade: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai asrama',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update asrama grade
     */
    public function updateGrade(Request $request, $gradeId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sholat_tahajud' => 'nullable|integer|min:0|max:100',
                'sholat_dhuha' => 'nullable|integer|min:0|max:100',
                'sholat_syuruq' => 'nullable|integer|min:0|max:100',
                'sunnah_rawatib' => 'nullable|integer|min:0|max:100',
                'puasa' => 'nullable|integer|min:0|max:100',
                'dzikir_pagi_petang' => 'nullable|integer|min:0|max:100',
                'adab_berbicara' => 'nullable|integer|min:0|max:100',
                'adab_bersikap' => 'nullable|integer|min:0|max:100',
                'kejujuran' => 'nullable|integer|min:0|max:100',
                'waktu_tidur' => 'nullable|integer|min:0|max:100',
                'pelaksanaan_piket' => 'nullable|integer|min:0|max:100',
                'kegiatan_mahad' => 'nullable|integer|min:0|max:100',
                'jasmani_penampilan' => 'nullable|integer|min:0|max:100',
                'kerapian_lemari' => 'nullable|integer|min:0|max:100',
                'kerapian_ranjang' => 'nullable|integer|min:0|max:100',
                'bahasa_arab' => 'nullable|integer|min:0|max:100',
                'bahasa_inggris' => 'nullable|integer|min:0|max:100',
                'catatan' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $grade = DB::table('asrama_grades')->where('id', $gradeId)->first();
            
            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai tidak ditemukan'
                ], 404);
            }

            $updateData = [
                'sholat_tahajud' => $request->sholat_tahajud,
                'sholat_dhuha' => $request->sholat_dhuha,
                'sholat_syuruq' => $request->sholat_syuruq,
                'sunnah_rawatib' => $request->sunnah_rawatib,
                'puasa' => $request->puasa,
                'dzikir_pagi_petang' => $request->dzikir_pagi_petang,
                'adab_berbicara' => $request->adab_berbicara,
                'adab_bersikap' => $request->adab_bersikap,
                'kejujuran' => $request->kejujuran,
                'waktu_tidur' => $request->waktu_tidur,
                'pelaksanaan_piket' => $request->pelaksanaan_piket,
                'kegiatan_mahad' => $request->kegiatan_mahad,
                'jasmani_penampilan' => $request->jasmani_penampilan,
                'kerapian_lemari' => $request->kerapian_lemari,
                'kerapian_ranjang' => $request->kerapian_ranjang,
                'bahasa_arab' => $request->bahasa_arab,
                'bahasa_inggris' => $request->bahasa_inggris,
                'catatan' => $request->catatan,
                'updated_by' => auth()->user()->name ?? 'System',
                'updated_at' => now(),
            ];

            DB::table('asrama_grades')
                ->where('id', $gradeId)
                ->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Nilai asrama berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating asrama grade: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nilai asrama',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update asrama grades
     */
    public function bulkUpdateGrades(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'grades' => 'required|array|min:1',
                'grades.*.student_id' => 'required|exists:student,id',
                'grades.*.month' => 'required|integer|min:1|max:12',
                'grades.*.academic_year' => 'required|string',
                'grades.*.asrama_id' => 'required|exists:asrama,id',
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
                $existingGrade = DB::table('asrama_grades')
                    ->where('student_id', $gradeData['student_id'])
                    ->where('month', $gradeData['month'])
                    ->where('academic_year', $gradeData['academic_year'])
                    ->whereNull('deleted_at')
                    ->first();

                $data = [
                    'student_id' => $gradeData['student_id'],
                    'month' => $gradeData['month'],
                    'month_name' => $monthNames[$gradeData['month']],
                    'academic_year' => $gradeData['academic_year'],
                    'sholat_tahajud' => $gradeData['sholat_tahajud'] ?? null,
                    'sholat_dhuha' => $gradeData['sholat_dhuha'] ?? null,
                    'sholat_syuruq' => $gradeData['sholat_syuruq'] ?? null,
                    'sunnah_rawatib' => $gradeData['sunnah_rawatib'] ?? null,
                    'puasa' => $gradeData['puasa'] ?? null,
                    'dzikir_pagi_petang' => $gradeData['dzikir_pagi_petang'] ?? null,
                    'adab_berbicara' => $gradeData['adab_berbicara'] ?? null,
                    'adab_bersikap' => $gradeData['adab_bersikap'] ?? null,
                    'kejujuran' => $gradeData['kejujuran'] ?? null,
                    'waktu_tidur' => $gradeData['waktu_tidur'] ?? null,
                    'pelaksanaan_piket' => $gradeData['pelaksanaan_piket'] ?? null,
                    'kegiatan_mahad' => $gradeData['kegiatan_mahad'] ?? null,
                    'jasmani_penampilan' => $gradeData['jasmani_penampilan'] ?? null,
                    'kerapian_lemari' => $gradeData['kerapian_lemari'] ?? null,
                    'kerapian_ranjang' => $gradeData['kerapian_ranjang'] ?? null,
                    'bahasa_arab' => $gradeData['bahasa_arab'] ?? null,
                    'bahasa_inggris' => $gradeData['bahasa_inggris'] ?? null,
                    'catatan' => $gradeData['catatan'] ?? '',
                    'updated_by' => auth()->user()->name ?? 'System',
                ];

                if ($existingGrade) {
                    // Update existing grade
                    $data['updated_at'] = now();
                    
                    DB::table('asrama_grades')
                        ->where('id', $existingGrade->id)
                        ->update($data);
                    
                    $updatedCount++;
                } else {
                    // Create new grade
                    $data['id'] = \Str::uuid();
                    $data['created_by'] = auth()->user()->name ?? 'System';
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    
                    DB::table('asrama_grades')->insert($data);
                    
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
            \Log::error('Error bulk updating asrama grades: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai asrama',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete asrama grade
     */
    public function deleteGrade($gradeId)
    {
        try {
            $grade = DB::table('asrama_grades')->where('id', $gradeId)->first();
            
            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai tidak ditemukan'
                ], 404);
            }

            // Soft delete
            DB::table('asrama_grades')
                ->where('id', $gradeId)
                ->update([
                    'deleted_at' => now(),
                    'updated_by' => auth()->user()->name ?? 'System'
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Nilai asrama berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting asrama grade: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus nilai asrama',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}