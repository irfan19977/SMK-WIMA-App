<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('students.index');
        $query = Student::with('user')->where('status', 'siswa');
        
        // Filter pencarian
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('nisn', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $students = $query->paginate(10);
        
        // Jika request dari AJAX (partial refresh), kembalikan partial tabel saja
        if ($request->boolean('partial')) {
            return view('students._table', compact('students'));
        }

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
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        if (request()->ajax()) {
            $view = view('students._form', [
                'student' => null,
                'action' => route('students.store'),
                'method' => 'POST',
                'title' => __('index.add_student')
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.add_student')
            ]);
        }
        
        return view('students.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            // Debug: Cek data yang masuk
            Log::info('Form submitted', $request->except(['password', 'password_confirmation']));

            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'gender' => 'required|in:laki-laki,perempuan',
                'birth_date' => 'required|date',
                'birth_place' => 'required|string|max:255',
                'religion' => 'required|string|max:255',
                'nik' => 'required|numeric|digits:16|unique:student,nik',
                'nisn' => 'nullable|numeric|digits:10|unique:student,nisn',
                'no_absen' => 'nullable|string|max:20',
                'no_card' => 'nullable|string|max:20',
                'address' => 'required|string',
                'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:500',
                'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
                'kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
                'akte_lahir' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
                'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
                'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
            ], [
                'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'nisn.unique' => 'NISN sudah terdaftar.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nisn.digits' => 'NISN harus 10 digit.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Simpan semua file dengan pengecekan yang lebih baik
            $photoPath = $this->uploadFile($request, 'photo_path', 'photos');
            $ijazahPath = $this->uploadFile($request, 'ijazah', 'documents/ijazah');
            $kartuKeluargaPath = $this->uploadFile($request, 'kartu_keluarga', 'documents/kartu_keluarga');
            $akteLahirPath = $this->uploadFile($request, 'akte_lahir', 'documents/akte_lahir');
            $ktpPath = $this->uploadFile($request, 'ktp', 'documents/ktp');
            $sertifikatPath = $this->uploadFile($request, 'sertifikat', 'documents/sertifikat'); // bisa null

            // Simpan data user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'photo_path' => $photoPath,
                'status' => $request->has('status') ? $request->status : true,
            ];

            // Generate password if not provided
            $defaultPassword = null;
            if (empty($validated['password'])) {
                $defaultPassword = 'password123';
                $userData['password'] = Hash::make($defaultPassword);
            } else {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user = User::create($userData);

            // Assign role student
            $user->assignRole('student');

            // Simpan data student
            $student = Student::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'nik' => $validated['nik'],
                'no_absen' => $validated['no_absen'],
                'no_card' => $validated['no_card'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'birth_place' => $validated['birth_place'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'ijazah' => $ijazahPath,
                'kartu_keluarga' => $kartuKeluargaPath,
                'akte_lahir' => $akteLahirPath,
                'ktp' => $ktpPath,
                'sertifikat' => $sertifikatPath,
                'status' => 'siswa',
            ]);

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                $message = 'Data siswa berhasil ditambahkan.';
                if ($defaultPassword) {
                    $message .= " Password default: {$defaultPassword}";
                }
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'student' => $student->load('user')
                ]);
            }

            // Redirect ke halaman yang benar
            $successMessage = 'Data siswa berhasil ditambahkan.';
            if ($defaultPassword) {
                $successMessage .= " Password default: {$defaultPassword}";
            }
            return redirect()->route('students.index')
                ->with('success', $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->route('students.create')
                ->withInput()
                ->with('error', 'Validation error. Please check your input.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendaftar: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('students.create')
                ->withInput()
                ->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Pastikan hanya siswa dengan status 'siswa' yang bisa diakses
        if ($student->status !== 'siswa') {
            abort(404, 'Siswa tidak ditemukan atau belum diterima.');
        }

        $student->load('user');
        return view('students.show', compact('student'));
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
        
        $student = Student::with('user')->findOrFail($id);

        // Pastikan hanya siswa dengan status 'siswa' yang bisa diedit
        if ($student->status !== 'siswa') {
            abort(404, 'Siswa tidak ditemukan atau belum diterima.');
        }

        if (request()->ajax()) {
            $view = view('students._form', [
                'student' => $student,
                'action' => route('students.update', $student->id),
                'method' => 'PUT',
                'title' => __('index.edit_student')
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.edit_student')
            ]);
        }

        return view('students.addEdit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::with('user')->findOrFail($id);

        // Pastikan hanya siswa dengan status 'siswa' yang bisa diupdate
        if ($student->status !== 'siswa') {
            abort(404, 'Siswa tidak ditemukan atau belum diterima.');
        }

        // Validate the request data - different validation for AJAX vs normal requests
        if ($request->ajax()) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'nisn' => 'nullable|string|max:20',
                'nik' => 'nullable|string|max:20',
                'phone' => 'nullable|string|max:20',
                'no_card' => 'nullable|string|max:20',
                'no_absen' => 'nullable|string|max:20',
                'gender' => 'nullable|string|max:20',
                'birth_place' => 'nullable|string|max:100',
                'birth_date' => 'nullable|date',
                'religion' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:255',
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'nullable|string|min:8',
                'phone' => 'nullable|string|max:20',
                'nisn' => 'nullable|string|max:20',
                'nik' => 'nullable|string|max:20',
                'no_card' => 'nullable|string|max:20',
                'no_absen' => 'nullable|string|max:20',
                'gender' => 'nullable|string|max:20',
                'birth_place' => 'nullable|string|max:100',
                'birth_date' => 'nullable|date',
                'religion' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:255',
            ]);
        }



        try {
            DB::beginTransaction();

            // Handle photo upload (from users table)
            $photoPath = $student->user->photo_path; // Keep existing photo by default
            if ($request->hasFile('photo_path')) {
                // Delete old photo if exists
                if ($student->user->photo_path && Storage::disk('public')->exists($student->user->photo_path)) {
                    Storage::disk('public')->delete($student->user->photo_path);
                }
                // Upload new photo
                $photoPath = $request->file('photo_path')->store('students/photos', 'public');
            }

            // Handle document files (from students table)
            $ijazahPath = $student->ijazah;
            if ($request->hasFile('ijazah')) {
                // Delete old ijazah if exists
                if ($student->ijazah && Storage::disk('public')->exists($student->ijazah)) {
                    Storage::disk('public')->delete($student->ijazah);
                }
                // Upload new ijazah
                $ijazahPath = $request->file('ijazah')->store('students/documents', 'public');
            }

            $kartuKeluargaPath = $student->kartu_keluarga;
            if ($request->hasFile('kartu_keluarga')) {
                // Delete old kartu_keluarga if exists
                if ($student->kartu_keluarga && Storage::disk('public')->exists($student->kartu_keluarga)) {
                    Storage::disk('public')->delete($student->kartu_keluarga);
                }
                // Upload new kartu_keluarga
                $kartuKeluargaPath = $request->file('kartu_keluarga')->store('students/documents', 'public');
            }

            $akteLahirPath = $student->akte_lahir;
            if ($request->hasFile('akte_lahir')) {
                // Delete old akte_lahir if exists
                if ($student->akte_lahir && Storage::disk('public')->exists($student->akte_lahir)) {
                    Storage::disk('public')->delete($student->akte_lahir);
                }
                // Upload new akte_lahir
                $akteLahirPath = $request->file('akte_lahir')->store('students/documents', 'public');
            }

            $ktpPath = $student->ktp;
            if ($request->hasFile('ktp')) {
                // Delete old ktp if exists
                if ($student->ktp && Storage::disk('public')->exists($student->ktp)) {
                    Storage::disk('public')->delete($student->ktp);
                }
                // Upload new ktp
                $ktpPath = $request->file('ktp')->store('students/documents', 'public');
            }

            $sertifikatPath = $student->sertifikat;
            if ($request->hasFile('sertifikat')) {
                // Delete old sertifikat if exists
                if ($student->sertifikat && Storage::disk('public')->exists($student->sertifikat)) {
                    Storage::disk('public')->delete($student->sertifikat);
                }
                // Upload new sertifikat
                $sertifikatPath = $request->file('sertifikat')->store('students/documents', 'public');
            }

            // Update user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status ? true : false,
                'photo_path' => $photoPath,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->user->update($userData);

            // Update student data
            $student->update([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'no_card' => $request->no_card,
                'no_absen' => $request->no_absen,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'religion' => $request->religion,
                'address' => $request->address,
                'ijazah' => $ijazahPath,
                'kartu_keluarga' => $kartuKeluargaPath,
                'akte_lahir' => $akteLahirPath,
                'ktp' => $ktpPath,
                'sertifikat' => $sertifikatPath,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Siswa berhasil diperbarui.',
                    'student' => $student->load('user'),
                ]);
            }

            return redirect()->route('students.index')->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();

            // Delete uploaded files if they exist and are different from original
            if ($request->hasFile('photo_path') && $photoPath !== $student->user->photo_path && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            if ($request->hasFile('ijazah') && $ijazahPath !== $student->ijazah && Storage::disk('public')->exists($ijazahPath)) {
                Storage::disk('public')->delete($ijazahPath);
            }
            if ($request->hasFile('kartu_keluarga') && $kartuKeluargaPath !== $student->kartu_keluarga && Storage::disk('public')->exists($kartuKeluargaPath)) {
                Storage::disk('public')->delete($kartuKeluargaPath);
            }
            if ($request->hasFile('akte_lahir') && $akteLahirPath !== $student->akte_lahir && Storage::disk('public')->exists($akteLahirPath)) {
                Storage::disk('public')->delete($akteLahirPath);
            }
            if ($request->hasFile('ktp') && $ktpPath !== $student->ktp && Storage::disk('public')->exists($ktpPath)) {
                Storage::disk('public')->delete($ktpPath);
            }
            if ($request->hasFile('sertifikat') && $sertifikatPath !== $student->sertifikat && Storage::disk('public')->exists($sertifikatPath)) {
                Storage::disk('public')->delete($sertifikatPath);
            }

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update student: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('students.edit', $id)
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

            $student = Student::withTrashed()->findOrFail($id);

            // Pastikan hanya siswa dengan status 'siswa' yang bisa dihapus
            if ($student->status !== 'siswa') {
                abort(404, 'Siswa tidak ditemukan atau belum diterima.');
            }

            $user = $student->user;
            
            // Hapus foto profil user jika ada
            if ($user->photo_path) {
                $userPhotoPath = storage_path('app/public/' . $user->photo_path);
                if (file_exists($userPhotoPath)) {
                    unlink($userPhotoPath);
                    
                    // Hapus direktori foto profil jika kosong
                    $userPhotoDir = dirname($userPhotoPath);
                    if (is_dir($userPhotoDir) && count(glob($userPhotoDir . '/*')) === 0) {
                        rmdir($userPhotoDir);
                    }
                }
            }
            
            // Daftar field dokumen yang perlu dihapus
            $documentFields = [
                'face_photo',
                'ktp',
                'kartu_keluarga',
                'ijazah',
                'akte_lahir',
                'sertifikat'
            ];

            // Hapus semua file dokumen yang ada
            foreach ($documentFields as $field) {
                if (!empty($student->$field)) {
                    $filePath = storage_path('app/public/' . $student->$field);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    
                    // Hapus direktori dokumen jika kosong
                    $directory = dirname($filePath);
                    if (is_dir($directory) && count(glob($directory . '/*')) === 0) {
                        rmdir($directory);
                    }
                }
            }

            // Hapus data student dan user
            $student->forceDelete(); // Force delete untuk menghapus permanen
            $user->delete(); // Hapus user

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

            // Pastikan user memiliki role student
            if (!$user->hasRole('student')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan siswa.'
                ], 404);
            }

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
     * Helper function untuk upload file
     */
    private function uploadFile(Request $request, $fieldName, $folder)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            return $file->storeAs($folder, $fileName, 'public');
        }
        return null;
    }
}
