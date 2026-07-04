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

        // Create classes
        $classes = $this->createClasses();

        // Create students
        $students = $this->createStudents($classes);

        // Create attendance
        $this->createAttendance($classes);

        // Create subjects
        $subjects = $this->createSubjects();

        // Create schedules
        $this->createSchedules($classes, $subjects, $teachers);

        // Create lesson attendance
        $this->createLessonAttendance($classes);

        // Create parents
        $this->createParents($students);

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
            'parent',
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
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $teacherRole = Role::create(['name' => 'Teacher']);
        $studentRole = Role::create(['name' => 'Student']);
        $parentRole = Role::create(['name' => 'Parent']);

        // Assign permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo([
            'dashboard.view',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'students.view', 'students.create', 'students.edit', 'students.delete',
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.delete',
            'parents.view', 'parents.create', 'parents.edit', 'parents.delete',
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
            'exams.view', 'exams.create', 'exams.edit',
            'questions.view', 'questions.create', 'questions.edit',
            'student-grades.view', 'student-grades.create', 'student-grades.edit',
        ]);
        $studentRole->givePermissionTo([
            'dashboard.view',
            'attendances.view',
            'exams.view',
            'schedules.view',
            'student-grades.view',
        ]);
        $parentRole->givePermissionTo([
            'dashboard.view',
            'students.view',
            'attendances.view',
            'exams.view',
            'student-grades.view',
            'schedules.view',
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
            'status' => true,
            'join_date' => now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminId = DB::table('administrator')->insertGetId([
            'id' => Str::uuid(),
            'name' => 'Super Admin',
            'phone' => '08123456789',
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
                'status' => true,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('teacher')->insert([
                'id' => $teacherUuid,
                'user_id' => $userUuid,
                'name' => $data['name'],
                'phone' => '0812345678' . rand(0, 9),
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

    
    private function createClasses(): array
    {
        $classes = [];
        $majors = [
            'Teknik Kimia Industri',
            'Teknik Komputer dan Jaringan',
            'Teknik Kendaraan Ringan',
            'Teknik Sepeda Motor'
        ];
        $grades = ['10', '11', '12'];
        $gradePrefixes = ['X', 'XI', 'XII'];

        foreach ($grades as $gradeIndex => $grade) {
            $prefix = $gradePrefixes[$gradeIndex];
            foreach ($majors as $majorIndex => $major) {
                $classUuid = Str::uuid();
                $code = $prefix . strtoupper(substr(str_replace(' ', '', $major), 0, 3)) . ($majorIndex + 1);

                DB::table('classes')->insert([
                    'id' => $classUuid,
                    'name' => $prefix . ' ' . $major . ' 2025/2026',
                    'code' => $code,
                    'grade' => $grade,
                    'major' => $major,
                    'academic_year' => '2025/2026',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $classes[] = [
                    'id' => $classUuid,
                    'name' => $prefix . ' ' . $major . ' 2025/2026',
                    'code' => $code,
                    'grade' => $grade,
                    'major' => $major,
                ];
            }
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

    
    private function createStudents(array $classes): array
    {
        $majors = [
            'Teknik Kimia Industri',
            'Teknik Komputer dan Jaringan',
            'Teknik Kendaraan Ringan',
            'Teknik Sepeda Motor'
        ];

        // Student distribution per grade and major
        $distribution = [
            '10' => [13, 12, 13, 12], // Total 50
            '11' => [12, 13, 12, 12], // Total 49
            '12' => [4, 4, 4, 3],     // Total 15
        ];

        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hana', 'Indra', 'Joko', 'Kartika', 'Lina', 'Made', 'Nina', 'Oscar', 'Putri', 'Rizky', 'Siti', 'Tono', 'Utami', 'Vina', 'Wahyu', 'Xena', 'Yani', 'Zainal'];
        $lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Hidayat', 'Saputra', 'Putra', 'Putri', 'Nugraha', 'Permana', 'Ramadhan', 'Suryadi', 'Wibowo', 'Yuliana', 'Zulkifli', 'Anggraini', 'Budiman', 'Cahyono', 'Darmawan', 'Efendi', 'Firmansyah', 'Gunawan', 'Hartono', 'Irawan', 'Junaedi'];

        // RFID cards to assign
        $rfidCards = [
            'B3:7B:49:FE',
            '73:CB:D9:07',
            '43:40:CE:11',
            '93:34:A8:07',
            'C0:AD:04:58',
            '63:0F:A0:F7'
        ];

        // Shuffle RFID cards for random assignment
        shuffle($rfidCards);

        // Track students by grade for RFID assignment
        $studentsByGrade = [
            '10' => [],
            '11' => [],
            '12' => []
        ];

        // Reorganize classes by grade and major for easy lookup
        $classesByGradeAndMajor = [];
        foreach ($classes as $class) {
            $classesByGradeAndMajor[$class['grade']][$class['major']] = $class;
        }

        $studentCounter = 1;

        // === Siswa Khusus: Putri Anggraini - X Teknik Kimia Industri ===
        $putriUserUuid = Str::uuid();
        $putriStudentUuid = Str::uuid();
        $putriClass = $classesByGradeAndMajor['10']['Teknik Kimia Industri'] ?? null;

        if ($putriClass) {
            DB::table('users')->insert([
                'id' => $putriUserUuid,
                'name' => 'Putri Anggraini',
                'email' => 'putri.anggraini@smkwima.sch.id',
                'password' => Hash::make('password'),
                'status' => true,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('student')->insert([
                'id' => $putriStudentUuid,
                'user_id' => $putriUserUuid,
                'name' => 'Putri Anggraini',
                'phone' => '081200000001',
                'nisn' => '20250001',
                'nik' => '317' . str_pad(rand(100000000000000, 999999999999999), 15, '0', STR_PAD_LEFT),
                'gender' => 'perempuan',
                'birth_place' => 'Surabaya',
                'birth_date' => now()->subYears(16)->format('Y-m-d'),
                'address' => 'Jl. Merdeka No. 1',
                'parent_name' => 'Ibu Anggraini',
                'parent_phone' => '082233088346',
                'no_card' => '73:CB:D9:07',
                'status' => 'siswa',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('student_class')->insert([
                'id' => Str::uuid(),
                'student_id' => $putriStudentUuid,
                'class_id' => $putriClass['id'],
                'academic_year' => '2025/2026',
                'semester' => (date('n') >= 7) ? 'ganjil' : 'genap',
                'start_date' => now()->format('Y-m-d'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userModel = \App\Models\User::find($putriUserUuid);
            $userModel->assignRole('Student');

            $studentsByGrade['10'][] = [
                'student_uuid' => $putriStudentUuid,
                'name' => 'Putri Anggraini',
                'nisn' => '20250001',
            ];

            $this->command->info('Created specific student: Putri Anggraini - X Teknik Kimia Industri (RFID: 73:CB:D9:07)');
            $studentCounter++;
        }
        // === End Siswa Khusus ===

        foreach ($distribution as $grade => $counts) {
            foreach ($majors as $majorIndex => $major) {
                $count = $counts[$majorIndex];

                // Kurangi 1 untuk grade 10 Teknik Kimia Industri karena Putri sudah dibuat
                if ($grade === '10' && $major === 'Teknik Kimia Industri' && $putriClass) {
                    $count = $count - 1;
                }

                // Get the class for this grade and major
                $class = $classesByGradeAndMajor[$grade][$major] ?? null;
                
                if (!$class) {
                    $this->command->error("Class not found for grade {$grade} and major {$major}");
                    continue;
                }

                for ($i = 1; $i <= $count; $i++) {
                    $userUuid = Str::uuid();
                    $studentUuid = Str::uuid();
                    $firstName = $firstNames[array_rand($firstNames)];
                    $lastName = $lastNames[array_rand($lastNames)];
                    $fullName = $firstName . ' ' . $lastName;
                    $nis = '2025' . str_pad($studentCounter, 4, '0', STR_PAD_LEFT);
                    $email = strtolower(str_replace(' ', '.', $fullName)) . '.' . $studentCounter . '@smkwima.sch.id';

                    // Create user
                    $studentPhone = '081234567' . str_pad($studentCounter % 1000, 3, '0', STR_PAD_LEFT);

                    DB::table('users')->insert([
                        'id' => $userUuid,
                        'name' => $fullName,
                        'email' => $email,
                        'password' => Hash::make('password'),
                        'status' => true,
                        'join_date' => now()->format('Y-m-d'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Create student
                    DB::table('student')->insert([
                        'id' => $studentUuid,
                        'user_id' => $userUuid,
                        'name' => $fullName,
                        'phone' => $studentPhone,
                        'nisn' => $nis,
                        'nik' => '317' . str_pad(rand(100000000000000, 999999999999999), 15, '0', STR_PAD_LEFT),
                        'gender' => rand(0, 1) === 1 ? 'laki-laki' : 'perempuan',
                        'birth_place' => 'Jakarta',
                        'birth_date' => now()->subYears(rand(15, 18))->format('Y-m-d'),
                        'address' => 'Jl. Siswa No. ' . $studentCounter,
                        'status' => 'siswa',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Assign student to class
                    DB::table('student_class')->insert([
                        'id' => Str::uuid(),
                        'student_id' => $studentUuid,
                        'class_id' => $class['id'],
                        'academic_year' => '2025/2026',
                        'semester' => (date('n') >= 7) ? 'ganjil' : 'genap',
                        'start_date' => now()->format('Y-m-d'),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Assign Student role using Spatie
                    $userModel = \App\Models\User::find($userUuid);
                    $userModel->assignRole('Student');

                    // Store student info for RFID assignment
                    $studentsByGrade[$grade][] = [
                        'student_uuid' => $studentUuid,
                        'name' => $fullName,
                        'nisn' => $nis,
                    ];

                    $studentCounter++;
                }
            }
        }

        // Assign RFID cards ensuring each grade gets at least 1
        // Remove Putri's card since it's already assigned
        $rfidCards = array_values(array_filter($rfidCards, fn($card) => $card !== '73:CB:D9:07'));
        $rfidIndex = 0;
        $rfidDistribution = [
            '10' => 2, // 2 cards for grade 10
            '11' => 2, // 2 cards for grade 11
            '12' => min(1, count($rfidCards) - 4), // remaining cards for grade 12
        ];

        foreach ($rfidDistribution as $grade => $cardCount) {
            if ($rfidIndex >= count($rfidCards)) break;

            $gradeStudents = $studentsByGrade[$grade];
            if (empty($gradeStudents)) continue;

            // Randomly select students from this grade
            $selectedStudentIndices = array_rand($gradeStudents, min($cardCount, count($gradeStudents)));

            // Handle single selection (array_rand returns int, not array)
            if (!is_array($selectedStudentIndices)) {
                $selectedStudentIndices = [$selectedStudentIndices];
            }

            foreach ($selectedStudentIndices as $studentIndex) {
                if ($rfidIndex >= count($rfidCards)) break;

                $student = $gradeStudents[$studentIndex];
                $rfidCard = $rfidCards[$rfidIndex];

                // Update student with RFID card
                DB::table('student')
                    ->where('id', $student['student_uuid'])
                    ->update([
                        'no_card' => $rfidCard,
                        'updated_at' => now(),
                    ]);

                $this->command->info("Assigned RFID {$rfidCard} to student {$student['name']} (NISN: {$student['nisn']}) - Grade {$grade}");
                $rfidIndex++;
            }
        }

        $this->command->info('Created ' . ($studentCounter - 1) . ' students across all grades and majors.');

        return $studentsByGrade;
    }

    private function createParents(array $studentsByGrade): void
    {
        // Get 2 students from different grades to link parents to
        $selectedStudents = [];

        // Get 1 student from grade 10
        if (!empty($studentsByGrade['10'])) {
            $selectedStudents[] = $studentsByGrade['10'][0];
        }

        // Get 1 student from grade 11
        if (!empty($studentsByGrade['11'])) {
            $selectedStudents[] = $studentsByGrade['11'][0];
        }

        // If we don't have enough students, use what we have
        if (count($selectedStudents) < 2 && !empty($studentsByGrade['12'])) {
            $selectedStudents[] = $studentsByGrade['12'][0];
        }

        // === Parent khusus: Orang tua Putri Anggraini ===
        $putriStudent = null;
        foreach ($studentsByGrade['10'] as $s) {
            if ($s['name'] === 'Putri Anggraini') {
                $putriStudent = $s;
                break;
            }
        }

        if ($putriStudent) {
            $putriParentUserUuid = Str::uuid();
            $putriParentUuid = Str::uuid();

            DB::table('users')->insert([
                'id' => $putriParentUserUuid,
                'name' => 'Ibu Anggraini',
                'email' => 'parent.putri@parent.com',
                'password' => Hash::make('password'),
                'status' => true,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('parent')->insert([
                'id' => $putriParentUuid,
                'user_id' => $putriParentUserUuid,
                'student_id' => $putriStudent['student_uuid'],
                'name' => 'Ibu Anggraini',
                'phone' => '082233088346',
                'jenis_kelamin' => 'perempuan',
                'status' => 'ibu',
                'province' => 'Jawa Timur',
                'regency' => 'Surabaya',
                'district' => 'Gubeng',
                'village' => 'Gubeng',
                'address' => 'Jl. Merdeka No. 1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userModel = \App\Models\User::find($putriParentUserUuid);
            $userModel->assignRole('Parent');

            $this->command->info('Created parent for Putri Anggraini: Ibu Anggraini (082233088346)');
        }
        // === End Parent khusus ===

        $parentData = [
            [
                'name' => 'Hendra Wijaya',
                'email' => 'parent1@parent.com',
                'phone' => '081234567890',
                'gender' => 'laki-laki',
                'status' => 'ayah',
            ],
            [
                'name' => 'Sri Mulyani',
                'email' => 'parent2@parent.com',
                'phone' => '081234567891',
                'gender' => 'perempuan',
                'status' => 'ibu',
            ],
        ];

        foreach ($parentData as $index => $data) {
            if (!isset($selectedStudents[$index])) {
                $this->command->warn("Not enough students to create parent " . ($index + 1));
                continue;
            }

            $student = $selectedStudents[$index];
            $userUuid = Str::uuid();
            $parentUuid = Str::uuid();

            // Create user
            DB::table('users')->insert([
                'id' => $userUuid,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'status' => true,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create parent
            DB::table('parent')->insert([
                'id' => $parentUuid,
                'user_id' => $userUuid,
                'student_id' => $student['student_uuid'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'jenis_kelamin' => $data['gender'],
                'status' => $data['status'],
                'province' => 'DKI Jakarta',
                'regency' => 'Jakarta Pusat',
                'district' => 'Menteng',
                'village' => 'Menteng',
                'address' => 'Jl. Orang Tua No. ' . ($index + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign Parent role
            $userModel = \App\Models\User::find($userUuid);
            $userModel->assignRole('Parent');

            $this->command->info("Created parent {$data['name']} ({$data['status']}) linked to student {$student['name']} (NISN: {$student['nisn']})");
        }

        $this->command->info('Created ' . count($parentData) . ' parent accounts.');
    }

    private function createAttendance(array $classes): void
    {
        // Get all students with their class information
        $students = DB::table('student')
            ->join('student_class', 'student.id', '=', 'student_class.student_id')
            ->select('student.id as student_id', 'student_class.class_id')
            ->get();

        // Generate working days for April 2026 and May 1-15, 2026
        $year = 2026;
        $workingDays = [];
        
        // April 2026 - all working days
        for ($day = 1; $day <= 30; $day++) {
            $date = \Carbon\Carbon::create($year, 4, $day);
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                $workingDays[] = $date->format('Y-m-d');
            }
        }
        
        // May 2026 - days 1-15 only
        for ($day = 1; $day <= 15; $day++) {
            $date = \Carbon\Carbon::create($year, 5, $day);
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                $workingDays[] = $date->format('Y-m-d');
            }
        }

        $attendanceCount = 0;

        foreach ($students as $student) {
            foreach ($workingDays as $date) {
                // Randomly decide if student is present (85% attendance rate)
                if (rand(1, 100) > 85) {
                    // Absent (izin, sakit, or alpha)
                    $status = rand(0, 1) === 0 ? 'izin' : (rand(0, 1) === 0 ? 'sakit' : 'alpha');
                    
                    DB::table('attendance')->insert([
                        'id' => Str::uuid(),
                        'student_id' => $student->student_id,
                        'class_id' => $student->class_id,
                        'date' => $date,
                        'check_in' => null,
                        'check_out' => null,
                        'check_in_status' => $status,
                        'check_out_status' => $status,
                        'academic_year' => '2025/2026',
                        'semester' => 'ganjil',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    // Present - generate realistic times
                    $checkInHour = rand(6, 7); // 6:00 - 7:59
                    $checkInMinute = rand(0, 59);
                    $checkInStatus = $checkInHour >= 7 ? 'terlambat' : 'tepat';
                    
                    $checkOutHour = rand(14, 15); // 14:00 - 15:59
                    $checkOutMinute = rand(0, 59);
                    $checkOutStatus = 'tepat';

                    DB::table('attendance')->insert([
                        'id' => Str::uuid(),
                        'student_id' => $student->student_id,
                        'class_id' => $student->class_id,
                        'date' => $date,
                        'check_in' => sprintf('%02d:%02d:00', $checkInHour, $checkInMinute),
                        'check_out' => sprintf('%02d:%02d:00', $checkInHour + 7, $checkOutMinute),
                        'check_in_status' => $checkInStatus,
                        'check_out_status' => $checkOutStatus,
                        'academic_year' => '2025/2026',
                        'semester' => 'ganjil',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                $attendanceCount++;
            }
        }

        $this->command->info('Created ' . $attendanceCount . ' attendance records for ' . $students->count() . ' students (April + May 1-15, 2026).');
    }

    private function createLessonAttendance(array $classes): void
    {
        // Get all schedules
        $schedules = DB::table('schedule')->get();

        // April 2026 - all days (Monday to Sunday)
        $year = 2026;
        $month = 4; // April
        $workingDays = [];
        
        for ($day = 1; $day <= 30; $day++) {
            $date = \Carbon\Carbon::create($year, $month, $day);
            $workingDays[$date->dayOfWeek] = $date->format('Y-m-d'); // Map day of week to date
        }

        // Map day names to Carbon day of week
        $dayMap = [
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
            'minggu' => 0,
        ];

        $lessonAttendanceCount = 0;

        foreach ($schedules as $schedule) {
            $dayOfWeek = $dayMap[$schedule->day] ?? null;
            
            if ($dayOfWeek && isset($workingDays[$dayOfWeek])) {
                $date = $workingDays[$dayOfWeek];
                
                // Get all students in this class
                $students = DB::table('student_class')
                    ->where('class_id', $schedule->class_id)
                    ->where('academic_year', '2025/2026')
                    ->pluck('student_id');

                foreach ($students as $studentId) {
                    // Randomly decide if student is present (90% attendance rate for lessons)
                    if (rand(1, 100) > 90) {
                        // Absent (izin, sakit, or alpha)
                        $status = rand(0, 1) === 0 ? 'izin' : (rand(0, 1) === 0 ? 'sakit' : 'alpha');
                        
                        DB::table('lesson_attendance')->insert([
                            'id' => Str::uuid(),
                            'student_id' => $studentId,
                            'class_id' => $schedule->class_id,
                            'subject_id' => $schedule->subject_id,
                            'date' => $date,
                            'check_in' => null,
                            'check_in_status' => $status,
                            'academic_year' => '2025/2026',
                            'semester' => 'ganjil',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Present - generate realistic check-in time based on schedule
                        $scheduleStartTime = \Carbon\Carbon::parse($schedule->start_time);
                        $checkInTime = $scheduleStartTime->copy()->addMinutes(rand(-10, 10)); // +/- 10 minutes from start
                        $checkInStatus = $checkInTime->gt($scheduleStartTime) ? 'terlambat' : 'hadir';

                        DB::table('lesson_attendance')->insert([
                            'id' => Str::uuid(),
                            'student_id' => $studentId,
                            'class_id' => $schedule->class_id,
                            'subject_id' => $schedule->subject_id,
                            'date' => $date,
                            'check_in' => $checkInTime->format('H:i:s'),
                            'check_in_status' => $checkInStatus,
                            'academic_year' => '2025/2026',
                            'semester' => 'ganjil',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    $lessonAttendanceCount++;
                }
            }
        }

        $this->command->info('Created ' . $lessonAttendanceCount . ' lesson attendance records for April 2026.');
    }

    private function createSchedules(array $classes, array $subjects, array $teachers): void
    {
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        $times = [
            ['start' => '07:00:00', 'end' => '08:30:00'],
            ['start' => '08:30:00', 'end' => '10:00:00'],
            ['start' => '10:15:00', 'end' => '11:45:00'],
            ['start' => '12:30:00', 'end' => '14:00:00'],
            ['start' => '14:00:00', 'end' => '15:30:00'],
        ];

        // Group subjects by major for more relevant assignments
        $subjectMap = [
            'Teknik Kimia Industri' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Kimia Industri', 'Teknik Mesin'],
            'Teknik Komputer dan Jaringan' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Teknik Komputer Jaringan', 'Produktif TKJ'],
            'Teknik Kendaraan Ringan' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Teknik Mesin'],
            'Teknik Sepeda Motor' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Teknik Mesin'],
        ];

        // Common subjects for all majors
        $commonSubjects = ['Pendidikan Agama Islam', 'Pendidikan Kewarganegaraan', 'Penjaskes', 'Seni Budaya', 'Sejarah'];

        foreach ($classes as $class) {
            $major = $class['major'];
            $grade = $class['grade'];
            
            // Get relevant subjects for this major
            $relevantSubjects = collect($subjects)->filter(function ($subject) use ($subjectMap, $major, $commonSubjects) {
                $majorSubjects = $subjectMap[$major] ?? [];
                return in_array($subject['name'], $majorSubjects) || in_array($subject['name'], $commonSubjects);
            })->values()->toArray();

            // If no relevant subjects found, use all subjects
            if (empty($relevantSubjects)) {
                $relevantSubjects = $subjects;
            }

            $subjectIndex = 0;

            foreach ($days as $day) {
                foreach ($times as $timeIndex => $time) {
                    // Skip Friday afternoon for Jumat Khusus/Keagamaan
                    if ($day === 'jumat' && $timeIndex >= 3) continue;

                    // Skip some slots for breaks (istirahat)
                    if ($timeIndex === 2) continue; // Break after 2nd period

                    // Cycle through subjects
                    $subject = $relevantSubjects[$subjectIndex % count($relevantSubjects)];
                    $teacher = $teachers[array_rand($teachers)];

                    DB::table('schedule')->insert([
                        'id' => Str::uuid(),
                        'class_id' => $class['id'],
                        'subject_id' => $subject['id'],
                        'teacher_id' => $teacher['teacher_id'],
                        'day' => $day,
                        'start_time' => $time['start'],
                        'end_time' => $time['end'],
                        'semester' => (date('n') >= 7) ? 'ganjil' : 'genap',
                        'academic_year' => '2025/2026',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $subjectIndex++;
                }
            }
        }

        $this->command->info('Created schedules for ' . count($classes) . ' classes.');
    }
}
