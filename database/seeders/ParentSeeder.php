<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            ['student_name' => 'Tristan Ibrahimmi R.', 'phone' => '081300000051', 'gender' => 'laki-laki'],
            ['student_name' => 'Tri Sunengseh', 'phone' => '085802733781', 'gender' => 'perempuan'],
            ['student_name' => 'Ilham Maulana', 'phone' => '081300000053', 'gender' => 'laki-laki'],
            ['student_name' => 'M. Ubaidillah', 'phone' => '081300000054', 'gender' => 'laki-laki'],
            ['student_name' => 'Silvania Naen Nova', 'phone' => '082233088346', 'gender' => 'perempuan'],
            ['student_name' => 'Novita Regina Putri', 'phone' => '081300000056', 'gender' => 'perempuan'],
        ];

        foreach ($parents as $index => $data) {
            $student = DB::table('student')->where('name', $data['student_name'])->first();

            if (!$student) {
                $this->command->warn("Student {$data['student_name']} not found, skipping parent.");
                continue;
            }

            $i        = $index + 51;
            $email    = "parent{$i}@smkwima.sch.id";
            $userUuid = Str::uuid()->toString();

            DB::table('users')->insert([
                'id'         => $userUuid,
                'name'       => "Wali Murid {$data['student_name']}",
                'email'      => $email,
                'password'   => Hash::make('password'),
                'status'     => true,
                'join_date'  => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('parent')->insert([
                'id'           => Str::uuid()->toString(),
                'user_id'      => $userUuid,
                'student_id'   => $student->id,
                'name'         => "Wali Murid {$data['student_name']}",
                'phone'        => $data['phone'],
                'jenis_kelamin' => $data['gender'],
                'status'       => 'wali',
                'address'      => "Jl. Pendidikan No. {$i}",
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            User::find($userUuid)->assignRole('Parent');
        }

        $this->command->info('Parent guardians seeded.');
    }
}
