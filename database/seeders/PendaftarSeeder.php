<?php

namespace Database\Seeders;

use App\Helpers\AcademicYearHelper;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = "2026/2027";

        $pendaftar = [
            [
                'name' => 'Pendaftar Satu',
                'email' => 'pendaftar1@example.com',
                'nisn' => '3000000001',
                'nik' => '3500000000000001',
                'gender' => 'laki-laki',
                'jurusan_utama' => 'Teknik Komputer & Jaringan',
                'jurusan_cadangan' => 'Teknik Kendaraan Ringan Otomotif',
            ],
            [
                'name' => 'Pendaftar Dua',
                'email' => 'pendaftar2@example.com',
                'nisn' => '3000000002',
                'nik' => '3500000000000002',
                'gender' => 'perempuan',
                'jurusan_utama' => 'Teknik Kimian Industri',
                'jurusan_cadangan' => 'Teknik Komputer & Jaringan',
            ],
            [
                'name' => 'Pendaftar Tiga',
                'email' => 'pendaftar3@example.com',
                'nisn' => '3000000003',
                'nik' => '3500000000000003',
                'gender' => 'laki-laki',
                'jurusan_utama' => 'Teknik Bisnis dan Sepeda Motor',
                'jurusan_cadangan' => 'Teknik Kendaraan Ringan Otomotif',
            ],
            [
                'name' => 'Pendaftar Empat',
                'email' => 'pendaftar4@example.com',
                'nisn' => '3000000004',
                'nik' => '3500000000000004',
                'gender' => 'perempuan',
                'jurusan_utama' => 'Teknik Kendaraan Ringan Otomotif',
                'jurusan_cadangan' => 'Teknik Bisnis dan Sepeda Motor',
            ],
            [
                'name' => 'Pendaftar Lima',
                'email' => 'pendaftar5@example.com',
                'nisn' => '3000000005',
                'nik' => '3500000000000005',
                'gender' => 'laki-laki',
                'jurusan_utama' => 'Teknik Komputer & Jaringan',
                'jurusan_cadangan' => 'Teknik Kimian Industri',
            ],
        ];

        foreach ($pendaftar as $data) {
            // Buat user
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            // Buat record student sebagai calon siswa (pendaftar)
            Student::updateOrCreate(
                ['nisn' => $data['nisn']],
                [
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'nisn' => $data['nisn'],
                    'nik' => $data['nik'],
                    'gender' => $data['gender'],
                    'status' => 'calon siswa',
                    'jurusan_utama' => $data['jurusan_utama'],
                    'jurusan_cadangan' => $data['jurusan_cadangan'],
                    'academic_year' => $academicYear,
                ]
            );
        }
    }
}
