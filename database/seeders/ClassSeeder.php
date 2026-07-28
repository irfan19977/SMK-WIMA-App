<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'name' => 'X TKJ 2026/2027',
                'code' => 'CLS-TKJ-X-26',
                'grade' => '10',
                'major' => 'Teknik Komputer & Jaringan',
                'academic_year' => '2026/2027',
                'is_archived' => false,
            ],
        ];

        foreach ($classes as $class) {
            DB::table('classes')->insert([
                'id' => Str::uuid()->toString(),
                'name' => $class['name'],
                'code' => $class['code'],
                'grade' => $class['grade'],
                'major' => $class['major'],
                'academic_year' => $class['academic_year'],
                'is_archived' => $class['is_archived'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Class X TKJ seeded.');
    }
}
