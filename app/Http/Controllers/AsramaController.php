<?php

namespace App\Http\Controllers;

use App\Models\Asrama;
use App\Models\AssignAsrama;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsramaController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('asrama.index');
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        
        return view('asrama.edit', compact('asramas'));
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
}