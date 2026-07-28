<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name'        => 'Admin Sekolah',
                'email'       => 'admin@smkwima.sch.id',
                'role'        => 'Admin',
                'phone'       => '08129876543',
                'birth_place' => 'Surabaya',
                'birth_date'  => '1992-05-15',
                'province'    => 'Jawa Timur',
                'regency'     => 'Surabaya',
                'district'    => 'Gubeng',
                'village'     => 'Mojo',
                'address'     => 'Jl. Raya Gubeng No. 10',
            ],
        ];

        foreach ($admins as $data) {
            $userUuid = Str::uuid()->toString();

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

            DB::table('administrator')->insert([
                'id'          => Str::uuid()->toString(),
                'user_id'     => $userUuid,
                'name'        => $data['name'],
                'phone'       => $data['phone'],
                'birth_place' => $data['birth_place'],
                'birth_date'  => $data['birth_date'],
                'province'    => $data['province'],
                'regency'     => $data['regency'],
                'district'    => $data['district'],
                'village'     => $data['village'],
                'address'     => $data['address'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            User::find($userUuid)->assignRole($data['role']);
        }

        $this->command->info('Admin users seeded.');
    }
}
