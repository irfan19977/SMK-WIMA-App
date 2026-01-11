<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Helpers\AcademicYearHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PendaftaranSiswaController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('pendaftaran-siswa.index');
        // Ambil semua siswa (calon maupun sudah diterima) untuk tahun akademik terpilih, kecuali yang ditolak
        $studentsQuery = Student::with('user')
            ->where('status', '!=', 'ditolak');

        // Filter Tahun Akademik berdasarkan kolom academic_year
        $selectedYear = $request->input('tahun_akademik');
        if (!$selectedYear) {
            $current = AcademicYearHelper::getCurrentAcademicYear();
            [$s, $e] = explode('/', $current);
            $selectedYear = ((int)$s + 1) . '/' . ((int)$e + 1); // default ke tahun depan
        }
        $studentsQuery->where('academic_year', $selectedYear);

        // Pencarian q: nama, nisn, nik, email (student) dan email/phone (relasi user)
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $studentsQuery->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nisn', 'like', "%$q%")
                   ->orWhere('nik', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('jurusan_utama', 'like', "%$q%")
                   ->orWhere('jurusan_cadangan', 'like', "%$q%")
                   ->orWhere('gender', 'like', "%$q%")
                   ->orWhere('birth_place', 'like', "%$q%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('email', 'like', "%$q%")
                         ->orWhere('phone', 'like', "%$q%");
                   });
            });
        }

        $students = $studentsQuery->get();

        // Jika request dari AJAX (partial refresh), kembalikan partial tabel saja
        if ($request->ajax() || $request->boolean('partial')) {
            return view('pendaftaran._table', compact('students'));
        }

        return view('pendaftaran.index', compact('students'));
    }

    public function show(Student $pendaftaran_siswa)
    {
        if ($pendaftaran_siswa->status !== 'calon siswa') {
            abort(404, 'Calon siswa tidak ditemukan atau sudah diterima.');
        }
        $student = $pendaftaran_siswa->load('user');
        return view('pendaftaran.show', compact('student'));
    }

    public function accept(Request $request, Student $pendaftaran_siswa)
    {
        $data = $request->validate([
            'jurusan_diterima' => 'nullable|string|max:191',
            'no_absen' => 'nullable|string|max:20',
        ]);

        if (!empty($data['jurusan_diterima'])) {
            $pendaftaran_siswa->jurusan_utama = $data['jurusan_diterima'];
        }

        if (!empty($data['no_absen'])) {
            $pendaftaran_siswa->no_absen = $data['no_absen'];
        }

        $pendaftaran_siswa->status = 'siswa';
        $pendaftaran_siswa->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Calon siswa telah diterima.',
                'student' => [
                    'id' => $pendaftaran_siswa->id,
                    'status' => $pendaftaran_siswa->status,
                ],
            ]);
        }

        return back()->with('success', 'Calon siswa telah diterima.');
    }

    public function reject(Request $request, Student $pendaftaran_siswa)
    {
        // Hapus data siswa dan user terkait ketika pendaftaran ditolak
        $user = $pendaftaran_siswa->user;

        // Soft delete student (karena model menggunakan SoftDeletes)
        $pendaftaran_siswa->delete();

        // Hapus user terkait (soft delete jika model User memakai SoftDeletes)
        if ($user) {
            $user->delete();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Calon siswa telah ditolak dan datanya dihapus.',
                'student' => [
                    'id' => $pendaftaran_siswa->id,
                ],
            ]);
        }

        return back()->with('success', 'Calon siswa telah ditolak dan datanya dihapus.');
    }

    public function export(Request $request)
    {
        // Export semua siswa (calon maupun sudah diterima) untuk tahun akademik terpilih, kecuali yang ditolak
        $studentsQuery = Student::with('user')
            ->where('status', '!=', 'ditolak');

        $selectedYear = $request->input('tahun_akademik');
        if (!$selectedYear) {
            $current = AcademicYearHelper::getCurrentAcademicYear();
            [$s, $e] = explode('/', $current);
            $selectedYear = ((int)$s + 1) . '/' . ((int)$e + 1);
        }
        $studentsQuery->where('academic_year', $selectedYear);

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $studentsQuery->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nisn', 'like', "%$q%")
                   ->orWhere('nik', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('jurusan_utama', 'like', "%$q%")
                   ->orWhere('jurusan_cadangan', 'like', "%$q%")
                   ->orWhere('gender', 'like', "%$q%")
                   ->orWhere('birth_place', 'like', "%$q%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('email', 'like', "%$q%")
                         ->orWhere('phone', 'like', "%$q%");
                   });
            });
        }

        $students = $studentsQuery->get();

        $filename = 'pendaftaran-' . str_replace('/', '-', $selectedYear) . '.csv';
        return response()->streamDownload(function () use ($students, $selectedYear) {
            $out = fopen('php://output', 'w');
            // Header dengan BOM untuk Excel UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($out, ['No', 'Nama', 'NISN', 'NIK', 'Jurusan Utama', 'Jurusan Cadangan', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Nomor HP', 'Status', 'Tahun Akademik']);
            $no = 1;
            foreach ($students as $s) {
                fputcsv($out, [
                    $no++,
                    $s->name,
                    $s->nisn ?? '-',
                    $s->nik ?? '-',
                    $s->jurusan_utama ?? '-',
                    $s->jurusan_cadangan ?? '-',
                    $s->gender ?? '-',
                    $s->birth_place ?? '-',
                    $s->birth_date ? \Carbon\Carbon::parse($s->birth_date)->format('d/m/Y') : '-',
                    optional($s->user)->phone ?? '-',
                    $s->status,
                    $s->academic_year,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function print(Request $request)
    {
        // Cetak semua siswa (calon maupun sudah diterima) untuk tahun akademik terpilih, kecuali yang ditolak
        $studentsQuery = Student::with('user')
            ->where('status', '!=', 'ditolak');

        $selectedYear = $request->input('tahun_akademik');
        if (!$selectedYear) {
            $current = AcademicYearHelper::getCurrentAcademicYear();
            [$s, $e] = explode('/', $current);
            $selectedYear = ((int)$s + 1) . '/' . ((int)$e + 1);
        }
        $studentsQuery->where('academic_year', $selectedYear);

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $studentsQuery->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nisn', 'like', "%$q%")
                   ->orWhere('nik', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('jurusan_utama', 'like', "%$q%")
                   ->orWhere('jurusan_cadangan', 'like', "%$q%")
                   ->orWhere('gender', 'like', "%$q%")
                   ->orWhere('birth_place', 'like', "%$q%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('email', 'like', "%$q%")
                         ->orWhere('phone', 'like', "%$q%");
                   });
            });
        }

        $students = $studentsQuery->get();
        return view('pendaftaran.print', compact('students', 'selectedYear'));
    }

    public function exportExcel(Request $request)
    {
        // Export ke Excel semua siswa (calon maupun sudah diterima) untuk tahun akademik terpilih, kecuali yang ditolak
        $studentsQuery = Student::with('user')
            ->where('status', '!=', 'ditolak');

        $selectedYear = $request->input('tahun_akademik');
        if (!$selectedYear) {
            $current = AcademicYearHelper::getCurrentAcademicYear();
            [$s, $e] = explode('/', $current);
            $selectedYear = ((int)$s + 1) . '/' . ((int)$e + 1);
        }
        $studentsQuery->where('academic_year', $selectedYear);

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $studentsQuery->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nisn', 'like', "%$q%")
                   ->orWhere('nik', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('jurusan_utama', 'like', "%$q%")
                   ->orWhere('jurusan_cadangan', 'like', "%$q%")
                   ->orWhere('gender', 'like', "%$q%")
                   ->orWhere('birth_place', 'like', "%$q%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('email', 'like', "%$q%")
                         ->orWhere('phone', 'like', "%$q%");
                   });
            });
        }

        $students = $studentsQuery->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul laporan
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'DAFTAR PENDAFTAR BARU');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set informasi sekolah
        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue('A2', 'SMK PGRI LAWANG');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set periode tahun akademik
        $sheet->mergeCells('A3:L3');
        $sheet->setCellValue('A3', 'Tahun Akademik: ' . $selectedYear);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set header tabel
        $headers = [
            'No', 'Nama', 'NISN', 'NIK', 'Jurusan Utama', 
            'Jurusan Cadangan', 'Jenis Kelamin', 'Tempat Lahir', 
            'Tanggal Lahir', 'Nomor HP', 'Status', 'Tahun Akademik'
        ];

        $row = 5;
        foreach ($headers as $index => $header) {
            $col = chr(65 + $index); // A, B, C, ... L
            $sheet->setCellValue($col . $row, $header);
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'],
            ],
        ];

        $sheet->getStyle('A5:L5')->applyFromArray($headerStyle);

        // Atur lebar kolom
        $columnWidths = [5, 25, 15, 18, 20, 20, 15, 20, 15, 15, 15, 15];
        foreach ($columnWidths as $index => $width) {
            $col = chr(65 + $index);
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Isi data
        $row = 6;
        $no = 1;

        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $student->name);
            $sheet->setCellValue('C' . $row, $student->nisn ?? '-');
            $sheet->setCellValue('D' . $row, $student->nik ?? '-');
            $sheet->setCellValue('E' . $row, $student->jurusan_utama ?? '-');
            $sheet->setCellValue('F' . $row, $student->jurusan_cadangan ?? '-');
            $sheet->setCellValue('G' . $row, $student->gender ?? '-');
            $sheet->setCellValue('H' . $row, $student->birth_place ?? '-');
            $sheet->setCellValue('I' . $row, $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-');
            $sheet->setCellValue('J' . $row, optional($student->user)->phone ?? '-');
            $sheet->setCellValue('K' . $row, $student->status);
            $sheet->setCellValue('L' . $row, $student->academic_year);

            $row++;
            $no++;
        }

        // Style untuk data
        $lastRow = $row - 1;
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A6:L' . $lastRow)->applyFromArray($dataStyle);

        // Style untuk kolom nomor (center)
        $sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G6:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I6:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Tambahkan autofilter
        $sheet->setAutoFilter('A5:L5');

        // Freeze panes untuk header tetap terlihat
        $sheet->freezePane('A6');

        // Buat writer dan kirim file ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'Pendaftar_Baru_' . str_replace('/', '-', $selectedYear) . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
