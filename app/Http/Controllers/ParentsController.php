<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ParentsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('parents.index');

        $query = ParentModel::with(['user', 'user.roles', 'student']);
        
        // Filter pencarian
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('student', function($studentQuery) use ($searchTerm) {
                    $studentQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                                ->orWhere('nisn', 'LIKE', '%' . $searchTerm . '%');
                });
            });
        }
        
        $parents = $query->paginate(10);
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            $parentsData = $parents->getCollection()->map(function($parent) {
                return [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    // Tambahkan data student
                    'student' => $parent->student ? [
                        'id' => $parent->student->id,
                        'name' => $parent->student->name,
                        'nisn' => $parent->student->nisn,
                    ] : null,
                    'user' => [
                        'id' => $parent->user->id,
                        'email' => $parent->user->email,
                    ]
                ];
            });
            
            return response()->json([
                'success' => true,
                'parents' => $parentsData,
                'pagination' => [
                    'current_page' => $parents->currentPage(),
                    'per_page' => $parents->perPage(),
                    'total' => $parents->total(),
                    'last_page' => $parents->lastPage(),
                ],
                'currentPage' => $parents->currentPage(),
                'perPage' => $parents->perPage(),
            ]);
        }
        
        // Return view normal untuk non-AJAX request
        return view('parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all(); 
        return view('parents.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'required|string', // Make student_id required
            'status' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        // Check if student already has a parent
        $existingParent = ParentModel::where('student_id', $request->student_id)->first();
        if ($existingParent) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Siswa sudah memiliki orang tua/wali');
        }

        try {
            DB::beginTransaction();

            $parents = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : null,
                'phone' => $request->phone,
            ]);
            $parents->assignRole('parent');

            // Create parent record with unique student_id
            $parents = ParentModel::create([
                'id' => Str::uuid(),
                'user_id' => $parents->id,
                'name' => $request->name,
                'student_id' => $request->student_id,
                'status' => $request->status,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'address' => $request->address,
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('parents.index')->with('success', 'Parent created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create parent: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $parents = ParentModel::with('user')->findOrFail($id);
        $students = Student::all(); // Get all students for the dropdown
        
        return view('parents.edit', compact('parents', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Ambil data parent, bukan student
    $parent = ParentModel::with('user')->findOrFail($id);
    
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $parent->user_id,
        'password' => 'nullable|string|min:8',
        'phone' => 'nullable|string|max:20',
        'student_id' => 'required|string', 
        'status' => 'nullable|string|max:20',
        'province' => 'nullable|string|max:100',
        'regency' => 'nullable|string|max:100',
        'district' => 'nullable|string|max:100',
        'village' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:255',
    ]);

    // Check if student_id is already taken by another parent (excluding current parent)
    $existingParent = ParentModel::where('student_id', $request->student_id)
                                 ->where('id', '!=', $id)
                                 ->first();
    if ($existingParent) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Siswa sudah memiliki orang tua/wali');
    }

    try {
        DB::beginTransaction();

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $parent->user->update($userData);

        // Update parent data
        $parent->update([
            'name' => $request->name,
            'student_id' => $request->student_id,
            'status' => $request->status,
            'province' => $request->province,
            'regency' => $request->regency,
            'district' => $request->district,
            'village' => $request->village,
            'address' => $request->address,
            'updated_by' => Auth::id(),
        ]);

        DB::commit();

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');

    } catch (\Exception $e) {
        DB::rollback();
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update parent: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $parent = ParentModel::findOrFail($id);
            $user = $parent->user;
            
            $parent->delete(); // Delete teacher first
            $user->delete(); // Then delete user
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function toggleActive(Request $request, $userId)
    {
        try {
            // Cari user berdasarkan ID yang dikirim dari frontend
            $user = User::findOrFail($userId);
            
            // Toggle status user
            $user->status = !$user->status;
            $saved = $user->save();
            
            if (!$saved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan status'
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'is_active' => $user->status, // Pastikan key ini sama dengan yang di frontend
                'status_text' => $user->status ? 'Aktif' : 'Diblokir'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
