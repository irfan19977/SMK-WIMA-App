<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class PendaftaranSiswaController extends Controller
{
    public function index(Request $request)
    {
        $students= Student::with('user')->where('status', 'calon siswa')->get();
        return view('pendaftaran.index', compact('students'));
    }

    public function show(Student $pendaftaran_siswa)
    {
        // Resource route uses parameter name based on the resource url (pendaftaran-siswa)
        // Bind to Student model via implicit model binding
        $student = $pendaftaran_siswa->load('user');
        return view('pendaftaran.show', compact('student'));
    }

    public function accept(Request $request, Student $pendaftaran_siswa)
    {
        // Update status calon siswa -> siswa
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
}
