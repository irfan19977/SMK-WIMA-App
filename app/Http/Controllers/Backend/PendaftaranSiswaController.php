<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Helpers\AcademicYearHelper;

class PendaftaranSiswaController extends Controller
{
    public function index(Request $request)
    {
        $studentsQuery = Student::with('user')
            ->where('status', 'calon siswa');

        // Filter Tahun Akademik berdasarkan kolom academic_year
        $selectedYear = $request->input('tahun_akademik');
        if (!$selectedYear) {
            $current = AcademicYearHelper::getCurrentAcademicYear();
            [$s, $e] = explode('/', $current);
            $selectedYear = ((int)$s + 1) . '/' . ((int)$e + 1); // default ke tahun depan
        }
        $studentsQuery->where('academic_year', $selectedYear);

        // Pencarian q: nama, nisn, email (student) dan email/phone (relasi user)
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $studentsQuery->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nisn', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
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

    public function export(Request $request)
    {
        $studentsQuery = Student::with('user')
            ->where('status', 'calon siswa');

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
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('email', 'like', "%$q%")
                         ->orWhere('phone', 'like', "%$q%");
                   });
            });
        }

        $students = $studentsQuery->get();

        $filename = 'pendaftaran-' . str_replace('/', '-', $selectedYear) . '.csv';
        return response()->streamDownload(function () use ($students) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No', 'Nama', 'NISN', 'Email', 'Phone', 'Status', 'Tahun Akademik']);
            $no = 1;
            foreach ($students as $s) {
                fputcsv($out, [
                    $no++,
                    $s->name,
                    $s->nisn,
                    $s->email ?: optional($s->user)->email,
                    $s->phone ?: optional($s->user)->phone,
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
        $studentsQuery = Student::with('user')
            ->where('status', 'calon siswa');

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
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('email', 'like', "%$q%")
                         ->orWhere('phone', 'like', "%$q%");
                   });
            });
        }

        $students = $studentsQuery->get();
        return view('pendaftaran.print', compact('students', 'selectedYear'));
    }
}
