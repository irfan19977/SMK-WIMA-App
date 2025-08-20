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
        // Data siswa individual dengan informasi lengkap
        $studentsData = [
        [
            'email' => 'andyahmadthariqmaulana@gmail.com',
            'nisn' => '2000000001',
            'qrcode' => 'STD000001',
            'no_absen' => 1,
        ],
        [
            'email' => 'azmifawwasfirdausy@gmail.com',
            'nisn' => '2000000002',
            'qrcode' => 'STD000002',
            'no_absen' => 2,
        ],
        [
            'email' => 'faishaldanurwedabismawibowo@gmail.com',
            'nisn' => '2000000003',
            'qrcode' => 'STD000003',
            'no_absen' => 3,
        ],
        [
            'email' => 'muhammadarshadnaufalmustofa@gmail.com',
            'nisn' => '2000000004',
            'qrcode' => 'STD000004',
            'no_absen' => 4,
        ],
        [
            'email' => 'muhammadfuadabdullah@gmail.com',
            'nisn' => '2000000005',
            'qrcode' => 'STD000005',
            'no_absen' => 5,
        ],
        [
            'email' => 'muhammadihsanuddinarsyad@gmail.com',
            'nisn' => '2000000006',
            'qrcode' => 'STD000006',
            'no_absen' => 6,
        ],
        [
            'email' => 'muhammadradonisbaihaqi@gmail.com',
            'nisn' => '2000000007',
            'qrcode' => 'STD000007',
            'no_absen' => 7,
        ],
        [
            'email' => 'muhammadsaidalkatiri@gmail.com',
            'nisn' => '2000000008',
            'qrcode' => 'STD000008',
            'no_absen' => 8,
        ],
        [
            'email' => 'muhammadshidqialkautsar@gmail.com',
            'nisn' => '2000000009',
            'qrcode' => 'STD000009',
            'no_absen' => 9,
        ],
        [
            'email' => 'musamubarak@gmail.com',
            'nisn' => '2000000010',
            'qrcode' => 'STD000010',
            'no_absen' => 10,
        ],
        [
            'email' => 'sholih@gmail.com',
            'nisn' => '2000000011',
            'qrcode' => 'STD000011',
            'no_absen' => 11,
        ],
        [
            'email' => 'zidnyzaydanhabibieizzilhatif@gmail.com',
            'nisn' => '2000000012',
            'qrcode' => 'STD000012',
            'no_absen' => 12,
        ],
        [
            'email' => 'abdullohazkaibadurrohman@gmail.com',
            'nisn' => '2000000013',
            'qrcode' => 'STD000013',
            'no_absen' => 1,
        ],
        [
            'email' => 'ardanfaiarrafan@gmail.com',
            'nisn' => '2000000014',
            'qrcode' => 'STD000014',
            'no_absen' => 2,
        ],
        [
            'email' => 'arkanthaariiqasadullah@gmail.com',
            'nisn' => '2000000015',
            'qrcode' => 'STD000015',
            'no_absen' => 3,
        ],
        [
            'email' => 'athallahassyarif@gmail.com',
            'nisn' => '2000000016',
            'qrcode' => 'STD000016',
            'no_absen' => 4,
        ],
        [
            'email' => 'chevietoraffiframadhan@gmail.com',
            'nisn' => '2000000017',
            'qrcode' => 'STD000017',
            'no_absen' => 5,
        ],
        [
            'email' => 'fahriabdurrahman@gmail.com',
            'nisn' => '2000000018',
            'qrcode' => 'STD000018',
            'no_absen' => 6,
        ],
        [
            'email' => 'farhanmaulanarizqi@gmail.com',
            'nisn' => '2000000019',
            'qrcode' => 'STD000019',
            'no_absen' => 7,
        ],
        [
            'email' => 'irsyaduddin@gmail.com',
            'nisn' => '2000000020',
            'qrcode' => 'STD000020',
            'no_absen' => 8,
        ],
        [
            'email' => 'muhammadazmyashshiddiqie@gmail.com',
            'nisn' => '2000000021',
            'qrcode' => 'STD000021',
            'no_absen' => 9,
        ],
        [
            'email' => 'muhammadabidnaufal@gmail.com',
            'nisn' => '2000000022',
            'qrcode' => 'STD000022',
            'no_absen' => 10,
        ],
        [
            'email' => 'muhammadfadhlirobbiel@gmail.com',
            'nisn' => '2000000023',
            'qrcode' => 'STD000023',
            'no_absen' => 11,
        ],
        [
            'email' => 'muhammadmishaal@gmail.com',
            'nisn' => '2000000024',
            'qrcode' => 'STD000024',
            'no_absen' => 12,
        ],
        [
            'email' => 'naurakhaula@gmail.com',
            'nisn' => '2000000025',
            'qrcode' => 'STD000025',
            'no_absen' => 13,
        ],
        [
            'email' => 'rakapratama@gmail.com',
            'nisn' => '2000000026',
            'qrcode' => 'STD000026',
            'no_absen' => 14,
        ],
        [
            'email' => 'shahdanalqiarraffi@gmail.com',
            'nisn' => '2000000027',
            'qrcode' => 'STD000027',
            'no_absen' => 15,
        ],
        [
            'email' => 'amuhafganalgazali@gmail.com',
            'nisn' => '2000000028',
            'qrcode' => 'STD000028',
            'no_absen' => 16,
        ],
        [
            'email' => 'student1@gmail.com',
            'nisn' => '2000000029',
            'qrcode' => 'STD000029',
            'no_absen' => 1,
        ],
        [
            'email' => 'student2@gmail.com',
            'nisn' => '2000000030',
            'qrcode' => 'STD000030',
            'no_absen' => 2,
        ],
        [
            'email' => 'student3@gmail.com',
            'nisn' => '2000000031',
            'qrcode' => 'STD000031',
            'no_absen' => 3,
        ],
    ];

        // Get admin user for created_by
        $adminUser = User::where('email', 'superadmin@gmail.com')->first();

        foreach ($studentsData as $studentData) {
            $studentUser = User::where('email', $studentData['email'])->first();

            if ($studentUser) {
                Student::create([
                    'id' => Str::uuid(),
                    'user_id' => $studentUser->id,
                    'name' => $studentUser->name,
                    'nisn' => $studentData['nisn'],
                    'qrcode' => $studentData['qrcode'],
                    'no_absen' => $studentData['no_absen'],
                    'gender' =>'laki-laki',
                    'created_by' => $adminUser->id ?? null,
                    'updated_by' => null,
                    'deleted_by' => null,
                ]);

                $this->command->info("Student created: {$studentUser->name}");
            } else {
                $this->command->warn("User not found for email: {$studentData['email']}");
            }
        }
    }
}
