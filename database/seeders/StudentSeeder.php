<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get student users by email
        $studentEmails = [
            'student1@gmail.com',
            'student2@gmail.com',
            'student3@gmail.com'
        ];
        
        // Custom card numbers for each student
        $cardNumbers = [
            '4340CE11', // untuk student1
            'B37B49FE', // untuk student2
            '73CBD907'  // untuk student3
        ];
        
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        foreach ($studentEmails as $index => $email) {
            $studentUser = User::where('email', $email)->first();
            
            if ($studentUser) {
                Student::create([
                    'id' => Str::uuid(),
                    'user_id' => $studentUser->id,
                    'name' => $studentUser->name,
                    'nisn' => '200' . str_pad($index + 1, 7, '0', STR_PAD_LEFT),
                    'qrcode' => 'STD' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    'no_card' => $cardNumbers[$index], // Menggunakan nomor card custom
                    'medical_info' => 'Sehat, tidak ada riwayat penyakit khusus',
                    'birth_place' => 'Kediri',
                    'birth_date' => '2008-0' . ($index + 1) . '-15',
                    'gender' => $index % 2 == 0 ? 'laki-laki' : 'perempuan',
                    'province' => 'Jawa Timur',
                    'regency' => 'Kediri',
                    'district' => 'Kota Kediri',
                    'village' => 'Kampung Dalem',
                    'address' => 'Jl. Siswa No. ' . ($index + 1) . ', Kediri',
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]);
            }
        }
    }
}
