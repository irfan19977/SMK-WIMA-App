<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $profile = $user->getProfileData();

        $phoneRules = ['nullable', 'string', 'max:20'];
        if ($profile) {
            $table = match($user->roles->first()?->name) {
                'student', 'Student' => 'student',
                'teacher', 'Teacher' => 'teacher',
                'admin', 'Admin', 'Super Admin' => 'administrator',
                'parent', 'Parent' => 'parent',
                default => null,
            };
            if ($table) {
                $phoneRules[] = Rule::unique($table, 'phone')->ignore($profile->id);
            }
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'phone' => $phoneRules,
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB, will be stored in photo_path
        ];

        // Add student-specific rules if user is student
        if ($this->user()->hasRole('student') || $this->user()->hasRole('Student')) {
            $studentRules = [
                'no_absen' => ['nullable', 'string', 'max:50'],
                'no_card' => ['nullable', 'string', 'max:50'],
                'nisn' => ['nullable', 'string', 'max:20'],
                'nik' => ['nullable', 'string', 'max:20'],
                'gender' => ['nullable', 'in:laki-laki,perempuan'],
                'birth_place' => ['nullable', 'string', 'max:100'],
                'birth_date' => ['nullable', 'date'],
                'religion' => ['nullable', 'string', 'max:50'],
                'jurusan_utama' => ['nullable', 'string', 'max:100'],
                'jurusan_cadangan' => ['nullable', 'string', 'max:100'],
                'academic_year' => ['nullable', 'string', 'max:20'],
            ];
            
            $rules = array_merge($rules, $studentRules);
        }

        return $rules;
    }
}
