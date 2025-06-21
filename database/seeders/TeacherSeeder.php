<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teacher users by email
        $teacherEmails = [
            'teacher@gmail.com',
            'hekel@gmail.com'
        ];
        
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        $teacherData = [
            [
                'name' => 'Teacher',
                'nip' => '198001010000001',
                'qrcode' => 'TCH000001',
                'no_card' => 'CARD-TCH-0001',
                'education_level' => 'S1',
                'education_major' => 'Pendidikan Matematika',
                'education_institution' => 'Universitas Negeri Jakarta',
                'gender' => 'laki-laki',
                'province' => 'Jawa Timur',
                'regency' => 'Kediri',
                'district' => 'Kota Kediri',
                'village' => 'Semampir',
                'address' => 'Jl. Pendidikan No. 1, Kediri',
            ],
            [
                'name' => 'Hekel',
                'nip' => '198001010000002',
                'qrcode' => 'TCH000002',
                'no_card' => 'CARD-TCH-0002',
                'education_level' => 'S1',
                'education_major' => 'Pendidikan Bahasa Inggris',
                'education_institution' => 'Universitas Negeri Malang',
                'gender' => 'laki-laki',
                'province' => 'Jawa Timur',
                'regency' => 'Malang',
                'district' => 'Kota Malang',
                'village' => 'Lowokwaru',
                'address' => 'Jl. Veteran No. 2, Malang',
            ],
        ];

        foreach ($teacherEmails as $index => $email) {
            $teacherUser = User::where('email', $email)->first();
            
            if ($teacherUser) {
                Teacher::create([
                    'id' => Str::uuid(),
                    'user_id' => $teacherUser->id,
                    'name' => $teacherData[$index]['name'],
                    'nip' => $teacherData[$index]['nip'],
                    'qrcode' => $teacherData[$index]['qrcode'],
                    'no_card' => $teacherData[$index]['no_card'],
                    'education_level' => $teacherData[$index]['education_level'],
                    'education_major' => $teacherData[$index]['education_major'],
                    'education_institution' => $teacherData[$index]['education_institution'],
                    'gender' => $teacherData[$index]['gender'],
                    'province' => $teacherData[$index]['province'],
                    'regency' => $teacherData[$index]['regency'],
                    'district' => $teacherData[$index]['district'],
                    'village' => $teacherData[$index]['village'],
                    'address' => $teacherData[$index]['address'],
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]);
            }
        }
    }
}
