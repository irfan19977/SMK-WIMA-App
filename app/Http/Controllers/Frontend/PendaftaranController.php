<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    /**
     * Show the registration form
     */
    public function index()
    {
        return view('home.pendaftaran');
    }

    /**
     * Handle registration form submission
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
                'password' => 'required|min:8|confirmed',
                'phone' => 'required|numeric',
                'gender' => 'required|in:laki-laki,perempuan',
                'birth_date' => 'required|date',
                'birth_place' => 'required|string|max:255',
                'religion' => 'required|string|max:255',
                'nik' => 'required|numeric|digits:16|unique:student,nik',
                'nisn' => 'required|numeric|digits:10|unique:student,nisn',
                'address' => 'required|string',
                'photo_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:500',
                'ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'kartu_keluarga' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'akte_lahir' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
                'terms' => 'required|accepted',
                'jurusan_utama' => 'required',
                'jurusan_cadangan' => 'required',
            ], [
                'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'nisn.unique' => 'NISN sudah terdaftar.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nisn.digits' => 'NISN harus 10 digit.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
                'jurusan_utama.required' => 'Jurusan utama harus dipilih.',
                'jurusan_cadangan.required' => 'Jurusan cadangan harus dipilih.',
            ]);

            // Simpan semua file dengan pengecekan yang lebih baik
            $photoPath = $this->uploadFile($request, 'photo_path', 'photos');
            $ijazahPath = $this->uploadFile($request, 'ijazah', 'documents/ijazah');
            $kartuKeluargaPath = $this->uploadFile($request, 'kartu_keluarga', 'documents/kartu_keluarga');
            $akteLahirPath = $this->uploadFile($request, 'akte_lahir', 'documents/akte_lahir');
            $ktpPath = $this->uploadFile($request, 'ktp', 'documents/ktp');
            $sertifikatPath = $this->uploadFile($request, 'sertifikat', 'documents/sertifikat'); // bisa null

            // Simpan data user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'photo_path' => $photoPath,
                'status' => true,
            ]);

            // Assign role student
            $user->assignRole('student');

            // Simpan data student
            $student = Student::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'nik' => $validated['nik'],
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
                'status' => 'calon siswa',
                'jurusan_utama' => $validated['jurusan_utama'],
                'jurusan_cadangan' => $validated['jurusan_cadangan'],
                'academic_year' => (date('Y') + 1). '/' . (date('Y') + 2),
            ]);

            DB::commit();

            // Redirect ke halaman yang benar
            return redirect()->back()
                ->with('success', 'Pendaftaran berhasil! Silakan login dengan akun yang telah dibuat.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mendaftar: ' . $e->getMessage());
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
