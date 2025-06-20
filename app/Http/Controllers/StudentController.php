<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('user');
        
        // Filter pencarian
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('nisn', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $students = $query->paginate(10);
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'students' => $students->items(),
                'pagination' => [
                    'current_page' => $students->currentPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total(),
                    'last_page' => $students->lastPage(),
                ],
                'currentPage' => $students->currentPage(),
                'perPage' => $students->perPage(),
            ]);
        }
        
        // Return view normal untuk non-AJAX request
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
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
            'nisn' => 'nullable|string|max:20',
            'no_card' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);


       try {
        DB::beginTransaction();

        $students = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'phone' => $request->phone,
            'status' => $request->status ? true : false,
        ]);
        $students->assignRole('student');

        // Step 2: Generate QR Code for student
        $qrcode = 'STD-' . strtoupper(Str::random(8)) . '-' . date('Y');

        // Step 3: Create student record
        $student = Student::create([
            'id' => Str::uuid(),
            'user_id' => $students->id,
            'name' => $request->name,
            'nisn' => $request->nisn,
            'qrcode' => $qrcode,
            'no_card' => $request->no_card,
            'medical_info' => $request->medical_info,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'province' => $request->province,
            'regency' => $request->regency,
            'district' => $request->district,
            'village' => $request->village,
            'address' => $request->address,
            'created_by' => Auth::id(), // Set current user as creator
        ]);

        DB::commit();

        // Redirect to the students index with a success message
        return redirect()->route('students.index')->with('success', 'Student created successfully.');

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
        $student = Student::with('user')->findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $students = Student::with('user')->findOrFail($id);
        
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'nisn' => 'nullable|string|max:20',
            'no_card' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'province' => 'nullable|string|max:100',
            'regency' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        

        try {
            DB::beginTransaction();

            // Handle photo upload
            $photoPath = $students->photo; // Keep existing photo by default
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($students->photo && Storage::disk('public')->exists($students->photo)) {
                    Storage::disk('public')->delete($students->photo);
                }
                // Upload new photo
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            // Update user data
            $studentsData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status ? true : false,
                'photo' => $photoPath,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $studentsData['password'] = Hash::make($request->password);
            }

            $students->user->update($studentsData);

            // Update student data
            $students->update([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'no_card' => $request->no_card,
                'medical_info' => $request->medical_info,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'address' => $request->address,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('students.index')->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete uploaded photo if exists and different from original
            if ($request->hasFile('photo') && $photoPath !== $students->photo && Storage::disk('public')->exists($photoPath)) {
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
            
            $students = Student::findOrFail($id);
            $user = $students->user;
            
            $students->delete(); // Delete teacher first
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
