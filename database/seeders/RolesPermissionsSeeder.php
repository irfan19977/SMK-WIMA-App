<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view', 'dashboard.index',
            'users.create', 'users.view', 'users.edit', 'users.delete', 'users.index',
            'students.create', 'students.view', 'students.edit', 'students.delete', 'students.index',
            'teachers.create', 'teachers.view', 'teachers.edit', 'teachers.delete', 'teachers.index',
            'parents.create', 'parents.view', 'parents.edit', 'parents.delete', 'parents.index',
            'classes.create', 'classes.view', 'classes.edit', 'classes.delete', 'classes.index', 'classes.show',
            'subjects.create', 'subjects.view', 'subjects.edit', 'subjects.delete', 'subjects.index',
            'schedules.create', 'schedules.view', 'schedules.edit', 'schedules.delete', 'schedules.index',
            'attendances.create', 'attendances.view', 'attendances.edit', 'attendances.delete', 'attendances.index',
            'lesson_attendances.create', 'lesson_attendances.view', 'lesson_attendances.edit', 'lesson_attendances.delete', 'lesson_attendances.index',
            'announcements.create', 'announcements.view', 'announcements.edit', 'announcements.delete', 'announcements.index',
            'news.create', 'news.view', 'news.edit', 'news.delete', 'news.index',
            'pendaftaran-siswa.create', 'pendaftaran-siswa.view', 'pendaftaran-siswa.edit', 'pendaftaran-siswa.delete', 'pendaftaran-siswa.index',
            'ekstrakurikuler.create', 'ekstrakurikuler.view', 'ekstrakurikuler.edit', 'ekstrakurikuler.delete', 'ekstrakurikuler.index',
            'face_recognition.create', 'face_recognition.view', 'face_recognition.edit', 'face_recognition.delete', 'face_recognition.index',
            'exams.create', 'exams.view', 'exams.edit', 'exams.delete', 'exams.index',
            'questions.create', 'questions.view', 'questions.edit', 'questions.delete', 'questions.index',
            'reports.create', 'reports.view', 'reports.edit', 'reports.delete', 'reports.index',
            'roles.create', 'roles.view', 'roles.edit', 'roles.delete', 'roles.index',
            'permissions.create', 'permissions.view', 'permissions.edit', 'permissions.delete', 'permissions.index',
            'settings.create', 'settings.view', 'settings.edit', 'settings.delete', 'settings.index',
            'setting-schedule.create', 'setting-schedule.view', 'setting-schedule.edit', 'setting-schedule.delete', 'setting-schedule.index',
            'student-grades.create', 'student-grades.view', 'student-grades.edit', 'student-grades.delete', 'student-grades.index',
            'student-permissions.create', 'student-permissions.view', 'student-permissions.edit', 'student-permissions.delete', 'student-permissions.index',
            'exams.student', 'classes.student',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // === Super Admin ===
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // === Admin ===
        // Akses: attendances, classes, lesson_attendances, parents, permissions, roles,
        //        schedules, setting-schedule, student-grades, subjects, students, users, teachers
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions([
            'dashboard.view', 'dashboard.index',
            'attendances.create', 'attendances.view', 'attendances.edit', 'attendances.delete', 'attendances.index',
            'classes.create', 'classes.view', 'classes.edit', 'classes.delete', 'classes.index', 'classes.show',
            'lesson_attendances.create', 'lesson_attendances.view', 'lesson_attendances.edit', 'lesson_attendances.delete', 'lesson_attendances.index',
            'parents.create', 'parents.view', 'parents.edit', 'parents.delete', 'parents.index',
            'permissions.create', 'permissions.view', 'permissions.edit', 'permissions.delete', 'permissions.index',
            'roles.create', 'roles.view', 'roles.edit', 'roles.delete', 'roles.index',
            'schedules.create', 'schedules.view', 'schedules.edit', 'schedules.delete', 'schedules.index',
            'setting-schedule.create', 'setting-schedule.view', 'setting-schedule.edit', 'setting-schedule.delete', 'setting-schedule.index',
            'student-grades.create', 'student-grades.view', 'student-grades.edit', 'student-grades.delete', 'student-grades.index',
            'subjects.create', 'subjects.view', 'subjects.edit', 'subjects.delete', 'subjects.index',
            'students.create', 'students.view', 'students.edit', 'students.delete', 'students.index',
            'users.create', 'users.view', 'users.edit', 'users.delete', 'users.index',
            'teachers.create', 'teachers.view', 'teachers.edit', 'teachers.delete', 'teachers.index',
        ]);

        // === Teacher ===
        // Akses sama dengan Admin kecuali: permissions, roles, teachers, users, students, parents
        $teacherRole = Role::firstOrCreate(['name' => 'Teacher']);
        $teacherRole->syncPermissions([
            'dashboard.view', 'dashboard.index',
            'attendances.create', 'attendances.view', 'attendances.edit', 'attendances.delete', 'attendances.index',
            'classes.view', 'classes.index', 'classes.show',
            'lesson_attendances.create', 'lesson_attendances.view', 'lesson_attendances.edit', 'lesson_attendances.delete', 'lesson_attendances.index',
            'schedules.view', 'schedules.index',
            'setting-schedule.view', 'setting-schedule.index',
            'student-grades.create', 'student-grades.view', 'student-grades.edit', 'student-grades.delete', 'student-grades.index',
            'subjects.view', 'subjects.index',
            'exams.create', 'exams.view', 'exams.edit', 'exams.delete', 'exams.index',
            'questions.create', 'questions.view', 'questions.edit', 'questions.delete', 'questions.index',
        ]);

        // === Student ===
        $studentRole = Role::firstOrCreate(['name' => 'Student']);
        $studentRole->syncPermissions([
            'dashboard.view', 'dashboard.index',
            'attendances.index', 'attendances.view',
            'classes.index', 'classes.show', 'classes.view', 'classes.student',
            'schedules.index', 'schedules.view',
            'lesson_attendances.index', 'lesson_attendances.view',
            'student-permissions.create', 'student-permissions.view', 'student-permissions.edit', 'student-permissions.delete', 'student-permissions.index',
            'exams.student', 'exams.index', 'exams.view',
        ]);

        // === Parent ===
        $parentRole = Role::firstOrCreate(['name' => 'Parent']);
        $parentRole->syncPermissions([
            'dashboard.view', 'dashboard.index',
            'attendances.index', 'attendances.view',
            'classes.index', 'classes.show', 'classes.view',
            'schedules.index', 'schedules.view',
            'lesson_attendances.index', 'lesson_attendances.view',
            'student-permissions.create', 'student-permissions.view', 'student-permissions.edit', 'student-permissions.delete', 'student-permissions.index',
        ]);

        $this->command->info('Roles & Permissions seeded.');
    }
}
