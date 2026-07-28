<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'name'                  => 'Budi Santoso, S.Pd',
                'email'                 => 'budi.santoso@smkwima.sch.id',
                'nip'                   => '198001012010011001',
                'gender'                => 'laki-laki',
                'phone'                 => '081234560001',
                'education_level'       => 'S1',
                'education_major'       => 'Pendidikan Matematika',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name'                  => 'Siti Nurhaliza, S.Pd',
                'email'                 => 'siti.nurhaliza@smkwima.sch.id',
                'nip'                   => '198502022015022002',
                'gender'                => 'perempuan',
                'phone'                 => '081234560002',
                'education_level'       => 'S1',
                'education_major'       => 'Pendidikan Bahasa Indonesia',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name'                  => 'Ahmad Fauzi, S.Kom',
                'email'                 => 'ahmad.fauzi@smkwima.sch.id',
                'nip'                   => '198803152018031003',
                'gender'                => 'laki-laki',
                'phone'                 => '081234560003',
                'education_level'       => 'S1',
                'education_major'       => 'Teknik Informatika',
                'education_institution' => 'Universitas Indonesia',
            ],
            [
                'name'                  => 'Dewi Lestari, S.Pd',
                'email'                 => 'dewi.lestari@smkwima.sch.id',
                'nip'                   => '199005202019052004',
                'gender'                => 'perempuan',
                'phone'                 => '081234560004',
                'education_level'       => 'S1',
                'education_major'       => 'Pendidikan Ekonomi',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name'                  => 'Rudi Hartono, S.T',
                'email'                 => 'rudi.hartono@smkwima.sch.id',
                'nip'                   => '198707152014071005',
                'gender'                => 'laki-laki',
                'phone'                 => '081234560005',
                'education_level'       => 'S1',
                'education_major'       => 'Teknik Mesin',
                'education_institution' => 'Universitas Indonesia',
            ],
        ];

        foreach ($teachers as $data) {
            $userUuid    = Str::uuid()->toString();
            $teacherUuid = Str::uuid()->toString();

            DB::table('users')->insert([
                'id'         => $userUuid,
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => Hash::make('password'),
                'status'     => true,
                'join_date'  => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('teacher')->insert([
                'id'                    => $teacherUuid,
                'user_id'               => $userUuid,
                'name'                  => $data['name'],
                'phone'                 => $data['phone'],
                'nip'                   => $data['nip'],
                'gender'                => $data['gender'],
                'education_level'       => $data['education_level'],
                'education_major'       => $data['education_major'],
                'education_institution' => $data['education_institution'],
                'province'              => 'DKI Jakarta',
                'regency'               => 'Jakarta Pusat',
                'district'              => 'Menteng',
                'village'               => 'Menteng',
                'address'               => 'Jl. Guru Indonesia No. ' . rand(1, 100),
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            User::find($userUuid)->assignRole('Teacher');
        }

        $this->command->info('Teacher users seeded.');
    }
}
