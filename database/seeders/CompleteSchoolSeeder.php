<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CompleteSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        Schema::disableForeignKeyConstraints();

        // Clear existing data
        $this->clearTables();

        // Create roles and permissions
        $this->createRolesAndPermissions();

        // Create users with roles
        $adminUser = $this->createAdministrator();
        $teachers = $this->createTeachers();
        $students = $this->createStudents();

        // Create classes
        $classes = $this->createClasses();

        // Create subjects
        $subjects = $this->createSubjects();

        // Assign students to classes
        $this->assignStudentsToClasses($students, $classes);

        // Create schedules
        $this->createSchedules($classes, $subjects, $teachers);

        // Create attendances
        $this->createAttendances($students, $classes);

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $this->command->info('Complete school data seeded successfully!');
    }

    private function clearTables(): void
    {
        $tables = [
            'attendance',
            'schedule',
            'student_class',
            'subject',
            'classes',
            'student',
            'teacher',
            'administrator',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'permissions',
            'roles',
            'users'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }

    private function createRolesAndPermissions(): void
    {
        // Create permissions
        $permissions = [
            'dashboard.view', 'dashboard.index',
            'users.create', 'users.view', 'users.edit', 'users.delete',
            'students.create', 'students.view', 'students.edit', 'students.delete', 'students.index',
            'teachers.create', 'teachers.view', 'teachers.edit', 'teachers.delete', 'teachers.index',
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
            'parents.create', 'parents.view', 'parents.edit', 'parents.delete', 'parents.index',
            'student-grades.create', 'student-grades.view', 'student-grades.edit', 'student-grades.delete', 'student-grades.index',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $teacherRole = Role::create(['name' => 'Teacher']);
        $studentRole = Role::create(['name' => 'Student']);

        // Assign permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo([
            'dashboard.view',
            'students.view', 'students.create', 'students.edit', 'students.delete',
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.delete',
            'classes.view', 'classes.create', 'classes.edit', 'classes.delete',
            'subjects.view', 'subjects.create', 'subjects.edit', 'subjects.delete',
            'schedules.view', 'schedules.create', 'schedules.edit', 'schedules.delete',
            'attendances.view', 'attendances.create', 'attendances.edit', 'attendances.delete',
        ]);
        $teacherRole->givePermissionTo([
            'dashboard.view',
            'students.view',
            'classes.view',
            'subjects.view',
            'schedules.view',
            'attendances.view', 'attendances.create', 'attendances.edit',
        ]);
        $studentRole->givePermissionTo([
            'dashboard.view',
            'attendances.view',
        ]);
    }

    private function createAdministrator(): array
    {
        $userUuid = Str::uuid();
        
        DB::table('users')->insert([
            'id' => $userUuid,
            'name' => 'Super Admin',
            'email' => 'admin@smkwima.sch.id',
            'password' => Hash::make('password'),
            'phone' => '08123456789',
            'status' => true,
            'join_date' => now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminId = DB::table('administrator')->insertGetId([
            'id' => Str::uuid(),
            'name' => 'Super Admin',
            'user_id' => $userUuid,
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'province' => 'DKI Jakarta',
            'regency' => 'Jakarta Pusat',
            'district' => 'Menteng',
            'village' => 'Menteng',
            'address' => 'Jl. Menteng Raya No. 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign Super Admin role
        $userModel = \App\Models\User::find($userUuid);
        $userModel->assignRole('Super Admin');

        return ['user_id' => $userUuid, 'admin_id' => $adminId];
    }

    private function createTeachers(): array
    {
        $teachers = [];
        $teacherData = [
            [
                'name' => 'Budi Santoso, S.Pd',
                'email' => 'budi.santoso@smkwima.sch.id',
                'nip' => '198001012010011001',
                'gender' => 'laki-laki',
                'education_level' => 'S1',
                'education_major' => 'Pendidikan Matematika',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name' => 'Siti Nurhaliza, S.Pd',
                'email' => 'siti.nurhaliza@smkwima.sch.id',
                'nip' => '198502022015022002',
                'gender' => 'perempuan',
                'education_level' => 'S1',
                'education_major' => 'Pendidikan Bahasa Indonesia',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name' => 'Ahmad Fauzi, S.Kom',
                'email' => 'ahmad.fauzi@smkwima.sch.id',
                'nip' => '198803152018031003',
                'gender' => 'laki-laki',
                'education_level' => 'S1',
                'education_major' => 'Teknik Informatika',
                'education_institution' => 'Universitas Indonesia',
            ],
            [
                'name' => 'Dewi Lestari, S.Pd',
                'email' => 'dewi.lestari@smkwima.sch.id',
                'nip' => '199005202019052004',
                'gender' => 'perempuan',
                'education_level' => 'S1',
                'education_major' => 'Pendidikan Ekonomi',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name' => 'Rudi Hartono, S.T',
                'email' => 'rudi.hartono@smkwima.sch.id',
                'nip' => '198707152014071005',
                'gender' => 'laki-laki',
                'education_level' => 'S1',
                'education_major' => 'Teknik Mesin',
                'education_institution' => 'Universitas Indonesia',
            ],
        ];

        foreach ($teacherData as $data) {
            $userUuid = Str::uuid();
            $teacherUuid = Str::uuid();
            
            DB::table('users')->insert([
                'id' => $userUuid,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'phone' => '0812345678' . rand(0, 9),
                'status' => true,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('teacher')->insert([
                'id' => $teacherUuid,
                'user_id' => $userUuid,
                'name' => $data['name'],
                'nip' => $data['nip'],
                'education_level' => $data['education_level'],
                'education_major' => $data['education_major'],
                'education_institution' => $data['education_institution'],
                'gender' => $data['gender'],
                'province' => 'DKI Jakarta',
                'regency' => 'Jakarta Pusat',
                'district' => 'Menteng',
                'village' => 'Menteng',
                'address' => 'Jl. Guru Indonesia No. ' . rand(1, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign Teacher role
            $userModel = \App\Models\User::find($userUuid);
            $userModel->assignRole('Teacher');

            $teachers[] = [
                'user_id' => $userUuid,
                'teacher_id' => $teacherUuid, // Use correct UUID
                'name' => $data['name'],
            ];
        }

        return $teachers;
    }

    private function createStudents(): array
    {
        $students = [];
        $studentNames = [
            'Ahmad Rizki', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari', 'Rudi Hartono',
            'Maya Sari', 'Joko Widodo', 'Ani Susanti', 'Eko Prasetyo', 'Rina Wijaya',
            'Doni Pratama', 'Lisa Permata', 'Hendra Gunawan', 'Fitri Handayani', 'Andi Wijaya',
            'Sri Wahyuni', 'Bambang Sutrisno', 'Yuni Astuti', 'Dedi Kurniawan', 'Nina Kartika',
        ];

        foreach ($studentNames as $index => $name) {
            $userUuid = Str::uuid();
            $studentUuid = Str::uuid();
            
            DB::table('users')->insert([
                'id' => $userUuid,
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@student.smkwima.sch.id',
                'password' => Hash::make('password'),
                'phone' => '081234567' . sprintf('%02d', $index + 1),
                'status' => true,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('student')->insert([
                'id' => $studentUuid,
                'user_id' => $userUuid,
                'no_absen' => sprintf('%03d', $index + 1),
                'name' => $name,
                'nisn' => '00' . sprintf('%08d', $index + 1),
                'nik' => '320101' . sprintf('%08d', $index + 100000001),
                'gender' => $index % 2 == 0 ? 'laki-laki' : 'perempuan',
                'birth_date' => now()->subYears(rand(15, 18))->format('Y-m-d'),
                'birth_place' => 'Jakarta',
                'religion' => 'Islam',
                'address' => 'Jl. Pelajar No. ' . ($index + 1),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign Student role
            $userModel = \App\Models\User::find($userUuid);
            $userModel->assignRole('Student');

            $students[] = [
                'user_id' => $userUuid,
                'student_id' => $studentUuid, // Use correct UUID
                'name' => $name,
                'nisn' => '00' . sprintf('%08d', $index + 1),
            ];
        }

        return $students;
    }

    private function createClasses(): array
    {
        $classes = [];
        $classData = [
            ['name' => 'X IPA 1', 'code' => 'XIPA1', 'grade' => 'X', 'major' => 'IPA'],
            ['name' => 'X IPA 2', 'code' => 'XIPA2', 'grade' => 'X', 'major' => 'IPA'],
            ['name' => 'X IPS 1', 'code' => 'XIPS1', 'grade' => 'X', 'major' => 'IPS'],
            ['name' => 'X IPS 2', 'code' => 'XIPS2', 'grade' => 'X', 'major' => 'IPS'],
            ['name' => 'XI IPA 1', 'code' => 'XIIPA1', 'grade' => 'XI', 'major' => 'IPA'],
            ['name' => 'XI IPA 2', 'code' => 'XIIPA2', 'grade' => 'XI', 'major' => 'IPA'],
            ['name' => 'XI IPS 1', 'code' => 'XIIPS1', 'grade' => 'XI', 'major' => 'IPS'],
            ['name' => 'XI IPS 2', 'code' => 'XIIPS2', 'grade' => 'XI', 'major' => 'IPS'],
            ['name' => 'XII IPA 1', 'code' => 'XIIIPA1', 'grade' => 'XII', 'major' => 'IPA'],
            ['name' => 'XII IPA 2', 'code' => 'XIIIPA2', 'grade' => 'XII', 'major' => 'IPA'],
            ['name' => 'XII IPS 1', 'code' => 'XIIIPS1', 'grade' => 'XII', 'major' => 'IPS'],
            ['name' => 'XII IPS 2', 'code' => 'XIIIPS2', 'grade' => 'XII', 'major' => 'IPS'],
            ['name' => 'X TKJ 1', 'code' => 'XTKJ1', 'grade' => 'X', 'major' => 'TKJ'],
            ['name' => 'X MM 1', 'code' => 'XMM1', 'grade' => 'X', 'major' => 'MM'],
            ['name' => 'XI RPL 1', 'code' => 'XIRPL1', 'grade' => 'XI', 'major' => 'RPL'],
            ['name' => 'XI RPL 2', 'code' => 'XIRPL2', 'grade' => 'XI', 'major' => 'RPL'],
            ['name' => 'XII AK 1', 'code' => 'XIIAK1', 'grade' => 'XII', 'major' => 'AK'],
        ];

        foreach ($classData as $data) {
            $classUuid = Str::uuid();
            
            DB::table('classes')->insert([
                'id' => $classUuid,
                'name' => $data['name'],
                'code' => $data['code'],
                'grade' => $data['grade'],
                'major' => $data['major'],
                'academic_year' => '2025/2026',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $classes[] = [
                'id' => $classUuid,
                'name' => $data['name'],
                'code' => $data['code'],
                'grade' => $data['grade'],
                'major' => $data['major'],
            ];
        }

        return $classes;
    }

    private function createSubjects(): array
    {
        $subjects = [];
        $subjectData = [
            ['name' => 'Matematika', 'code' => 'MAT'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING'],
            ['name' => 'Fisika', 'code' => 'FIS'],
            ['name' => 'Kimia', 'code' => 'KIM'],
            ['name' => 'Biologi', 'code' => 'BIO'],
            ['name' => 'Sejarah', 'code' => 'SEJ'],
            ['name' => 'Geografi', 'code' => 'GEO'],
            ['name' => 'Ekonomi', 'code' => 'EKO'],
            ['name' => 'Sosiologi', 'code' => 'SOS'],
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI'],
            ['name' => 'Pendidikan Kewarganegaraan', 'code' => 'PKWU'],
            ['name' => 'Penjaskes', 'code' => 'PJOK'],
            ['name' => 'Seni Budaya', 'code' => 'SB'],
            ['name' => 'Teknik Komputer Jaringan', 'code' => 'TKJ'],
            ['name' => 'Multimedia', 'code' => 'MM'],
            ['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL'],
            ['name' => 'Akuntansi', 'code' => 'AK'],
            ['name' => 'Produktif TKJ', 'code' => 'PRODTKJ'],
            ['name' => 'Produktif MM', 'code' => 'PRODMM'],
            ['name' => 'Produktif RPL', 'code' => 'PRODRPL'],
            ['name' => 'Produktif AK', 'code' => 'PRODAK'],
            ['name' => 'Kimia Industri', 'code' => 'KIMIND'],
            ['name' => 'Teknik Mesin', 'code' => 'TMESIN'],
        ];

        foreach ($subjectData as $data) {
            $subjectUuid = Str::uuid();
            
            DB::table('subject')->insert([
                'id' => $subjectUuid,
                'name' => $data['name'],
                'code' => $data['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $subjects[] = [
                'id' => $subjectUuid,
                'name' => $data['name'],
                'code' => $data['code'],
            ];
        }

        return $subjects;
    }

    private function assignStudentsToClasses(array $students, array $classes): void
    {
        $studentChunks = array_chunk($students, 5); // 5 students per class

        foreach ($studentChunks as $index => $chunk) {
            if (isset($classes[$index])) {
                foreach ($chunk as $student) {
                    DB::table('student_class')->insert([
                        'id' => Str::uuid(),
                        'class_id' => $classes[$index]['id'],
                        'student_id' => $student['student_id'],
                        'academic_year' => '2025/2026',
                        'semester' => 'ganjil',
                        'start_date' => now()->format('Y-m-d'),
                        'end_date' => now()->addMonths(6)->format('Y-m-d'),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function createSchedules(array $classes, array $subjects, array $teachers): void
    {
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $times = [
            ['start' => '07:00:00', 'end' => '08:30:00'],
            ['start' => '08:30:00', 'end' => '10:00:00'],
            ['start' => '10:15:00', 'end' => '11:45:00'],
            ['start' => '12:30:00', 'end' => '14:00:00'],
            ['start' => '14:00:00', 'end' => '15:30:00'],
        ];

        foreach ($classes as $classIndex => $class) {
            foreach ($days as $dayIndex => $day) {
                foreach ($times as $timeIndex => $time) {
                    // Skip some slots for variety
                    if (rand(1, 10) > 7) continue;

                    $subject = $subjects[array_rand($subjects)];
                    $teacher = $teachers[array_rand($teachers)];

                    DB::table('schedule')->insert([
                        'id' => Str::uuid(),
                        'class_id' => $class['id'],
                        'subject_id' => $subject['id'],
                        'teacher_id' => $teacher['teacher_id'],
                        'day' => $day,
                        'start_time' => $time['start'],
                        'end_time' => $time['end'],
                        'semester' => 'ganjil',
                        'academic_year' => '2025/2026',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function createAttendances(array $students, array $classes): void
    {
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        foreach ($students as $student) {
            foreach ($dates as $date) {
                // Skip weekends
                if (in_array(date('N', strtotime($date)), [6, 7])) continue;

                $checkInStatus = 'tepat';
                $lateDuration = 0;
                $checkInTime = null;

                // Randomly make some students late
                if (rand(1, 10) <= 3) {
                    $lateDuration = rand(5, 45);
                    $hours = 7;
                    $minutes = $lateDuration;
                    $checkInTime = sprintf('%02d:%02d:00', $hours, $minutes);
                    $checkInStatus = 'terlambat';
                } else {
                    // On time (between 06:45 and 07:00)
                    $hours = 6;
                    $minutes = rand(45, 59);
                    $checkInTime = sprintf('%02d:%02d:00', $hours, $minutes);
                    $checkInStatus = 'tepat';
                }

                // Randomly make some absent
                if (rand(1, 10) <= 1) {
                    $checkInStatus = 'alpha';
                    $checkInTime = null;
                }

                DB::table('attendance')->insert([
                    'id' => Str::uuid(),
                    'student_id' => $student['student_id'], // Use correct UUID from students array
                    'class_id' => $classes[array_rand($classes)]['id'],
                    'date' => $date,
                    'check_in' => $checkInTime,
                    'check_out' => $checkInTime ? date('H:i:s', strtotime($checkInTime) + 8*3600) : null,
                    'check_in_status' => $checkInStatus,
                    'check_out_status' => 'tepat',
                    'academic_year' => '2025/2026',
                    'semester' => 'ganjil',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
