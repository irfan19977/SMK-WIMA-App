<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AcademicYearHelper;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentGrades;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentGradesController extends Controller
{
    /**
     * Menampilkan halaman utama input nilai
     */
    public function index()
    {
        $classes = Classes::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        // Tentukan semester default berdasarkan data student_class
        $academicYear = AcademicYearHelper::getCurrentAcademicYear();
        $ganjilValues = ['1', 'ganjil', 1];
        $genapValues = ['2', 'genap', 2];

        $hasActiveGenap = DB::table('student_class')
            ->where('academic_year', $academicYear)
            ->whereIn('semester', $genapValues)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->exists();

        $hasActiveGanjil = DB::table('student_class')
            ->where('academic_year', $academicYear)
            ->where(function ($q) use ($ganjilValues) {
                $q->whereIn('semester', $ganjilValues)
                  ->orWhereNull('semester')
                  ->orWhere('semester', '');
            })
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->exists();

        $defaultSemester = ($hasActiveGenap && !$hasActiveGanjil) ? 2 : 1;

        return view('grades.index', compact('classes', 'subjects', 'defaultSemester'));
    }

    /**
     * Mendapatkan mata pelajaran berdasarkan kelas dari jadwal
     */
    public function getSubjectsByClass(Request $request)
    {
        $classId = $request->get('class_id');
        $academicYear = $request->get('academic_year');

        if (!$classId) {
            return response()->json(['data' => []]);
        }

        // Ambil mata pelajaran dari jadwal kelas
        $subjects = Schedule::where('class_id', $classId)
            ->when($academicYear, function($query) use ($academicYear) {
                return $query->where('academic_year', $academicYear);
            })
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    /**
     * Mendapatkan daftar siswa berdasarkan kelas
     */
    public function getStudents(Request $request)
    {
        $classId = $request->get('class_id');
        $academicYear = $request->get('academic_year');

        if (!$classId) {
            return response()->json(['data' => []]);
        }

        $class = Classes::find($classId);
        
        if (!$class) {
            return response()->json(['data' => []]);
        }

        $students = $class->students()
            ->orderBy('name')
            ->get()
            ->map(function($student, $index) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'nisn' => $student->nisn,
                    'no_absen' => $index + 1
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Mendapatkan nilai siswa berdasarkan filter
     */
    public function getGrades(Request $request)
    {
        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        $academicYear = $request->get('academic_year');
        $semester = $request->get('semester');

        if (!$classId || !$subjectId) {
            return response()->json(['data' => []]);
        }

        $class = Classes::find($classId);
        
        if (!$class) {
            return response()->json(['data' => []]);
        }

        // Ambil semua siswa di kelas
        $students = $class->students()->orderBy('name')->get();

        // Konversi semester (1 = ganjil, 2 = genap)
        $semesterValues = $this->getSemesterValues($semester);

        // Ambil nilai yang sudah ada
        $existingGrades = StudentGrades::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->when($academicYear, function($query) use ($academicYear) {
                return $query->where('academic_year', $academicYear);
            })
            ->when($semester, function($query) use ($semesterValues) {
                return $query->whereIn('semester', $semesterValues);
            })
            ->get()
            ->keyBy('student_id');

        $data = $students->map(function($student, $index) use ($existingGrades, $classId, $subjectId, $academicYear, $semester) {
            $grade = $existingGrades->get($student->id);
            
            return [
                'id' => $grade ? $grade->id : null,
                'student_id' => $student->id,
                'student_name' => $student->name,
                'student_nisn' => $student->nisn,
                'no_absen' => $index + 1,
                'class_id' => $classId,
                'subject_id' => $subjectId,
                'academic_year' => $academicYear,
                'semester' => $semester,
                // Komponen nilai - Tugas
                'tugas1' => $grade ? $grade->tugas1 : null,
                'tugas2' => $grade ? $grade->tugas2 : null,
                'tugas3' => $grade ? $grade->tugas3 : null,
                'tugas4' => $grade ? $grade->tugas4 : null,
                'tugas5' => $grade ? $grade->tugas5 : null,
                'tugas6' => $grade ? $grade->tugas6 : null,
                // Komponen nilai - Lainnya
                'sikap' => $grade ? $grade->sikap : null,
                'uts' => $grade ? $grade->uts : null,
                'uas' => $grade ? $grade->uas : null,
                'nilai' => $grade ? $grade->nilai : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Menyimpan atau update nilai siswa
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
        ]);

        try {
            $grade = StudentGrades::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'academic_year' => $request->academic_year,
                    'semester' => $request->semester,
                ],
                [
                    'tugas1' => $request->tugas1,
                    'tugas2' => $request->tugas2,
                    'tugas3' => $request->tugas3,
                    'tugas4' => $request->tugas4,
                    'tugas5' => $request->tugas5,
                    'tugas6' => $request->tugas6,
                    'sikap' => $request->sikap,
                    'uts' => $request->uts,
                    'uas' => $request->uas,
                    'nilai' => $this->calculateFinalGrade($request),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan',
                'data' => $grade
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update nilai siswa secara bulk
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
        ]);

        DB::beginTransaction();
        
        try {
            $savedCount = 0;
            $normalizedSemester = $this->normalizeSemester($request->semester);
            $semesterValues = $this->getSemesterValues($request->semester);
            
            foreach ($request->grades as $gradeData) {
                $finalGrade = $this->calculateFinalGradeFromArray($gradeData);
                
                // Cari existing grade dengan berbagai format semester
                $existingGrade = StudentGrades::where('student_id', $gradeData['student_id'])
                    ->where('class_id', $request->class_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('academic_year', $request->academic_year)
                    ->whereIn('semester', $semesterValues)
                    ->first();
                
                if ($existingGrade) {
                    // Update existing
                    $existingGrade->update([
                        'tugas1' => $gradeData['tugas1'] ?? null,
                        'tugas2' => $gradeData['tugas2'] ?? null,
                        'tugas3' => $gradeData['tugas3'] ?? null,
                        'tugas4' => $gradeData['tugas4'] ?? null,
                        'tugas5' => $gradeData['tugas5'] ?? null,
                        'tugas6' => $gradeData['tugas6'] ?? null,
                        'sikap' => $gradeData['sikap'] ?? null,
                        'uts' => $gradeData['uts'] ?? null,
                        'uas' => $gradeData['uas'] ?? null,
                        'nilai' => $finalGrade,
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    // Create new
                    StudentGrades::create([
                        'student_id' => $gradeData['student_id'],
                        'class_id' => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'academic_year' => $request->academic_year,
                        'semester' => $normalizedSemester,
                        'tugas1' => $gradeData['tugas1'] ?? null,
                        'tugas2' => $gradeData['tugas2'] ?? null,
                        'tugas3' => $gradeData['tugas3'] ?? null,
                        'tugas4' => $gradeData['tugas4'] ?? null,
                        'tugas5' => $gradeData['tugas5'] ?? null,
                        'tugas6' => $gradeData['tugas6'] ?? null,
                        'sikap' => $gradeData['sikap'] ?? null,
                        'uts' => $gradeData['uts'] ?? null,
                        'uas' => $gradeData['uas'] ?? null,
                        'nilai' => $finalGrade,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
                
                $savedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyimpan {$savedCount} nilai siswa",
                'saved_count' => $savedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan statistik nilai
     */
    public function getStatistics(Request $request)
    {
        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        $academicYear = $request->get('academic_year');
        $semester = $request->get('semester');

        if (!$classId || !$subjectId) {
            return response()->json(['data' => null]);
        }

        // Konversi semester
        $semesterValues = $this->getSemesterValues($semester);

        $grades = StudentGrades::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->when($academicYear, function($query) use ($academicYear) {
                return $query->where('academic_year', $academicYear);
            })
            ->when($semester, function($query) use ($semesterValues) {
                return $query->whereIn('semester', $semesterValues);
            })
            ->whereNotNull('nilai')
            ->get();

        if ($grades->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => null
            ]);
        }

        $nilaiArray = $grades->pluck('nilai')->toArray();

        $statistics = [
            'total_siswa' => $grades->count(),
            'rata_rata' => round(array_sum($nilaiArray) / count($nilaiArray), 2),
            'nilai_tertinggi' => max($nilaiArray),
            'nilai_terendah' => min($nilaiArray),
            'tuntas' => $grades->where('nilai', '>=', 70)->count(),
            'tidak_tuntas' => $grades->where('nilai', '<', 70)->count(),
            'persentase_tuntas' => round(($grades->where('nilai', '>=', 70)->count() / $grades->count()) * 100, 2),
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Menghitung nilai akhir dari request
     * Logika: Rata-rata tugas 1-6, komponen kosong dihitung 0
     * Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
     */
    private function calculateFinalGrade(Request $request)
    {
        // Cek apakah ada minimal 1 komponen yang diisi
        $hasAnyValue = !empty($request->tugas1) || !empty($request->tugas2) || !empty($request->tugas3) ||
                       !empty($request->tugas4) || !empty($request->tugas5) || !empty($request->tugas6) ||
                       !empty($request->sikap) || !empty($request->uts) || !empty($request->uas);

        // Jika semua komponen kosong, anggap nilainya 0 (bukan null)
        if (!$hasAnyValue) {
            return 0;
        }

        // Hitung rata-rata tugas 1-6 (kosong = 0)
        $tugasValues = [
            floatval($request->tugas1 ?? 0),
            floatval($request->tugas2 ?? 0),
            floatval($request->tugas3 ?? 0),
            floatval($request->tugas4 ?? 0),
            floatval($request->tugas5 ?? 0),
            floatval($request->tugas6 ?? 0),
        ];
        $rataRataTugas = array_sum($tugasValues) / 6;

        // Komponen lain (kosong = 0)
        $sikap = floatval($request->sikap ?? 0);
        $uts = floatval($request->uts ?? 0);
        $uas = floatval($request->uas ?? 0);

        // Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
        $nilaiAkhir = ($rataRataTugas * 0.30) + ($sikap * 0.10) + ($uts * 0.30) + ($uas * 0.30);

        return round($nilaiAkhir, 2);
    }

    /**
     * Menghitung nilai akhir dari array
     * Logika: Rata-rata tugas 1-6, komponen kosong dihitung 0
     * Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
     */
    private function calculateFinalGradeFromArray(array $data)
    {
        // Cek apakah ada minimal 1 komponen yang diisi
        $hasAnyValue = !empty($data['tugas1']) || !empty($data['tugas2']) || !empty($data['tugas3']) ||
                       !empty($data['tugas4']) || !empty($data['tugas5']) || !empty($data['tugas6']) ||
                       !empty($data['sikap']) || !empty($data['uts']) || !empty($data['uas']);

        // Jika semua komponen kosong, anggap nilainya 0 (bukan null)
        if (!$hasAnyValue) {
            return 0;
        }

        // Hitung rata-rata tugas 1-6 (kosong = 0)
        $tugasValues = [
            floatval($data['tugas1'] ?? 0),
            floatval($data['tugas2'] ?? 0),
            floatval($data['tugas3'] ?? 0),
            floatval($data['tugas4'] ?? 0),
            floatval($data['tugas5'] ?? 0),
            floatval($data['tugas6'] ?? 0),
        ];
        $rataRataTugas = array_sum($tugasValues) / 6;

        // Komponen lain (kosong = 0)
        $sikap = floatval($data['sikap'] ?? 0);
        $uts = floatval($data['uts'] ?? 0);
        $uas = floatval($data['uas'] ?? 0);

        // Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
        $nilaiAkhir = ($rataRataTugas * 0.30) + ($sikap * 0.10) + ($uts * 0.30) + ($uas * 0.30);

        return round($nilaiAkhir, 2);
    }

    /**
     * Konversi semester ke array nilai yang valid
     * Mendukung format: 1, 2, "1", "2", "ganjil", "genap"
     */
    private function getSemesterValues($semester)
    {
        if ($semester == '1' || $semester == 1 || strtolower($semester) == 'ganjil') {
            return ['1', 'ganjil', 1];
        }
        if ($semester == '2' || $semester == 2 || strtolower($semester) == 'genap') {
            return ['2', 'genap', 2];
        }
        return [$semester];
    }

    /**
     * Normalisasi nilai semester untuk disimpan
     */
    private function normalizeSemester($semester)
    {
        if ($semester == '1' || $semester == 1 || strtolower($semester) == 'ganjil') {
            return '1';
        }
        if ($semester == '2' || $semester == 2 || strtolower($semester) == 'genap') {
            return '2';
        }
        return $semester;
    }
}
