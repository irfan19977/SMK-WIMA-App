<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user by email
        $adminUser = User::where('email', 'administrator@gmail.com')->first();

        if ($adminUser) {
            Administrator::create([
                'id' => Str::uuid(),
                'user_id' => $adminUser->id,
                'name' => $adminUser->name,
                'birth_place' => 'Jakarta',
                'birth_date' => '1980-01-01',
                'province' => 'DKI Jakarta',
                'regency' => 'Jakarta Pusat',
                'district' => 'Menteng',
                'village' => 'Menteng',
                'address' => 'Jl. Sudirman No. 1, Jakarta',
                'created_by' => null,
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
