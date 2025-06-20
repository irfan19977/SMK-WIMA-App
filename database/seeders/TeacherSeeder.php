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
        // Get teacher user by email
        $teacherUser = User::where('email', 'teacher@gmail.com')->first();
        
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        if ($teacherUser) {
            Teacher::create([
                'id' => Str::uuid(),
                'user_id' => $teacherUser->id,
                'name' => $teacherUser->name,
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
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
