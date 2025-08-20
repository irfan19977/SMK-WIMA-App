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
                'email' => 'superadmin@gmail.com',
                'name' => 'Superadmin',
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
            ['email' => 'andyahmadthariqmaulana@gmail.com', 'name' => 'ANDY AHMAD THARIQ MAULANA', 'phone' => '081234567891', 'status' => '1',],
            ['email' => 'azmifawwasfirdausy@gmail.com', 'name' => 'AZMI FAWWAS FIRDAUSY', 'phone' => '081234567892', 'status' => '1',],
            ['email' => 'faishaldanurwedabismawibowo@gmail.com', 'name' => 'FAISHAL DANURWEDA BISMA WIBOWO', 'phone' => '081234567893', 'status' => '1',],
            ['email' => 'muhammadarshadnaufalmustofa@gmail.com', 'name' => 'MUHAMMAD ARSHAD NAUFAL MUSTOFA', 'phone' => '081234567894', 'status' => '1',],
            ['email' => 'muhammadfuadabdullah@gmail.com', 'name' => 'MUHAMMAD FUAD ABDULLAH', 'phone' => '081234567895', 'status' => '1',],
            ['email' => 'muhammadihsanuddinarsyad@gmail.com', 'name' => 'MUHAMMAD IHSANUDDIN ARSYAD', 'phone' => '081234567896', 'status' => '1',],
            ['email' => 'muhammadradonisbaihaqi@gmail.com', 'name' => 'MUHAMMAD RADONIS BAIHAQI', 'phone' => '081234567897', 'status' => '1',],
            ['email' => 'muhammadsaidalkatiri@gmail.com', 'name' => 'MUHAMMAD SAID ALKATIRI', 'phone' => '081234567898', 'status' => '1',],
            ['email' => 'muhammadshidqialkautsar@gmail.com', 'name' => 'MUHAMMAD SHIDQI AL KAUTSAR', 'phone' => '081234567899', 'status' => '1',],
            ['email' => 'musamubarak@gmail.com', 'name' => 'MUSA MUBARAK', 'phone' => '081234567800', 'status' => '1',],
            ['email' => 'sholih@gmail.com', 'name' => 'SHOLIH', 'phone' => '081234567801', 'status' => '1',],
            ['email' => 'zidnyzaydanhabibieizzilhatif@gmail.com', 'name' => 'ZIDNY ZAYDAN HABIBIE IZZI LHATIF', 'phone' => '081234567802', 'status' => '1',],
            // Data siswa kelas 11
            ['email' => 'abdullohazkaibadurrohman@gmail.com', 'name' => 'ABDULLOH AZKA \'IBADURROHMAN SUFYAN', 'phone' => '081234567803', 'status' => '1',],
            ['email' => 'ardanfaiarrafan@gmail.com', 'name' => 'ARDAN FAI ARRAFAN', 'phone' => '081234567804', 'status' => '1',],
            ['email' => 'arkanthaariiqasadullah@gmail.com', 'name' => 'ARKAN THAARIQ ASADULLAH', 'phone' => '081234567805', 'status' => '1',],
            ['email' => 'athallahassyarif@gmail.com', 'name' => 'ATHALLAH ASSYARIF', 'phone' => '081234567806', 'status' => '1',],
            ['email' => 'chevietoraffiframadhan@gmail.com', 'name' => 'CHEVIETO RAFFIF RAMADHAN', 'phone' => '081234567807', 'status' => '1',],
            ['email' => 'fahriabdurrahman@gmail.com', 'name' => 'FAHRI ABDURRAHMAN', 'phone' => '081234567808', 'status' => '1',],
            ['email' => 'farhanmaulanarizqi@gmail.com', 'name' => 'FARHAN MAULANA RIZQI', 'phone' => '081234567809', 'status' => '1',],
            ['email' => 'irsyaduddin@gmail.com', 'name' => 'IRSYADUDDIN', 'phone' => '081234567810', 'status' => '1',],
            ['email' => 'muhammadazmyashshiddiqie@gmail.com', 'name' => 'MUHAMMAD \'AZMY ASH-SHIDDIQIE', 'phone' => '081234567811', 'status' => '1',],
            ['email' => 'muhammadabidnaufal@gmail.com', 'name' => 'MUHAMMAD ABID NAUFAL', 'phone' => '081234567812', 'status' => '1',],
            ['email' => 'muhammadfadhlirobbiel@gmail.com', 'name' => 'MUHAMMAD FADHLI ROBBI ELHAMI', 'phone' => '081234567813', 'status' => '1',],
            ['email' => 'muhammadmishaal@gmail.com', 'name' => 'MUHAMMAD MISHAAL', 'phone' => '081234567814', 'status' => '1',],
            ['email' => 'naurakhaula@gmail.com', 'name' => 'NAURA KHAULA AL HAQQUL', 'phone' => '081234567815', 'status' => '1',],
            ['email' => 'rakapratama@gmail.com', 'name' => 'RAKA PRATAMA', 'phone' => '081234567816', 'status' => '1',],
            ['email' => 'shahdanalqiarraffi@gmail.com', 'name' => 'SYAHDAN ALQI ARRAFFI', 'phone' => '081234567817', 'status' => '1',],
            ['email' => 'amuhafganalgazali@gmail.com', 'name' => 'A MUH AFGAN AL GAZALI', 'phone' => '081234567818', 'status' => '1',],
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
