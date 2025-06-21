<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for created_by
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        $subjects = [
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING'],
            ['name' => 'Ilmu Pengetahuan Alam', 'code' => 'IPA'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'code' => 'IPS'],
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn'],
            ['name' => 'Seni Budaya', 'code' => 'SBUD'],
            ['name' => 'Pendidikan Jasmani dan Kesehatan', 'code' => 'PJOK'],
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'id' => Str::uuid(),
                'name' => $subject['name'],
                'code' => $subject['code'],
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
