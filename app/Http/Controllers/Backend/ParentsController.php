<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Str;

class ParentsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
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
        
        // Set per page
        $perPage = $request->get('per_page', 10);
        $parents = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportExcel($parents);
        }

        // Handle print
        if ($request->has('print') && $request->print === 'pdf') {
            return $this->printPDF($parents);
        }
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            $parentsData = $parents->getCollection()->map(function($parent) {
                return [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'email' => $parent->user->email,
                    'phone' => $parent->user->phone,
                    'status' => $parent->status,
                    'user_status' => $parent->user->status,
                    'user_id' => $parent->user_id,
                    'student' => $parent->student ? [
                        'id' => $parent->student->id,
                        'name' => $parent->student->name,
                        'nisn' => $parent->student->nisn,
                    ] : null,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $parentsData,
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
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $students = Student::all();
        
        if (request()->ajax()) {
            $html = view('parents.addEdit', ['students' => $students])->render();
            return response()->json([
                'success' => true,
                'title' => 'Tambah Wali Murid',
                'html' => $html
            ]);
        }
        
        return view('parents.addEdit', ['students' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string',
            'status' => 'required|in:ayah,ibu,wali',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
        ]);

        // Check if student already has a parent (if student_id is provided)
        if ($request->student_id) {
            $existingParent = ParentModel::where('student_id', $request->student_id)->first();
            if ($existingParent) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Siswa sudah memiliki orang tua/wali');
            }
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'status' => true,
            ]);
            $user->assignRole('parent');

            // Create parent record
            $parent = ParentModel::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $request->name,
                'student_id' => $request->student_id ?: null,
                'status' => $request->status,
                'jenis_kelamin' => $request->jenis_kelamin,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'address' => $request->address,
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wali murid berhasil ditambahkan.',
                    'data' => $parent
                ]);
            }
            
            return redirect()->route('parents.index')->with('success', 'Wali murid berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan wali murid: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan wali murid: ' . $e->getMessage());
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
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $parent = ParentModel::with('user')->findOrFail($id);
        $students = Student::all();
        
        if (request()->ajax()) {
            $html = view('parents.addEdit', ['parent' => $parent, 'students' => $students])->render();
            return response()->json([
                'success' => true,
                'title' => 'Edit Wali Murid',
                'html' => $html
            ]);
        }
        
        return view('parents.addEdit', ['parent' => $parent, 'students' => $students]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $parent = ParentModel::with('user')->findOrFail($id);
        
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->user_id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string',
            'status' => 'required|in:ayah,ibu,wali',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
        ]);

        // Check if student_id is already taken by another parent (if student_id is provided)
        if ($request->student_id) {
            $existingParent = ParentModel::where('student_id', $request->student_id)
                                         ->where('id', '!=', $id)
                                         ->first();
            if ($existingParent) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Siswa sudah memiliki orang tua/wali');
            }
        }

        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            
            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }
            
            $parent->user->update($userData);

            // Update parent data
            $parent->update([
                'name' => $request->name,
                'student_id' => $request->student_id ?: null,
                'status' => $request->status,
                'jenis_kelamin' => $request->jenis_kelamin,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'address' => $request->address,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wali murid berhasil diperbarui.',
                    'data' => $parent
                ]);
            }
            
            return redirect()->route('parents.index')->with('success', 'Wali murid berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui wali murid: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui wali murid: ' . $e->getMessage());
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

    /**
     * Export parents to Excel
     */
    public function exportExcel($parents)
    {
        // Placeholder for Excel export functionality
        return response()->json([
            'success' => false,
            'message' => 'Export Excel functionality not implemented yet'
        ]);
    }

    /**
     * Print parents to PDF
     */
    public function printPDF($parents)
    {
        // Placeholder for PDF print functionality
        return response()->json([
            'success' => false,
            'message' => 'Print PDF functionality not implemented yet'
        ]);
    }
}
