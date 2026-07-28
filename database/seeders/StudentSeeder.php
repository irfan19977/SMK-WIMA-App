<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indah', 'Joko', 'Kirana', 'Lutfi', 'Maya', 'Nanda', 'Oscar', 'Putri', 'Qori', 'Raka', 'Sari', 'Teguh', 'Umar', 'Vina', 'Wahyu', 'Xena', 'Yoga', 'Zahra'];
        $lastNames  = ['Santoso', 'Wijaya', 'Kusuma', 'Putri', 'Pratama', 'Nugroho', 'Andini', 'Saputra', 'Rahayu', 'Mahendra', 'Sari', 'Hidayat', 'Lestari', 'Ramadhan', 'Surya', 'Oktaviani', 'Putra', 'Maulana', 'Anggraini', 'Firmansyah'];

        for ($i = 1; $i <= 50; $i++) {
            $firstName = $firstNames[($i - 1) % count($firstNames)];
            $lastName  = $lastNames[($i - 1) % count($lastNames)];
            $name      = "{$firstName} {$lastName} {$i}";
            $email     = "student{$i}@smkwima.sch.id";
            $nisn      = str_pad((string) (2000000000 + $i), 10, '0', STR_PAD_LEFT);
            $nik       = str_pad((string) (3200000000000000 + $i), 16, '0', STR_PAD_LEFT);
            $gender    = $i % 2 === 0 ? 'perempuan' : 'laki-laki';
            $noCard    = 'RFID' . str_pad((string) $i, 5, '0', STR_PAD_LEFT);

            $this->createStudent($name, $email, $noCard, $nisn, $nik, $gender, $i);
        }

        $specificStudents = [
            ['name' => 'Tristan Ibrahimmi R.', 'no_card' => '93:34:A8:07', 'gender' => 'laki-laki', 'index' => 51],
            ['name' => 'Tri Sunengseh', 'no_card' => '73:CB:D9:07', 'gender' => 'perempuan', 'index' => 52],
            ['name' => 'Ilham Maulana', 'no_card' => '43:40:CE:11', 'gender' => 'laki-laki', 'index' => 53],
            ['name' => 'M. Ubaidillah', 'no_card' => 'B3:7B:49:FE', 'gender' => 'laki-laki', 'index' => 54],
            ['name' => 'Silvania Naen Nova', 'no_card' => 'C0:AD:04:58', 'gender' => 'perempuan', 'index' => 55],
            ['name' => 'Novita Regina Putri', 'no_card' => '63:0F:A0:F7', 'gender' => 'perempuan', 'index' => 56],
        ];

        foreach ($specificStudents as $data) {
            $i     = $data['index'];
            $email = "student{$i}@smkwima.sch.id";
            $nisn  = str_pad((string) (2000000000 + $i), 10, '0', STR_PAD_LEFT);
            $nik   = str_pad((string) (3200000000000000 + $i), 16, '0', STR_PAD_LEFT);

            $this->createStudent($data['name'], $email, $data['no_card'], $nisn, $nik, $data['gender'], $i);
        }

        $this->command->info('56 students seeded.');
    }

    private function createStudent(string $name, string $email, string $noCard, string $nisn, string $nik, string $gender, int $index): void
    {
        $userUuid = Str::uuid()->toString();

        DB::table('users')->insert([
            'id'         => $userUuid,
            'name'       => $name,
            'email'      => $email,
            'password'   => Hash::make('password'),
            'status'     => true,
            'join_date'  => now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('student')->insert([
            'id'          => Str::uuid()->toString(),
            'user_id'     => $userUuid,
            'no_absen'    => str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            'no_card'     => $noCard,
            'name'        => $name,
            'phone'       => '0812' . str_pad((string) (1000000 + $index), 8, '0', STR_PAD_LEFT),
            'nisn'        => $nisn,
            'nik'         => $nik,
            'gender'      => $gender,
            'birth_date'  => now()->subYears(16)->subDays($index)->format('Y-m-d'),
            'birth_place' => 'Kota ' . $index,
            'religion'    => 'Islam',
            'address'     => "Jl. Pendidikan No. {$index}",
            'parent_name' => "Orang Tua {$name}",
            'parent_phone' => '0813' . str_pad((string) (1000000 + $index), 8, '0', STR_PAD_LEFT),
            'academic_year' => '2025/2026',
            'status'      => 'siswa',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        User::find($userUuid)->assignRole('Student');
    }
}
