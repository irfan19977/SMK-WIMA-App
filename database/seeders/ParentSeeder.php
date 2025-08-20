<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get parent user by email
        $parentUser = User::where('email', 'parent@gmail.com')->first();
        
        // Get first student to link with parent
        $firstStudent = Student::whereHas('user', function($query) {
            $query->where('email', 'student1@gmail.com');
        })->first();
        
        // Get admin user for created_by
        $adminUser = User::where('email', 'superadmin@gmail.com')->first();

        if ($parentUser) {
            ParentModel::create([
                'id' => Str::uuid(),
                'user_id' => $parentUser->id,
                'student_id' => $firstStudent->id ?? null,
                'name' => $parentUser->name,
                'jenis_kelamin' => 'laki-laki',
                'status' => 'ayah',
                'province' => 'Jawa Timur',
                'regency' => 'Kediri',
                'district' => 'Kota Kediri',
                'village' => 'Kampung Dalem',
                'address' => 'Jl. Orang Tua No. 1, Kediri',
                'created_by' => $adminUser->id ?? null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
