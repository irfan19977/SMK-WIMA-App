<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Lesson;
use App\Models\News;
use App\Models\ParentModel;
use App\Models\Schedule;
use App\Models\SettingSchedule;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentGrades;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CompleteAcademicSeeder extends Seeder
{
    private $adminUser;
    private $teachers = [];
    private $students = [];
    private $parents = [];
    private $classes = [];
    private $subjects = [];
    private $schedules = [];

    /**
     * Run the database seeds for Academic Year 2025/2026 Semester Ganjil
     */
    public function run(): void
    {
        $this->command->info('🎓 Starting Complete Academic Seeder for 2025/2026 Semester Ganjil...');
        
        // 1. Create Users (Admin, Teachers, Students, Parents)
        $this->seedUsers();
        
        // 2. Create Administrator
        $this->seedAdministrator();
        
        // 3. Create Teachers
        $this->seedTeachers();
        
        // 4. Create Parents
        $this->seedParents();
        
        // 5. Create Students
        $this->seedStudents();
        
        // 6. Create Subjects
        $this->seedSubjects();
        
        // 7. Create Classes
        $this->seedClasses();
        
        // 8. Assign Students to Classes
        $this->seedStudentClasses();
        
        // 9. Create Schedules
        $this->seedSchedules();
        
        // 10. Create Setting Schedule
        $this->seedSettingSchedule();
        
        // 11. Create Attendances (In/Out)
        $this->seedAttendances();
        
        // 12. Create Lesson Attendances
        $this->seedLessonAttendances();
        
        // 13. Create News
        $this->seedNews();
        
        $this->command->info('✅ Complete Academic Seeder finished successfully!');
    }

    private function seedUsers()
    {
        $this->command->info('👤 Seeding users...');

        // Create roles if not exist
        $roles = ['superadmin', 'teacher', 'student', 'parent'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Admin
        $adminUser = User::create([
            'id' => Str::uuid(),
            'name' => 'Super Administrator',
            'email' => 'superadmin@gmail.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'status' => true,
        ]);
        $adminUser->assignRole('superadmin');
        $this->adminUser = $adminUser;

        // Teachers
        $teacherNames = [
            'Budi Santoso, S.Pd',
            'Siti Nurhaliza, S.Pd',
            'Ahmad Dahlan, S.Kom',
            'Dewi Lestari, S.Si',
            'Hendra Wijaya, S.Pd',
            'Rina Kusuma, S.Pd',
            'Agus Setiawan, S.T',
            'Maya Sari, S.Pd',
            'Bambang Prakoso, S.Pd',
            'Fitri Handayani, S.Pd',
        ];

        foreach ($teacherNames as $index => $name) {
            $email = 'teacher' . ($index + 1) . '@gmail.com';
            $user = User::create([
                'id' => Str::uuid(),
                'name' => $name,
                'email' => $email,
                'phone' => '0812345678' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'status' => true,
            ]);
            $user->assignRole('teacher');
            $this->teachers[] = $user;
        }

        // Parents
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'id' => Str::uuid(),
                'name' => 'Orang Tua ' . $i,
                'email' => 'parent' . $i . '@gmail.com',
                'phone' => '0813' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'status' => true,
            ]);
            $user->assignRole('parent');
            $this->parents[] = $user;
        }

        // Students
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'id' => Str::uuid(),
                'name' => 'Siswa ' . $i,
                'email' => 'student' . $i . '@gmail.com',
                'phone' => '0814' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
                'status' => true,
            ]);
            $user->assignRole('student');
            $this->students[] = $user;
        }

        $this->command->info('✓ Created ' . count($this->teachers) . ' teachers, ' . count($this->students) . ' students, ' . count($this->parents) . ' parents');
    }

    private function seedAdministrator()
    {
        $this->command->info('👨‍💼 Seeding administrator...');

        Administrator::create([
            'id' => Str::uuid(),
            'user_id' => $this->adminUser->id,
            'name' => 'Super Administrator',
            'birth_place' => 'Malang',
            'birth_date' => '1985-01-01',
            'address' => 'Jl. Raya Lawang No. 123, Lawang, Malang',
            'created_by' => $this->adminUser->id,
        ]);
    }

    private function seedTeachers()
    {
        $this->command->info('👨‍🏫 Seeding teachers...');

        $genders = ['laki-laki', 'perempuan'];
        
        foreach ($this->teachers as $index => $teacherUser) {
            $teacher = Teacher::create([
                'id' => Str::uuid(),
                'user_id' => $teacherUser->id,
                'name' => $teacherUser->name,
                'nip' => '19' . (85 + $index) . '0101201001100' . ($index + 1),
                'gender' => $genders[$index % 2],
                'address' => 'Jl. Guru ' . ($index + 1) . ', Lawang, Malang',
                'created_by' => $this->adminUser->id,
            ]);
            $this->teachers[$index]->teacher = $teacher;
        }
    }

    private function seedParents()
    {
        $this->command->info('👨‍👩‍👧 Seeding parents...');

        $statuses = ['ayah', 'ibu', 'wali'];
        $genders = ['laki-laki', 'perempuan'];

        foreach ($this->parents as $index => $parentUser) {
            $parent = ParentModel::create([
                'id' => Str::uuid(),
                'user_id' => $parentUser->id,
                'name' => $parentUser->name,
                'jenis_kelamin' => $genders[$index % 2],
                'status' => $statuses[$index % 3],
                'address' => 'Jl. Orang Tua ' . ($index + 1) . ', Lawang, Malang',
                'created_by' => $this->adminUser->id,
            ]);
            $this->parents[$index]->parent = $parent;
        }
    }

    private function seedStudents()
    {
        $this->command->info('👨‍🎓 Seeding students...');

        $genders = ['laki-laki', 'perempuan'];
        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'];

        foreach ($this->students as $index => $studentUser) {
            $student = Student::create([
                'id' => Str::uuid(),
                'user_id' => $studentUser->id,
                'name' => $studentUser->name,
                'nisn' => '00' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                'nik' => '3507' . str_pad($index + 1, 12, '0', STR_PAD_LEFT),
                'gender' => $genders[$index % 2],
                'birth_place' => 'Malang',
                'birth_date' => Carbon::now()->subYears(16)->format('Y-m-d'),
                'religion' => $religions[$index % 5],
                'address' => 'Jl. Siswa ' . ($index + 1) . ', Lawang, Malang',
                'status' => 'Aktif',
                'created_by' => $this->adminUser->id,
            ]);
            $this->students[$index]->student = $student;
        }
    }

    private function seedSubjects()
    {
        $this->command->info('📚 Seeding subjects...');

        $subjectsData = [
            // Mata Pelajaran Umum
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI', 'category' => 'Umum'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn', 'category' => 'Umum'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND', 'category' => 'Umum'],
            ['name' => 'Matematika', 'code' => 'MTK', 'category' => 'Umum'],
            ['name' => 'Sejarah Indonesia', 'code' => 'SEJ', 'category' => 'Umum'],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'category' => 'Umum'],
            ['name' => 'Seni Budaya', 'code' => 'SBUD', 'category' => 'Umum'],
            ['name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'code' => 'PJOK', 'category' => 'Umum'],
            
            // TKRO
            ['name' => 'Teknologi Dasar Otomotif', 'code' => 'TDO', 'category' => 'Kejuruan TKRO'],
            ['name' => 'Pemeliharaan Mesin Kendaraan Ringan', 'code' => 'PMKR', 'category' => 'Kejuruan TKRO'],
            ['name' => 'Pemeliharaan Sasis dan Pemindah Tenaga', 'code' => 'PSPT', 'category' => 'Kejuruan TKRO'],
            ['name' => 'Pemeliharaan Kelistrikan Kendaraan Ringan', 'code' => 'PKKR', 'category' => 'Kejuruan TKRO'],
            
            // TBSM
            ['name' => 'Teknologi Dasar Sepeda Motor', 'code' => 'TDSM', 'category' => 'Kejuruan TBSM'],
            ['name' => 'Pemeliharaan Mesin Sepeda Motor', 'code' => 'PMSM', 'category' => 'Kejuruan TBSM'],
            ['name' => 'Pemeliharaan Sasis Sepeda Motor', 'code' => 'PSSM', 'category' => 'Kejuruan TBSM'],
            
            // TKJ
            ['name' => 'Sistem Komputer', 'code' => 'SISKOM', 'category' => 'Kejuruan TKJ'],
            ['name' => 'Komputer dan Jaringan Dasar', 'code' => 'KJD', 'category' => 'Kejuruan TKJ'],
            ['name' => 'Pemrograman Dasar', 'code' => 'PD', 'category' => 'Kejuruan TKJ'],
            ['name' => 'Administrasi Infrastruktur Jaringan', 'code' => 'AIJ', 'category' => 'Kejuruan TKJ'],
            
            // TKI
            ['name' => 'Dasar-Dasar Kimia Industri', 'code' => 'DDKI', 'category' => 'Kejuruan TKI'],
            ['name' => 'Kimia Dasar', 'code' => 'KD', 'category' => 'Kejuruan TKI'],
            ['name' => 'Kimia Analitik', 'code' => 'KA', 'category' => 'Kejuruan TKI'],
        ];

        foreach ($subjectsData as $data) {
            $subject = Subject::create([
                'id' => Str::uuid(),
                'name' => $data['name'],
                'code' => $data['code'],
                'created_by' => $this->adminUser->id,
            ]);
            $this->subjects[] = $subject;
        }

        $this->command->info('✓ Created ' . count($this->subjects) . ' subjects');
    }

    private function seedClasses()
    {
        $this->command->info('🏫 Seeding classes...');

        $classesData = [
            // TKRO (Teknik Kendaraan Ringan Otomotif) -> TKR
            ['name' => 'X TKR 2025/2026', 'code' => 'X-TKRO', 'grade' => '10', 'major' => 'Teknik Kendaraan Ringan Otomotif'],
            ['name' => 'XI TKR 2025/2026', 'code' => 'XI-TKRO', 'grade' => '11', 'major' => 'Teknik Kendaraan Ringan Otomotif'],
            ['name' => 'XII TKR 2025/2026', 'code' => 'XII-TKRO', 'grade' => '12', 'major' => 'Teknik Kendaraan Ringan Otomotif'],

            // TBSM (Teknik Bisnis Sepeda Motor) -> TSM
            ['name' => 'X TSM 2025/2026', 'code' => 'X-TBSM', 'grade' => '10', 'major' => 'Teknik Bisnis Sepeda Motor'],
            ['name' => 'XI TSM 2025/2026', 'code' => 'XI-TBSM', 'grade' => '11', 'major' => 'Teknik Bisnis Sepeda Motor'],
            ['name' => 'XII TSM 2025/2026', 'code' => 'XII-TBSM', 'grade' => '12', 'major' => 'Teknik Bisnis Sepeda Motor'],

            // TKJ (Teknik Komputer & Jaringan) -> TKJ
            ['name' => 'X TKJ 2025/2026', 'code' => 'X-TKJ', 'grade' => '10', 'major' => 'Teknik Komputer & Jaringan'],
            ['name' => 'XI TKJ 2025/2026', 'code' => 'XI-TKJ', 'grade' => '11', 'major' => 'Teknik Komputer & Jaringan'],
            ['name' => 'XII TKJ 2025/2026', 'code' => 'XII-TKJ', 'grade' => '12', 'major' => 'Teknik Komputer & Jaringan'],

            // TKI (Teknik Kimia Industri) -> KI
            ['name' => 'X KI 2025/2026', 'code' => 'X-TKI', 'grade' => '10', 'major' => 'Teknik Kimia Industri'],
            ['name' => 'XI KI 2025/2026', 'code' => 'XI-TKI', 'grade' => '11', 'major' => 'Teknik Kimia Industri'],
            ['name' => 'XII KI 2025/2026', 'code' => 'XII-TKI', 'grade' => '12', 'major' => 'Teknik Kimia Industri'],
        ];

        foreach ($classesData as $data) {
            $class = Classes::create([
                'id' => Str::uuid(),
                'name' => $data['name'],
                'code' => $data['code'],
                'grade' => $data['grade'],
                'major' => $data['major'],
                'academic_year' => '2025/2026',
                'is_archived' => false,
                'created_by' => $this->adminUser->id,
            ]);
            $this->classes[] = $class;
        }

        $this->command->info('✓ Created ' . count($this->classes) . ' classes');
    }

    private function seedStudentClasses()
    {
        $this->command->info('📝 Assigning students to classes...');

        $majors = ['TKRO', 'TBSM', 'TKJ', 'TKI'];
        $count = 0;
        
        foreach ($this->students as $index => $studentUser) {
            $student = $studentUser->student;
            $major = $majors[$index % 4];
            
            // Find appropriate class based on major (assign to class X)
            $class = collect($this->classes)->first(function ($c) use ($major) {
                return str_contains($c->code, $major) && str_contains($c->code, 'X-');
            });

            if ($class) {
                StudentClass::create([
                    'id' => Str::uuid(),
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'academic_year' => '2025/2026',
                    'semester' => 'ganjil',
                    'start_date' => '2025-07-15',
                    'end_date' => '2025-12-20',
                    'status' => 'active',
                    'created_by' => $this->adminUser->id,
                ]);
                $count++;
            }
        }

        $this->command->info('✓ Assigned ' . $count . ' students to classes');
    }

    private function seedSchedules()
    {
        $this->command->info('📅 Seeding schedules...');

        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $timeSlots = [
            ['07:00:00', '08:30:00'],
            ['08:30:00', '10:00:00'],
            ['10:15:00', '11:45:00'],
            ['12:30:00', '14:00:00'],
            ['14:00:00', '15:30:00'],
        ];

        $count = 0;
        foreach ($this->classes as $class) {
            $classSubjects = $this->getSubjectsForClass($class);
            
            $dayIndex = 0;
            $slotIndex = 0;

            foreach ($classSubjects as $subject) {
                if ($dayIndex >= count($days)) break;

                $teacher = $this->teachers[array_rand($this->teachers)];

                $schedule = Schedule::create([
                    'id' => Str::uuid(),
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->teacher->id,
                    'day' => $days[$dayIndex],
                    'start_time' => $timeSlots[$slotIndex][0],
                    'end_time' => $timeSlots[$slotIndex][1],
                    'semester' => 'ganjil',
                    'academic_year' => '2025/2026',
                    'created_by' => $this->adminUser->id,
                ]);
                $this->schedules[] = $schedule;
                $count++;

                $slotIndex++;
                if ($slotIndex >= count($timeSlots)) {
                    $slotIndex = 0;
                    $dayIndex++;
                }
            }
        }

        $this->command->info('✓ Created ' . $count . ' schedules');
    }

    private function seedSettingSchedule()
    {
        $this->command->info('⚙️ Seeding setting schedule...');

        SettingSchedule::create([
            'id' => Str::uuid(),
            'start_time' => '07:00:00',
            'end_time' => '15:30:00',
            'created_by' => $this->adminUser->id,
        ]);
    }

    private function seedAttendances()
    {
        $this->command->info('📋 Seeding attendances (In/Out)...');

        // Create attendance for last 30 days
        $startDate = Carbon::now()->subDays(30);
        $count = 0;

        for ($day = 0; $day < 30; $day++) {
            $date = $startDate->copy()->addDays($day);
            
            // Skip weekends
            if ($date->isWeekend()) continue;

            foreach ($this->students as $studentUser) {
                $student = $studentUser->student;
                
                // 90% attendance rate
                if (rand(1, 100) <= 90) {
                    $checkIn = $date->copy()->setTime(7, rand(0, 30), 0);
                    $checkOut = $date->copy()->setTime(15, rand(0, 30), 0);

                    Attendance::create([
                        'id' => Str::uuid(),
                        'student_id' => $student->id,
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $checkIn->format('H:i:s'),
                        'check_out' => $checkOut->format('H:i:s'),
                        'created_by' => $this->adminUser->id,
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info('✓ Created ' . $count . ' attendance records');
    }

    private function seedLessonAttendances()
    {
        $this->command->info('📖 Seeding lesson attendances...');

        $startDate = Carbon::now()->subDays(30);
        $count = 0;

        for ($day = 0; $day < 30; $day++) {
            $date = $startDate->copy()->addDays($day);
            
            if ($date->isWeekend()) continue;

            $dayName = $this->getDayName($date);

            // Get schedules for this day
            $daySchedules = collect($this->schedules)->filter(function ($schedule) use ($dayName) {
                return $schedule->day === $dayName;
            });

            foreach ($daySchedules as $schedule) {
                // Get students in this class
                $studentClasses = StudentClass::where('class_id', $schedule->class_id)
                    ->where('academic_year', '2025/2026')
                    ->get();

                foreach ($studentClasses as $studentClass) {
                    // 85% attendance rate for lessons
                    $statuses = ['hadir', 'terlambat', 'izin', 'sakit', 'alpha'];
                    $status = rand(1, 100) <= 85 ? 'hadir' : $statuses[array_rand($statuses)];
                    
                    Lesson::create([
                        'id' => Str::uuid(),
                        'student_id' => $studentClass->student_id,
                        'class_id' => $schedule->class_id,
                        'subject_id' => $schedule->subject_id,
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $date->copy()->setTime(7, rand(0, 30), 0)->format('H:i:s'),
                        'check_in_status' => $status,
                        'academic_year' => '2025/2026',
                        'semester' => 'ganjil',
                        'created_by' => $this->adminUser->id,
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info('✓ Created ' . $count . ' lesson attendance records');
    }

    private function seedStudentGrades()
    {
        $this->command->info('📊 Seeding student grades...');

        $count = 0;
        $months = [7, 8, 9, 10, 11, 12]; // Juli - Desember (Semester Ganjil)
        $monthNames = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        foreach ($this->schedules as $schedule) {
            $studentClasses = StudentClass::where('class_id', $schedule->class_id)
                ->where('academic_year', '2025/2026')
                ->get();

            foreach ($studentClasses as $studentClass) {
                // Create grades for each month
                foreach ($months as $index => $month) {
                    StudentGrades::create([
                        'id' => Str::uuid(),
                        'student_id' => $studentClass->student_id,
                        'class_id' => $schedule->class_id,
                        'subject_id' => $schedule->subject_id,
                        'academic_year' => '2025/2026',
                        'semester' => 'ganjil',
                        'month' => $month,
                        'month_name' => $monthNames[$index],
                        'h1' => rand(70, 100),
                        'h2' => rand(70, 100),
                        'h3' => rand(70, 100),
                        'k1' => rand(70, 100),
                        'k2' => rand(70, 100),
                        'k3' => rand(70, 100),
                        'ck' => rand(70, 100),
                        'p' => rand(75, 100),
                        'k' => rand(75, 100),
                        'aktif' => rand(75, 100),
                        'nilai' => rand(75, 95),
                        'created_by' => $this->adminUser->id,
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info('✓ Created ' . $count . ' student grades');
    }

    private function seedNews()
    {
        $this->command->info('📰 Seeding news...');

        $newsData = [
            [
                'title' => 'Penerimaan Peserta Didik Baru (PPDB) 2025/2026',
                'slug' => 'ppdb-2025-2026',
                'content' => '<p>SMK PGRI Lawang membuka pendaftaran peserta didik baru untuk tahun ajaran 2025/2026. Pendaftaran dibuka mulai tanggal 1 Juni 2025.</p>',
                'category' => 'Pengumuman',
            ],
            [
                'title' => 'Prestasi Siswa TKJ di Lomba Programming',
                'slug' => 'prestasi-tkj-programming',
                'content' => '<p>Siswa jurusan TKJ berhasil meraih juara 1 dalam lomba programming tingkat provinsi.</p>',
                'category' => 'Prestasi',
            ],
            [
                'title' => 'Kegiatan Prakerin Tahun 2025',
                'slug' => 'prakerin-2025',
                'content' => '<p>Siswa kelas XI akan melaksanakan Praktek Kerja Industri (Prakerin) mulai bulan Maret 2025.</p>',
                'category' => 'Kegiatan',
            ],
        ];

        foreach ($newsData as $data) {
            News::create([
                'id' => Str::uuid(),
                'user_id' => $this->adminUser->id,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'category' => $data['category'],
                'published_at' => Carbon::now(),
            ]);
        }

        $this->command->info('✓ Created ' . count($newsData) . ' news articles');
    }

    private function getSubjectsForClass($class)
    {
        $commonCodes = ['PAI', 'PPKn', 'BIND', 'MTK', 'SEJ', 'BING', 'SBUD', 'PJOK'];
        $commonSubjects = collect($this->subjects)->filter(function ($subject) use ($commonCodes) {
            return in_array($subject->code, $commonCodes);
        });

        $majorSubjects = collect();
        
        if (str_contains($class->code, 'TKRO')) {
            $codes = ['TDO', 'PMKR', 'PSPT', 'PKKR'];
            $majorSubjects = collect($this->subjects)->filter(function ($subject) use ($codes) {
                return in_array($subject->code, $codes);
            });
        } elseif (str_contains($class->code, 'TBSM')) {
            $codes = ['TDSM', 'PMSM', 'PSSM'];
            $majorSubjects = collect($this->subjects)->filter(function ($subject) use ($codes) {
                return in_array($subject->code, $codes);
            });
        } elseif (str_contains($class->code, 'TKJ')) {
            $codes = ['SISKOM', 'KJD', 'PD', 'AIJ'];
            $majorSubjects = collect($this->subjects)->filter(function ($subject) use ($codes) {
                return in_array($subject->code, $codes);
            });
        } elseif (str_contains($class->code, 'TKI')) {
            $codes = ['DDKI', 'KD', 'KA'];
            $majorSubjects = collect($this->subjects)->filter(function ($subject) use ($codes) {
                return in_array($subject->code, $codes);
            });
        }

        return $commonSubjects->merge($majorSubjects)->values();
    }

    private function getDayName($date)
    {
        $days = [
            0 => 'minggu',
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
        ];

        return $days[$date->dayOfWeek];
    }
}
