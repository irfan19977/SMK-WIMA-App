<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING'],
            ['name' => 'Pendidikan Agama dan Budi Pekerti', 'code' => 'PABP'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKN'],
            ['name' => 'Fisika', 'code' => 'FIS'],
            ['name' => 'Kimia', 'code' => 'KIM'],
            ['name' => 'Biologi', 'code' => 'BIO'],
            ['name' => 'Sejarah', 'code' => 'SEJ'],
            ['name' => 'Ekonomi', 'code' => 'EKO'],
            ['name' => 'Geografi', 'code' => 'GEO'],
            ['name' => 'Sosiologi', 'code' => 'SOS'],
            ['name' => 'Seni Budaya', 'code' => 'SBD'],
            ['name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'code' => 'PJOK'],
            ['name' => 'Prakarya', 'code' => 'PRK'],
            ['name' => 'Informatika', 'code' => 'INF'],
            ['name' => 'Bahasa Jawa', 'code' => 'BJW'],
            ['name' => 'Projek Penguatan Profil Pelajar Pancasila', 'code' => 'P5'],
            ['name' => 'Simulasi dan Komunikasi Digital', 'code' => 'SKD'],
            ['name' => 'Pemrograman Dasar', 'code' => 'PD'],
            ['name' => 'Dasar-Dasar Kejuruan', 'code' => 'DDK'],
            ['name' => 'Pemrograman Web dan Perangkat Bergerak', 'code' => 'PWPB'],
            ['name' => 'Pemrograman Berorientasi Objek', 'code' => 'PBO'],
            ['name' => 'Basis Data', 'code' => 'BD'],
        ];

        foreach ($subjects as $subject) {
            DB::table('subject')->insert([
                'id'         => Str::uuid()->toString(),
                'name'       => $subject['name'],
                'code'       => $subject['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(count($subjects) . ' subjects seeded.');
    }
}
