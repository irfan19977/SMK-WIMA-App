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
use Illuminate\Support\Facades\Validator;
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
            // Debug: Log form data (except sensitive information)
            Log::info('Form submission started', $request->except(['password', 'password_confirmation']));
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'phone' => 'required|numeric|digits_between:10,15',
                'gender' => 'required|in:laki-laki,perempuan',
                'birth_date' => 'required|date|before:-10 years',
                'birth_place' => 'required|string|max:100',
                'religion' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                'nik' => 'required|numeric|digits:16|unique:student,nik',
                'nisn' => 'required|numeric|digits:10|unique:student,nisn',
                'address' => 'required|string|max:500',
                // 'asalSekolah' => 'required|string|max:255',
                'jurusan_utama' => 'required|in:Teknik Kendaraan Ringan,Teknik Bisnis Sepeda Motor,Teknik Kimia Industri,Teknik Komputer dan Jaringan',
                'jurusan_cadangan' => 'required|in:Teknik Kendaraan Ringan,Teknik Bisnis Sepeda Motor,Teknik Kimia Industri,Teknik Komputer dan Jaringan|different:jurusan_utama',
                'photo_path' => 'required|image|mimes:jpeg,png,jpg|max:500',
                'ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'kartu_keluarga' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'akte_lahir' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:500',
                'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:500',
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'digits' => ':attribute harus :digits digit.',
                'digits_between' => ':attribute harus antara :min sampai :max digit.',
                'numeric' => ':attribute harus berupa angka.',
                'date' => 'Format tanggal tidak valid.',
                'before' => 'Anda harus berusia minimal 10 tahun untuk mendaftar.',
                'in' => 'Nilai :attribute tidak valid.',
                'different' => 'Jurusan cadangan harus berbeda dengan jurusan utama.',
                'image' => 'File harus berupa gambar.',
                'mimes' => 'Format file tidak didukung. Gunakan format: :values.',
                'max' => 'Ukuran file tidak boleh lebih dari :max KB.'
            ], [
                'name' => 'nama lengkap',
                'email' => 'email',
                'password' => 'password',
                'phone' => 'nomor telepon',
                'gender' => 'jenis kelamin',
                'birth_date' => 'tanggal lahir',
                'birth_place' => 'tempat lahir',
                'religion' => 'agama',
                'nik' => 'NIK',
                'nisn' => 'NISN',
                'address' => 'alamat',
                // 'asalSekolah' => 'asal sekolah',
                'jurusan_utama' => 'jurusan utama',
                'jurusan_cadangan' => 'jurusan cadangan',
                'photo_path' => 'foto',
                'ijazah' => 'ijazah/SKL',
                'kartu_keluarga' => 'kartu keluarga',
                'akte_lahir' => 'akte lahir',
                'ktp' => 'KTP',
                'sertifikat' => 'sertifikat'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            // Upload files
            $photoPath = $this->uploadFile($request, 'photo_path', 'photos');
            $ijazahPath = $this->uploadFile($request, 'ijazah', 'documents/ijazah');
            $kartuKeluargaPath = $this->uploadFile($request, 'kartu_keluarga', 'documents/kartu_keluarga');
            $akteLahirPath = $this->uploadFile($request, 'akte_lahir', 'documents/akte_lahir');
            $ktpPath = $this->uploadFile($request, 'ktp', 'documents/ktp');
            $sertifikatPath = $request->hasFile('sertifikat') ? $this->uploadFile($request, 'sertifikat', 'documents/sertifikat') : null;

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'photo_path' => $photoPath,
                'status' => true,
            ]);

            // Assign student role
            $user->assignRole('student');

            // Create student record
            Student::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'name' => $request->name,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'religion' => $request->religion,
                'address' => $request->address,
                // 'asal_sekolah' => $request->asalSekolah,
                'ijazah' => $ijazahPath,
                'kartu_keluarga' => $kartuKeluargaPath,
                'akte_lahir' => $akteLahirPath,
                'ktp' => $ktpPath,
                'sertifikat' => $sertifikatPath,
                'status' => 'calon siswa',
                'jurusan_utama' => $request->jurusan_utama,
                'jurusan_cadangan' => $request->jurusan_cadangan,
                'academic_year' => (date('Y') + 1) . '/' . (date('Y') + 2),
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil! Silakan login dengan email dan password yang telah Anda buat.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba beberapa saat lagi atau hubungi admin jika masalah berlanjut. Error: ' . $e->getMessage());
        }
    }

    /**
     * Helper function untuk upload file
     */
    private function uploadFile(Request $request, $fieldName, $folder)
    {
        try {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                
                // Create a safe filename (remove special characters, keep only alphanumeric, dash, and underscore)
                $safeName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $originalName);
                $fileName = time() . '_' . $safeName . '.' . $extension;
                
                // Ensure the directory exists
                $path = $folder . '/' . date('Y/m');
                
                // Store the file with visibility set to 'public'
                $filePath = $file->storeAs($path, $fileName, 'public');
                
                if (!$filePath) {
                    Log::error("Failed to store file: {$fieldName}");
                    throw new \Exception("Gagal mengunggah file {$fieldName}. Silakan coba lagi.");
                }
                
                return $filePath;
            }
            return null;
        } catch (\Exception $e) {
            Log::error("Error uploading file {$fieldName}: " . $e->getMessage());
            throw $e;
        }
    }
}
