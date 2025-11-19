<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AcademicYearHelper;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\StudentGrades;
use App\Models\Subject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AcademicReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('student-grades.index');
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $teacherId = $user->teacher->id ?? null;
            if (!$teacherId) {
                return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
            }

            $classes = Classes::select('classes.*')
                ->join('schedule', 'classes.id', '=', 'schedule.class_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('classes.name')
                ->get();

            $subjects = Subject::select('subject.*')
                ->join('schedule', 'subject.id', '=', 'schedule.subject_id')
                ->where('schedule.teacher_id', $teacherId)
                ->whereNull('schedule.deleted_at')
                ->distinct()
                ->orderBy('subject.name')
                ->get();
        } else {
            $classes = Classes::orderBy('name')->get();
            $subjects = Subject::orderBy('name')->get();
        }

        return view('reports.academic', compact('classes', 'subjects'));
    }

    public function semesterData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string|exists:classes,id',
                'subject_id' => 'required|string|exists:subject,id',
                'academic_year' => 'nullable|string',
                'semester' => 'required|in:ganjil,genap'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();
            $semester = $request->semester;
            $classId = $request->class_id;
            $subjectId = $request->subject_id;

            $user = Auth::user();
            if ($user->hasRole('teacher')) {
                $teacherId = $user->teacher->id ?? null;
                if (!$teacherId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 403);
                }

                $hasAccess = DB::table('schedule')
                    ->where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->where('teacher_id', $teacherId)
                    ->where('academic_year', $academicYear)
                    ->whereNull('deleted_at')
                    ->exists();

                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke mata pelajaran ini di kelas tersebut'
                    ], 403);
                }
            }

            // Students
            $students = DB::table('student')
                ->select([
                    'student.id as student_id',
                    'student.name as student_name',
                    'student.nisn as student_nisn',
                    'student.no_absen'
                ])
                ->join('student_class', 'student.id', '=', 'student_class.student_id')
                ->where('student_class.class_id', $classId)
                ->where('student_class.academic_year', $academicYear)
                ->where('student_class.status', 'active')
                ->whereNull('student.deleted_at')
                ->whereNull('student_class.deleted_at')
                ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
                ->orderBy('student.name')
                ->get();

            // UTS
            $uts = StudentGrades::where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->where('assessment_type', 'uts')
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('student_id');

            // UAS
            $uas = StudentGrades::where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->where('assessment_type', 'uas')
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('student_id');

            // Bulanan (tugas & sikap)
            $monthly = StudentGrades::where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->where('assessment_type', 'bulanan')
                ->whereNull('deleted_at')
                ->get()
                ->groupBy('student_id');

            $rows = $students->map(function ($s) use ($uts, $uas, $monthly) {
                $utsScore = optional($uts->get($s->student_id))->uts;
                $uasScore = optional($uas->get($s->student_id))->uas;

                // tugas semester = rata-rata dari rata-rata(tugas1,tugas2) per bulan
                $monthlyRows = $monthly->get($s->student_id) ?? collect();
                $monthlyMeans = $monthlyRows->map(function ($g) {
                    $parts = [];
                    if ($g->tugas1 !== null) $parts[] = (float) $g->tugas1;
                    if ($g->tugas2 !== null) $parts[] = (float) $g->tugas2;
                    if (count($parts) === 0) return null;
                    return array_sum($parts) / count($parts);
                })->filter(function ($v) { return $v !== null; });
                $tugasScore = $monthlyMeans->count() > 0 ? round($monthlyMeans->avg(), 2) : null;

                // sikap info (avg sikap bulanan)
                $sikapVals = $monthlyRows->pluck('sikap')->filter(function ($v) { return $v !== null; })->map(fn($v) => (float) $v);
                $sikapAvg = $sikapVals->count() > 0 ? round($sikapVals->avg(), 2) : null;

                // final: jika uts/uas tidak ada, final null (incomplete)
                $final = null;
                if ($utsScore !== null && $uasScore !== null) {
                    $final = round((0.4 * (float) $utsScore) + (0.4 * (float) $uasScore) + (0.2 * ((float) ($tugasScore ?? 0))), 2);
                }

                return [
                    'student_id' => $s->student_id,
                    'no_absen' => $s->no_absen,
                    'student_name' => $s->student_name,
                    'student_nisn' => $s->student_nisn,
                    'uts' => $utsScore,
                    'uas' => $uasScore,
                    'tugas' => $tugasScore,
                    'sikap' => $sikapAvg,
                    'final' => $final,
                    'status' => $final === null ? 'incomplete' : 'ok'
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $rows,
                'count' => $rows->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in semesterData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch academic report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        // Validate inputs
        $request->validate([
            'class_id' => 'required|string|exists:classes,id',
            'subject_id' => 'required|string',
            'academic_year' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap'
        ]);

        $class = Classes::find($request->class_id);
        $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();
        $semester = $request->semester;

        if ($request->subject_id === 'all') {
            // Build per-student pages with all subjects for this class in the given academic year
            $payload = $this->buildAllSubjectsRows($request);
            return view('reports.academic_print_all', [
                'pages' => $payload['pages'], // array of ['student' => ..., 'subjects' => [...]]
                'class' => $class,
                'academicYear' => $academicYear,
                'semester' => $semester,
            ]);
        }

        // Single subject export
        // Ensure subject exists when not 'all'
        $subject = Subject::findOrFail($request->subject_id);
        $data = $this->buildSemesterRows($request);
        return view('reports.academic_print', [
            'rows' => $data,
            'class' => $class,
            'subject' => $subject,
            'academicYear' => $academicYear,
            'semester' => $semester,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'class_id' => 'required|string|exists:classes,id',
            'subject_id' => 'required|string|exists:subject,id',
            'academic_year' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap'
        ]);

        $rows = $this->buildSemesterRows($request);
        $class = Classes::find($request->class_id);
        $subject = Subject::find($request->subject_id);
        $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();
        $semester = $request->semester;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title and letterhead texts
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'LAPORAN NILAI AKADEMIK');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'SMK PGRI LAWANG');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Kelas: ' . ($class->name ?? '-') . ' | Mapel: ' . ($subject->name ?? '-') . ' | Semester: ' . strtoupper($semester) . ' | Tahun Akademik: ' . $academicYear);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = ['No Absen', 'Nama', 'NISN', 'UTS', 'UAS', 'Tugas', 'Sikap', 'Nilai Akhir', 'Status'];
        $rowIdx = 5;
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i); // A..
            $sheet->setCellValue($col . $rowIdx, $h);
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFEFEFEF'],
            ],
        ];
        $sheet->getStyle('A5:I5')->applyFromArray($headerStyle);

        // Column widths
        $widths = [10, 28, 16, 10, 10, 10, 10, 12, 12];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimension(chr(65 + $i))->setWidth($w);
        }

        // Data
        $r = 6;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $r, $row['no_absen'] ?? $row['no_absen'] ?? $row['no_absen'] ?? $row['no_absen']);
            $sheet->setCellValue('A' . $r, $row['no_absen'] ?? '');
            $sheet->setCellValue('B' . $r, $row['student_name'] ?? '');
            $sheet->setCellValue('C' . $r, $row['student_nisn'] ?? '');
            $sheet->setCellValue('D' . $r, $row['uts'] ?? '');
            $sheet->setCellValue('E' . $r, $row['uas'] ?? '');
            $sheet->setCellValue('F' . $r, $row['tugas'] ?? '');
            $sheet->setCellValue('G' . $r, $row['sikap'] ?? '');
            $sheet->setCellValue('H' . $r, $row['final'] ?? '');
            $sheet->setCellValue('I' . $r, $row['status'] ?? '');
            $r++;
        }

        $last = $r - 1;
        $sheet->getStyle('A6:I' . $last)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        $sheet->getStyle('A6:A' . $last)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D6:I' . $last)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $filename = 'Laporan_Akademik_' . ($class->name ?? '') . '_' . ($subject->name ?? '') . '_' . strtoupper($semester) . '_' . str_replace('/', '-', $academicYear) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function buildSemesterRows(Request $request)
    {
        $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();
        $semester = $request->semester;
        $classId = $request->class_id;
        $subjectId = $request->subject_id;

        // Students
        $students = DB::table('student')
            ->select([
                'student.id as student_id',
                'student.name as student_name',
                'student.nisn as student_nisn',
                'student.no_absen'
            ])
            ->join('student_class', 'student.id', '=', 'student_class.student_id')
            ->where('student_class.class_id', $classId)
            ->where('student_class.academic_year', $academicYear)
            ->where('student_class.status', 'active')
            ->whereNull('student.deleted_at')
            ->whereNull('student_class.deleted_at')
            ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
            ->orderBy('student.name')
            ->get();

        // UTS
        $uts = StudentGrades::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->where('assessment_type', 'uts')
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('student_id');

        // UAS
        $uas = StudentGrades::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->where('assessment_type', 'uas')
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('student_id');

        // Bulanan (tugas & sikap)
        $monthly = StudentGrades::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->where('assessment_type', 'bulanan')
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('student_id');

        $rows = $students->map(function ($s) use ($uts, $uas, $monthly) {
            $utsScore = optional($uts->get($s->student_id))->uts;
            $uasScore = optional($uas->get($s->student_id))->uas;

            $monthlyRows = $monthly->get($s->student_id) ?? collect();
            $monthlyMeans = $monthlyRows->map(function ($g) {
                $parts = [];
                if ($g->tugas1 !== null) $parts[] = (float) $g->tugas1;
                if ($g->tugas2 !== null) $parts[] = (float) $g->tugas2;
                if (count($parts) === 0) return null;
                return array_sum($parts) / count($parts);
            })->filter(function ($v) { return $v !== null; });
            $tugasScore = $monthlyMeans->count() > 0 ? round($monthlyMeans->avg(), 2) : null;

            $sikapVals = $monthlyRows->pluck('sikap')->filter(function ($v) { return $v !== null; })->map(fn($v) => (float) $v);
            $sikapAvg = $sikapVals->count() > 0 ? round($sikapVals->avg(), 2) : null;

            $final = null;
            if ($utsScore !== null && $uasScore !== null) {
                $final = round((0.4 * (float) $utsScore) + (0.4 * (float) $uasScore) + (0.2 * ((float) ($tugasScore ?? 0))), 2);
            }

            return [
                'student_id' => $s->student_id,
                'no_absen' => $s->no_absen,
                'student_name' => $s->student_name,
                'student_nisn' => $s->student_nisn,
                'uts' => $utsScore,
                'uas' => $uasScore,
                'tugas' => $tugasScore,
                'sikap' => $sikapAvg,
                'final' => $final,
                'status' => $final === null ? 'incomplete' : 'ok'
            ];
        })->values();

        return $rows;
    }

    private function buildAllSubjectsRows(Request $request)
    {
        $academicYear = $request->academic_year ?: AcademicYearHelper::getCurrentAcademicYear();
        $semester = $request->semester;
        $classId = $request->class_id;

        // All active students for class + year
        $students = DB::table('student')
            ->select([
                'student.id as student_id',
                'student.name as student_name',
                'student.nisn as student_nisn',
                'student.no_absen'
            ])
            ->join('student_class', 'student.id', '=', 'student_class.student_id')
            ->where('student_class.class_id', $classId)
            ->where('student_class.academic_year', $academicYear)
            ->where('student_class.status', 'active')
            ->whereNull('student.deleted_at')
            ->whereNull('student_class.deleted_at')
            ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
            ->orderBy('student.name')
            ->get();

        // Subjects taught to this class (prefer the selected academic year; fallback to any year if none)
        $subjectsQuery = Subject::select('subject.*')
            ->join('schedule', 'subject.id', '=', 'schedule.subject_id')
            ->where('schedule.class_id', $classId)
            ->whereNull('schedule.deleted_at')
            ->distinct();
        $subjects = (clone $subjectsQuery)
            ->where('schedule.academic_year', $academicYear)
            ->orderBy('subject.name')
            ->get();
        if ($subjects->count() === 0) {
            // fallback: ignore academic year filter to show all mapped subjects for the class
            $subjects = $subjectsQuery
                ->orderBy('subject.name')
                ->get();
        }

        // Preload grades by (student, subject)
        $grades = StudentGrades::where('class_id', $classId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->whereIn('assessment_type', ['bulanan','uts','uas'])
            ->whereNull('deleted_at')
            ->get()
            ->groupBy(function ($g) { return $g->student_id . ':' . $g->subject_id; });

        $pages = [];
        foreach ($students as $s) {
            $subjectRows = [];
            foreach ($subjects as $subj) {
                $key = $s->student_id . ':' . $subj->id;
                $group = $grades->get($key) ?? collect();
                $utsScore = optional($group->firstWhere('assessment_type','uts'))->uts;
                $uasScore = optional($group->firstWhere('assessment_type','uas'))->uas;
                $monthly = $group->where('assessment_type','bulanan');
                $monthlyMeans = $monthly->map(function ($g) {
                    $parts = [];
                    if ($g->tugas1 !== null) $parts[] = (float) $g->tugas1;
                    if ($g->tugas2 !== null) $parts[] = (float) $g->tugas2;
                    if (count($parts) === 0) return null;
                    return array_sum($parts) / count($parts);
                })->filter(fn($v) => $v !== null);
                $tugas = $monthlyMeans->count() ? round($monthlyMeans->avg(), 2) : null;

                $final = null;
                if ($utsScore !== null && $uasScore !== null) {
                    $final = round((0.4 * (float) $utsScore) + (0.4 * (float) $uasScore) + (0.2 * ((float) ($tugas ?? 0))), 2);
                }

                $subjectRows[] = [
                    'subject_name' => $subj->name,
                    'uts' => $utsScore,
                    'uas' => $uasScore,
                    'tugas' => $tugas,
                    'final' => $final,
                    'status' => $final === null ? 'incomplete' : 'ok',
                ];
            }

            $pages[] = [
                'student' => [
                    'id' => $s->student_id,
                    'name' => $s->student_name,
                    'nisn' => $s->student_nisn,
                    'no_absen' => $s->no_absen,
                ],
                'subjects' => $subjectRows,
            ];
        }

        return [
            'pages' => $pages,
        ];
    }

    public function allSubjectsData(Request $request)
    {
        $request->validate([
            'class_id' => 'required|string|exists:classes,id',
            'academic_year' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap'
        ]);

        $payload = $this->buildAllSubjectsRows($request);
        return response()->json([
            'success' => true,
            'data' => $payload['pages'],
            'count' => count($payload['pages'])
        ]);
    }
}
