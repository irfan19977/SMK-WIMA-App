<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_user_value = [
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        // Ubah struktur ke multi-dimensional array
        $users_by_type = [
            'administrator' => [],
            'teacher' => [],
            'student' => [],
            'parent' => [],
        ];

        $users = [
            [
                'email' => 'administrator@gmail.com',
                'name' => 'Administrator',
                'phone' => '085802733781',
                'status' => '1',
                'type' => 'administrator',
            ],
            [
                'email' => 'teacher1@gmail.com',
                'name' => 'Teacher 1',
                'phone' => '000',
                'status' => '1',
                'type' => 'teacher',
            ],
            [
                'email' => 'teacher2@gmail.com',
                'name' => 'Teacher 2',
                'phone' => '0856',
                'status' => '1',
                'type' => 'teacher',
            ],
            [
                'email' => 'parent@gmail.com',
                'name' => 'parent',
                'phone' => '111',
                'status' => '1',
                'type' => 'parent',
            ],
        ];

        foreach ($users as $user_data) {
            $type = $user_data['type']; 
            unset($user_data['type']); 
            $user = User::create(array_merge($user_data, $default_user_value));
            
            // Tambahkan user ke array sesuai type
            $users_by_type[$type][] = $user;
        }

        $students = [
            ['email' => 'student1@gmail.com', 'name' => 'Student 1', 'phone' => '222', 'status' => '1',],
            ['email' => 'student2@gmail.com', 'name' => 'Student 2', 'phone' => '333', 'status' => '1',],
            ['email' => 'student3@gmail.com', 'name' => 'Student 3', 'phone' => '444', 'status' => '1',],
        ];
        
        foreach ($students as $student_data) {
            $user = User::create(array_merge($student_data, $default_user_value));
            // Tambahkan semua student ke array student
            $users_by_type['student'][] = $user;
        }

        $role_administrator = Role::create(['name' => 'administrator']);
        $role_teacher = Role::create(['name' => 'teacher']);
        $role_parent = Role::create(['name' => 'parent']);
        $role_student = Role::create(['name' => 'student']);

        // Assign roles ke semua user berdasarkan type
        
        foreach ($users_by_type['administrator'] as $user) {
            $user->assignRole($role_administrator);
        }
        
        foreach ($users_by_type['teacher'] as $user) {
            $user->assignRole($role_teacher);
        }
        
        foreach ($users_by_type['parent'] as $user) {
            $user->assignRole($role_parent);
        }
        
        foreach ($users_by_type['student'] as $user) {
            $user->assignRole($role_student);
        }
    }
}
