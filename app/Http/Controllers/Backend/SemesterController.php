<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SemesterController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // $this->authorize('semester.index');
        
        // Auto-detect dan set active semester berdasarkan tanggal sekarang
        Semester::autoSetActiveSemester();
        
        $semesters = Semester::orderBy('academic_year', 'desc')
            ->orderBy('semester_type', 'asc')
            ->get();
        
        return view('semester.index', compact('semesters'));
    }

    public function create()
    {
        // $this->authorize('semester.create');
        return view('semester.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('semester.create');
        
        $request->validate([
            'academic_year' => 'required|string|max:9',
            'semester_type' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        // Cek apakah sudah ada semester aktif
        if ($request->boolean('is_active')) {
            Semester::where('is_active', true)->update(['is_active' => false]);
        }

        Semester::create([
            'academic_year' => $request->academic_year,
            'semester_type' => $request->semester_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Auto-detect dan set active semester setelah create
        if (!$request->boolean('is_active')) {
            Semester::autoSetActiveSemester();
        }

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil ditambahkan.');
    }

    public function show(Semester $semester)
    {
        // $this->authorize('semester.show');
        return view('semester.show', compact('semester'));
    }

    public function edit(Semester $semester)
    {
        // $this->authorize('semester.edit');
        return view('semester.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        // $this->authorize('semester.edit');
        
        $request->validate([
            'academic_year' => 'required|string|max:9',
            'semester_type' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        // Cek apakah sudah ada semester aktif lain
        if ($request->boolean('is_active') && !$semester->is_active) {
            Semester::where('is_active', true)->update(['is_active' => false]);
        }

        $semester->update([
            'academic_year' => $request->academic_year,
            'semester_type' => $request->semester_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester)
    {
        // $this->authorize('semester.delete');
        
        $semester->delete();

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil dihapus.');
    }

    /**
     * Generate semester otomatis berdasarkan tahun akademik
     */
    public function generateSemester(Request $request)
    {
        // $this->authorize('semester.create');
        
        $request->validate([
            'academic_year' => 'required|string|max:9',
        ]);

        $academicYear = $request->academic_year;
        [$startYear, $endYear] = explode('/', $academicYear);
        
        // Semester Ganjil: Juli - Desember
        $ganjilStartDate = "{$startYear}-07-01";
        $ganjilEndDate = "{$startYear}-12-31";
        
        // Semester Genap: Januari - Juni
        $genapStartDate = "{$endYear}-01-01";
        $genapEndDate = "{$endYear}-06-30";

        // Cek apakah sudah ada
        $existingGanjil = Semester::where('academic_year', $academicYear)
            ->where('semester_type', 'ganjil')
            ->first();
            
        $existingGenap = Semester::where('academic_year', $academicYear)
            ->where('semester_type', 'genap')
            ->first();

        $created = [];
        
        if (!$existingGanjil) {
            Semester::create([
                'academic_year' => $academicYear,
                'semester_type' => 'ganjil',
                'start_date' => $ganjilStartDate,
                'end_date' => $ganjilEndDate,
                'is_active' => false,
            ]);
            $created[] = 'Semester Ganjil';
        }
        
        if (!$existingGenap) {
            Semester::create([
                'academic_year' => $academicYear,
                'semester_type' => 'genap',
                'start_date' => $genapStartDate,
                'end_date' => $genapEndDate,
                'is_active' => false,
            ]);
            $created[] = 'Semester Genap';
        }

        // Auto-detect dan set active semester setelah generate
        Semester::autoSetActiveSemester();

        $message = count($created) > 0 
            ? 'Berhasil generate: ' . implode(', ', $created) . '. Semester aktif: ' . (Semester::getCurrentActiveSemester()?->display_name ?? 'Tidak ada')
            : 'Semester untuk tahun akademik ini sudah ada.';

        return redirect()->route('semester.index')
            ->with('success', $message);
    }

    /**
     * Set semester aktif
     */
    public function setActive(Semester $semester)
    {
        // $this->authorize('semester.edit');
        
        // Nonaktifkan semua semester lain
        Semester::where('is_active', true)->update(['is_active' => false]);
        
        // Aktifkan semester ini
        $semester->update(['is_active' => true]);

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil diaktifkan.');
    }
}
