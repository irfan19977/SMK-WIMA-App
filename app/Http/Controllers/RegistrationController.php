<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
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
            DB::beginTransaction();

            // Validate the request
            $validatedData = $this->validateRegistration($request);

            // Create user account
            $user = User::create([
                'id' => Str::uuid(),
                'name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone' => $validatedData['phone'],
                'status' => 'pending', // Registration pending approval
                'photo_path' => $this->handlePhotoUpload($request),
            ]);

            // Create student record
            $student = Student::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'full_name' => $validatedData['full_name'],
                'nickname' => $validatedData['nickname'] ?? null,
                'gender' => $validatedData['gender'],
                'birth_date' => $validatedData['birth_date'],
                'birth_place' => $validatedData['birth_place'],
                'religion' => $validatedData['religion'],
                'nisn' => $validatedData['nisn'] ?? null,
                'nik' => $validatedData['nik'],
                'address' => $validatedData['address'],
                'phone' => $validatedData['phone'],
                'whatsapp' => $validatedData['whatsapp'] ?? null,
                'blood_type' => $validatedData['blood_type'] ?? null,
                'is_active' => false, // Inactive until approved
            ]);

            // Create parent records
            $this->createParentRecords($validatedData, $student->id);

            // Handle document uploads
            $this->handleDocumentUploads($request, $student->id);

            DB::commit();

            Log::info('Registration completed successfully', [
                'user_id' => $user->id,
                'student_id' => $student->id,
                'email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim! Silakan cek email untuk informasi lebih lanjut.',
                'registration_number' => 'REG' . date('Y') . str_pad($student->id, 4, '0', STR_PAD_LEFT)
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Validate registration form data
     */
    private function validateRegistration(Request $request)
    {
        return $request->validate([
            // Account Information
            'email' => 'required|email|unique:users,email',
            'confirm_email' => 'required|same:email',
            'password' => 'required|min:8|confirmed',

            // Personal Information
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'religion' => 'required|string|max:50',
            'nisn' => 'nullable|string|max:20',
            'nik' => 'required|string|max:20',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'blood_type' => 'nullable|in:A,B,AB,O',

            // Parent Information
            'father_name' => 'required|string|max:255',
            'father_nik' => 'required|string|max:20',
            'father_birth_date' => 'nullable|date',
            'father_occupation' => 'required|string|max:255',
            'father_education' => 'nullable|string|max:50',
            'father_income' => 'nullable|string|max:255',
            'father_phone' => 'required|string|max:20',

            'mother_name' => 'required|string|max:255',
            'mother_nik' => 'required|string|max:20',
            'mother_birth_date' => 'nullable|date',
            'mother_occupation' => 'required|string|max:255',
            'mother_education' => 'nullable|string|max:50',
            'mother_income' => 'nullable|string|max:255',
            'mother_phone' => 'required|string|max:20',

            // Guardian (optional)
            'has_guardian' => 'nullable|boolean',
            'guardian_name' => 'nullable|required_if:has_guardian,true|string|max:255',
            'guardian_relation' => 'nullable|required_if:has_guardian,true|string|max:255',
            'guardian_occupation' => 'nullable|required_if:has_guardian,true|string|max:255',
            'guardian_phone' => 'nullable|required_if:has_guardian,true|string|max:20',

            // Terms agreement
            'agree_terms' => 'required|accepted',
        ]);
    }

    /**
     * Handle photo upload
     */
    private function handlePhotoUpload(Request $request)
    {
        if ($request->hasFile('student_photo')) {
            $photo = $request->file('student_photo');
            $filename = time() . '_' . Str::slug($request->full_name) . '.' . $photo->getClientOriginalExtension();
            return $photo->storeAs('student_photos', $filename, 'public');
        }

        return null;
    }

    /**
     * Create parent records
     */
    private function createParentRecords($validatedData, $studentId)
    {
        // Create father record
        ParentModel::create([
            'id' => Str::uuid(),
            'student_id' => $studentId,
            'name' => $validatedData['father_name'],
            'nik' => $validatedData['father_nik'],
            'birth_date' => $validatedData['father_birth_date'],
            'occupation' => $validatedData['father_occupation'],
            'education' => $validatedData['father_education'],
            'income' => $validatedData['father_income'],
            'phone' => $validatedData['father_phone'],
            'type' => 'father',
        ]);

        // Create mother record
        ParentModel::create([
            'id' => Str::uuid(),
            'student_id' => $studentId,
            'name' => $validatedData['mother_name'],
            'nik' => $validatedData['mother_nik'],
            'birth_date' => $validatedData['mother_birth_date'],
            'occupation' => $validatedData['mother_occupation'],
            'education' => $validatedData['mother_education'],
            'income' => $validatedData['mother_income'],
            'phone' => $validatedData['mother_phone'],
            'type' => 'mother',
        ]);

        // Create guardian record if exists
        if (!empty($validatedData['has_guardian']) && !empty($validatedData['guardian_name'])) {
            ParentModel::create([
                'id' => Str::uuid(),
                'student_id' => $studentId,
                'name' => $validatedData['guardian_name'],
                'occupation' => $validatedData['guardian_occupation'],
                'phone' => $validatedData['guardian_phone'],
                'type' => 'guardian',
                'relation' => $validatedData['guardian_relation'],
            ]);
        }
    }

    /**
     * Handle document uploads
     */
    private function handleDocumentUploads(Request $request, $studentId)
    {
        $documents = [
            'birth_certificate' => 'akta_kelahiran',
            'family_card' => 'kartu_keluarga',
            'previous_certificate' => 'ijazah_sebelumnya',
            'health_certificate' => 'surat_keterangan_sehat',
        ];

        $uploadPath = 'student_documents/' . $studentId;

        foreach ($documents as $fieldName => $folderName) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $filename = time() . '_' . $fieldName . '.' . $file->getClientOriginalExtension();

                // Store in appropriate folder
                $path = $file->storeAs($uploadPath, $filename, 'public');

                // Here you could save the document path to a documents table
                // For now, we'll just store it in the student record or create a separate documents table
            }
        }

        // Handle multiple achievement certificates
        if ($request->hasFile('achievement_certificates')) {
            foreach ($request->file('achievement_certificates') as $file) {
                $filename = time() . '_achievement_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($uploadPath, $filename, 'public');
            }
        }
    }
}
