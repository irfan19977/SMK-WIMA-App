<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar permissions yang akan dibuat
        $permissions = [
            'announcements.index', 'announcements.create', 'announcements.edit',
            'students.index', 'students.create', 'students.edit',
            'teachers.index', 'teachers.create', 'teachers.edit',
            'parents.index', 'parents.create', 'parents.edit',
            'classes.index', 'classes.create', 'classes.edit',
            'subjects.index', 'subjects.create', 'subjects.edit',
            'schedules.index', 'schedules.create', 'schedules.edit',
            'leasson.index', 'leasson.create', 'leasson.edit',
            'attendances.index', 'attendances.create', 'attendances.edit',
            'questions.index', 'questions.create', 'questions.edit',
            'exams.index', 'exams.create', 'exams.edit',
            'reports.index', 'reports.create', 'reports.edit',
            'settings.index', 'settings.create', 'settings.edit',
            'roles.index', 'roles.create', 'roles.edit',
            'permissions.index', 'permissions.create', 'permissions.edit',
            'lesson_attendances.index', 'lesson_attendances.create', 'lesson_attendances.edit',
        ];

        // Buat semua permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Definisi permissions untuk setiap role
        $rolePermissions = [
            'administrator' => [
                // Administrator memiliki semua akses
                'announcements.index', 'announcements.create', 'announcements.edit',
                'students.index', 'students.create', 'students.edit',
                'teachers.index', 'teachers.create', 'teachers.edit',
                'parents.index', 'parents.create', 'parents.edit',
                'classes.index', 'classes.create', 'classes.edit',
                'subjects.index', 'subjects.create', 'subjects.edit',
                'schedules.index', 'schedules.create', 'schedules.edit',
                'leasson.index', 'leasson.create', 'leasson.edit',
                'attendances.index', 'attendances.create', 'attendances.edit',
                'questions.index', 'questions.create', 'questions.edit',
                'exams.index', 'exams.create', 'exams.edit',
                'reports.index', 'reports.create', 'reports.edit',
                'settings.index', 'settings.create', 'settings.edit',
                'roles.index', 'roles.create', 'roles.edit',
                'permissions.index', 'permissions.create', 'permissions.edit',
                'lesson_attendances.index', 'lesson_attendances.create', 'lesson_attendances.edit',
            ],
            
            'teacher' => [
                // Teacher dapat melihat dan mengelola data siswa, absensi, dan pengumuman
                'announcements.index', 'announcements.create', 'announcements.edit',
                'students.index', 'students.edit', // tidak bisa create siswa baru
                'schedules.index',
                'attendances.index', 'attendances.create', 'attendances.edit',
                'parents.index', // hanya bisa melihat data orang tua
            ],
            
            'student' => [
                // Student hanya bisa melihat data tertentu
                'announcements.index',
                'schedules.index',
                'attendances.index', // hanya melihat absensi sendiri
            ],
            
            'parent' => [
                // Parent dapat melihat data anak dan pengumuman
                'announcements.index',
                'students.index', // hanya melihat data anak sendiri
                'schedules.index',
                'attendances.index', // hanya melihat absensi anak sendiri
            ]
        ];

        // Assign permissions to roles
        foreach ($rolePermissions as $roleName => $rolePermissionsList) {
            // Buat atau ambil role
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            // Sync permissions ke role
            $role->syncPermissions($rolePermissionsList);
            
        }

    }
}
