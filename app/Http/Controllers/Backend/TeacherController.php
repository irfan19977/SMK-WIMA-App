<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('teachers.index');

        $query = Teacher::with(['user', 'user.roles']);
        
        // Filter pencarian
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('nip', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $teachers = $query->paginate(10);
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            // Transform data untuk memastikan relasi dimuat dengan benar
            $teachersData = $teachers->getCollection()->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'nip' => $teacher->nip,
                    'no_card' => $teacher->no_card,
                    'education_level' => $teacher->education_level,
                    'education_major' => $teacher->education_major,
                    'user' => [
                        'id' => $teacher->user->id,
                        'email' => $teacher->user->email,
                        'phone' => $teacher->user->phone,
                        'status' => $teacher->user->status,
                        'roles' => $teacher->user->roles->map(function($role) {
                            return [
                                'name' => $role->name
                            ];
                        })
                    ]
                ];
            });
            
            return response()->json([
                'success' => true,
                'teachers' => $teachersData,
                'pagination' => [
                    'current_page' => $teachers->currentPage(),
                    'per_page' => $teachers->perPage(),
                    'total' => $teachers->total(),
                    'last_page' => $teachers->lastPage(),
                ],
                'currentPage' => $teachers->currentPage(),
                'perPage' => $teachers->perPage(),
            ]);
        }
        
        // Return view normal untuk non-AJAX request
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
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
            'nip' => 'nullable|string|max:20',
            'no_card' => 'nullable|string|max:20',
            'education_level' => 'nullable|string|max:100',
            'education_major' => 'nullable|string|max:100',
            'education_institution' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);


       try {
        
        DB::beginTransaction();

        $teachers = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'phone' => $request->phone,
            'status' => $request->status ? true : false,
        ]);
        $teachers->assignRole('teacher');

        // Step 2: Generate QR Code for student
        $qrcode = 'TEC-' . strtoupper(Str::random(8)) . '-' . date('Y');

        // Step 3: Create student record
        $teachers = Teacher::create([
            'id' => Str::uuid(),
            'user_id' => $teachers->id,
            'name' => $request->name,
            'nip' => $request->nip,
            'qrcode' => $qrcode,
            'no_card' => $request->no_card,
            'education_level' => $request->education_level,
            'education_major' => $request->education_major,
            'education_institution' => $request->education_institution,
            'gender' => $request->gender,
            'province' => $request->province,
            'regency' => $request->regency,
            'district' => $request->district,
            'village' => $request->village,
            'address' => $request->address,
            'created_by' => Auth::id(), // Set current user as creator
        ]);

        DB::commit();

        // Redirect to the teachers index with a success message
        return redirect()->route('teachers.index')->with('success', 'Student created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create student: ' . $e->getMessage());
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
        $teachers = Teacher::with('user')->findOrFail($id);
        return view('teachers.edit', compact('teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teachers = Teacher::with('user')->findOrFail($id);
        
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'nip' => 'nullable|string|max:20',
            'no_card' => 'nullable|string|max:20',
            'education_level' => 'nullable|string|max:100',
            'education_major' => 'nullable|string|max:100',
            'education_institution' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Handle photo upload
            $photoPath = $teachers->photo; // Keep existing photo by default
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($teachers->photo && Storage::disk('public')->exists($teachers->photo)) {
                    Storage::disk('public')->delete($teachers->photo);
                }
                // Upload new photo
                $photoPath = $request->file('photo')->store('teachers/photos', 'public');
            }

            // Update user data
            $teachersData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status ? true : false,
                'photo' => $photoPath,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $teachersData['password'] = Hash::make($request->password);
            }

            $teachers->user->update($teachersData);

            // Update student data
            $teachers->update([
                'name' => $request->name,
                'nip' => $request->nip,
                'no_card' => $request->no_card,
                'education_level' => $request->education_level,
                'education_major' => $request->education_major,
                'education_institution' => $request->education_institution,
                'gender' => $request->gender,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'address' => $request->address,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete uploaded photo if exists and different from original
            if ($request->hasFile('photo') && $photoPath !== $teachers->photo && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $teacher = Teacher::findOrFail($id);
            $user = $teacher->user;
            
            $teacher->delete(); // Delete teacher first
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
