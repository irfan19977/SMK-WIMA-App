<?php

namespace Database\Seeders;

use App\Helpers\AcademicYearHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JulyStudentAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYearHelper::getCurrentAcademicYear();
        $startYear = (int) explode('/', $academicYear)[0];
        $semester = 'ganjil';
        $julyStart = Carbon::create($startYear, 7, 1)->startOfDay();
        $julyEnd = $julyStart->copy()->day(20)->endOfDay();
        $julyDeleteEnd = $julyStart->copy()->endOfMonth();
        $now = now();

        $students = [
            ['name' => 'Tristan Ibrahimmi R.', 'email' => 'student51@smkwima.sch.id', 'card' => '93:34:A8:07', 'gender' => 'laki-laki', 'number' => 51],
            ['name' => 'Tri Sunengseh', 'email' => 'student52@smkwima.sch.id', 'card' => '73:CB:D9:07', 'gender' => 'perempuan', 'number' => 52],
            ['name' => 'Ilham Maulana', 'email' => 'student53@smkwima.sch.id', 'card' => '43:40:CE:11', 'gender' => 'laki-laki', 'number' => 53],
            ['name' => 'M. Ubaidillah', 'email' => 'student54@smkwima.sch.id', 'card' => 'B3:7B:49:FE', 'gender' => 'laki-laki', 'number' => 54],
            ['name' => 'Silvania Naen Nova', 'email' => 'student55@smkwima.sch.id', 'card' => 'C0:AD:04:58', 'gender' => 'perempuan', 'number' => 55],
            ['name' => 'Novita Regina Putri', 'email' => 'student56@smkwima.sch.id', 'card' => '63:0F:A0:F7', 'gender' => 'perempuan', 'number' => 56],
        ];

        $className = 'X TKJ ' . $academicYear;
        $class = DB::table('classes')
            ->where('code', 'X-TKJ-' . $startYear)
            ->orWhere('name', $className)
            ->first();
        if (!$class) {
            $classId = (string) Str::uuid();
            DB::table('classes')->insert([
                'id' => $classId,
                'name' => $className,
                'code' => 'X-TKJ-' . $startYear,
                'grade' => 'X',
                'major' => 'Teknik Komputer dan Jaringan',
                'academic_year' => $academicYear,
                'is_archived' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $class = DB::table('classes')->where('id', $classId)->first();
        }

        $subjectDefinitions = [
            ['code' => 'BIND', 'name' => 'Bahasa Indonesia'],
            ['code' => 'MTK', 'name' => 'Matematika'],
            ['code' => 'INF', 'name' => 'Informatika'],
            ['code' => 'PWPB', 'name' => 'Pemrograman Web dan Perangkat Bergerak'],
            ['code' => 'PABP', 'name' => 'Pendidikan Agama dan Budi Pekerti'],
            ['code' => 'BING', 'name' => 'Bahasa Inggris'],
            ['code' => 'PJOK', 'name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
            ['code' => 'DDK', 'name' => 'Dasar-Dasar Kejuruan'],
            ['code' => 'BD', 'name' => 'Basis Data'],
        ];

        foreach ($subjectDefinitions as $subject) {
            DB::table('subject')->updateOrInsert(
                ['code' => $subject['code']],
                ['name' => $subject['name'], 'updated_at' => $now, 'created_at' => $now]
            );
        }
        $subjects = DB::table('subject')->whereIn('code', array_column($subjectDefinitions, 'code'))->pluck('id', 'code');

        $teacherDefinitions = [
            ['email' => 'budi.santoso@smkwima.sch.id', 'name' => 'Budi Santoso, S.Pd', 'nip' => '198001012010011001', 'gender' => 'laki-laki'],
            ['email' => 'siti.nurhaliza@smkwima.sch.id', 'name' => 'Siti Nurhaliza, S.Pd', 'nip' => '198502022015022002', 'gender' => 'perempuan'],
            ['email' => 'ahmad.fauzi@smkwima.sch.id', 'name' => 'Ahmad Fauzi, S.Kom', 'nip' => '198803152018031003', 'gender' => 'laki-laki'],
            ['email' => 'dewi.lestari@smkwima.sch.id', 'name' => 'Dewi Lestari, S.Pd', 'nip' => '199005202019052004', 'gender' => 'perempuan'],
            ['email' => 'rudi.hartono@smkwima.sch.id', 'name' => 'Rudi Hartono, S.T', 'nip' => '198707152014071005', 'gender' => 'laki-laki'],
        ];

        $teacherIds = [];
        foreach ($teacherDefinitions as $teacher) {
            $user = User::firstOrCreate(
                ['email' => $teacher['email']],
                ['id' => (string) Str::uuid(), 'name' => $teacher['name'], 'password' => Hash::make('password'), 'status' => true, 'join_date' => $now->format('Y-m-d')]
            );
            $user->assignRole('Teacher');

            $teacherId = DB::table('teacher')->where('user_id', $user->id)->value('id');
            if (!$teacherId) {
                $teacherId = (string) Str::uuid();
                DB::table('teacher')->insert([
                    'id' => $teacherId,
                    'user_id' => $user->id,
                    'name' => $teacher['name'],
                    'nip' => $teacher['nip'],
                    'gender' => $teacher['gender'],
                    'phone' => '081234560000',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $teacherIds[$teacher['email']] = $teacherId;
        }

        $settingSchedules = [
            ['day' => 'Senin', 'start' => '07:00:00', 'end' => '15:00:00'],
            ['day' => 'Selasa', 'start' => '07:00:00', 'end' => '15:00:00'],
            ['day' => 'Rabu', 'start' => '07:00:00', 'end' => '15:00:00'],
            ['day' => 'Kamis', 'start' => '07:00:00', 'end' => '15:00:00'],
            ['day' => 'Jumat', 'start' => '07:00:00', 'end' => '11:30:00'],
            ['day' => 'Sabtu', 'start' => '07:00:00', 'end' => '12:00:00'],
        ];
        foreach ($settingSchedules as $setting) {
            DB::table('setting_schedule')->updateOrInsert(
                ['day' => $setting['day']],
                ['id' => (string) Str::uuid(), 'start_time' => $setting['start'], 'end_time' => $setting['end'], 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $scheduleDefinitions = [
            ['day' => 'senin', 'start' => '07:00:00', 'end' => '08:30:00', 'subject' => 'BIND', 'teacher' => 'siti.nurhaliza@smkwima.sch.id'],
            ['day' => 'senin', 'start' => '08:30:00', 'end' => '10:00:00', 'subject' => 'MTK', 'teacher' => 'budi.santoso@smkwima.sch.id'],
            ['day' => 'selasa', 'start' => '07:00:00', 'end' => '08:30:00', 'subject' => 'INF', 'teacher' => 'ahmad.fauzi@smkwima.sch.id'],
            ['day' => 'selasa', 'start' => '08:30:00', 'end' => '10:00:00', 'subject' => 'PWPB', 'teacher' => 'ahmad.fauzi@smkwima.sch.id'],
            ['day' => 'rabu', 'start' => '07:00:00', 'end' => '08:30:00', 'subject' => 'PABP', 'teacher' => 'dewi.lestari@smkwima.sch.id'],
            ['day' => 'rabu', 'start' => '08:30:00', 'end' => '10:00:00', 'subject' => 'BING', 'teacher' => 'siti.nurhaliza@smkwima.sch.id'],
            ['day' => 'kamis', 'start' => '07:00:00', 'end' => '08:30:00', 'subject' => 'DDK', 'teacher' => 'rudi.hartono@smkwima.sch.id'],
            ['day' => 'kamis', 'start' => '08:30:00', 'end' => '10:00:00', 'subject' => 'BD', 'teacher' => 'ahmad.fauzi@smkwima.sch.id'],
            ['day' => 'jumat', 'start' => '07:00:00', 'end' => '08:30:00', 'subject' => 'PJOK', 'teacher' => 'rudi.hartono@smkwima.sch.id'],
            ['day' => 'sabtu', 'start' => '07:00:00', 'end' => '08:30:00', 'subject' => 'MTK', 'teacher' => 'budi.santoso@smkwima.sch.id'],
        ];

        DB::table('schedule')->where('class_id', $class->id)->where('academic_year', $academicYear)->where('semester', $semester)->delete();
        foreach ($scheduleDefinitions as $schedule) {
            DB::table('schedule')->insert([
                'id' => (string) Str::uuid(),
                'class_id' => $class->id,
                'subject_id' => $subjects[$schedule['subject']],
                'teacher_id' => $teacherIds[$schedule['teacher']],
                'day' => $schedule['day'],
                'start_time' => $schedule['start'],
                'end_time' => $schedule['end'],
                'academic_year' => $academicYear,
                'semester' => $semester,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $studentIds = [];
        foreach ($students as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['id' => (string) Str::uuid(), 'name' => $data['name'], 'password' => Hash::make('password'), 'status' => true, 'join_date' => $now->format('Y-m-d')]
            );
            $user->assignRole('Student');

            $studentId = DB::table('student')->where('user_id', $user->id)->value('id');
            if (!$studentId) {
                $studentId = (string) Str::uuid();
                DB::table('student')->insert([
                    'id' => $studentId,
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'no_absen' => str_pad((string) $data['number'], 2, '0', STR_PAD_LEFT),
                    'no_card' => $data['card'],
                    'nisn' => str_pad((string) (2000000000 + $data['number']), 10, '0', STR_PAD_LEFT),
                    'nik' => str_pad((string) (3200000000000000 + $data['number']), 16, '0', STR_PAD_LEFT),
                    'gender' => $data['gender'],
                    'academic_year' => $academicYear,
                    'status' => 'siswa',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $studentIds[] = $studentId;

            DB::table('student_class')->updateOrInsert(
                ['student_id' => $studentId, 'class_id' => $class->id, 'academic_year' => $academicYear, 'semester' => $semester],
                ['id' => (string) Str::uuid(), 'start_date' => $julyStart->format('Y-m-d'), 'end_date' => null, 'status' => 'active', 'updated_at' => $now, 'created_at' => $now]
            );
        }

        DB::table('attendance')->whereIn('student_id', $studentIds)->whereBetween('date', [$julyStart->format('Y-m-d'), $julyDeleteEnd->format('Y-m-d')])->delete();
        DB::table('lesson_attendance')->whereIn('student_id', $studentIds)->whereBetween('date', [$julyStart->format('Y-m-d'), $julyDeleteEnd->format('Y-m-d')])->delete();

        $scheduleByDay = collect($scheduleDefinitions)->groupBy('day');
        $date = $julyStart->copy();
        while ($date <= $julyEnd) {
            $day = strtolower($date->locale('id')->isoFormat('dddd'));
            if (!isset($scheduleByDay[$day])) {
                $date->addDay();
                continue;
            }

            foreach ($studentIds as $studentIndex => $studentId) {
                $seed = $date->day + $studentIndex + 1;
                $status = $seed % 17 === 0 ? 'sakit' : ($seed % 13 === 0 ? 'izin' : ($seed % 7 === 0 ? 'alpha' : ($seed % 5 === 0 ? 'terlambat' : 'tepat')));
                $checkIn = in_array($status, ['izin', 'sakit', 'alpha'], true) ? null : ($status === 'terlambat' ? '07:18:00' : '06:52:00');
                $checkOut = in_array($status, ['izin', 'sakit', 'alpha'], true) ? null : '15:02:00';

                DB::table('attendance')->insert([
                    'id' => (string) Str::uuid(),
                    'student_id' => $studentId,
                    'class_id' => $class->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'check_in_status' => $status,
                    'check_out_status' => in_array($status, ['izin', 'sakit', 'alpha'], true) ? $status : 'tepat',
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($scheduleByDay[$day] as $lessonIndex => $schedule) {
                    $lessonStatus = $status === 'tepat' && ($seed + $lessonIndex) % 6 === 0 ? 'terlambat' : ($status === 'tepat' ? 'hadir' : $status);
                    $lessonCheckIn = in_array($lessonStatus, ['izin', 'sakit', 'alpha'], true)
                        ? null
                        : Carbon::parse($schedule['start'])->addMinutes($lessonStatus === 'terlambat' ? 12 : 2)->format('H:i:s');

                    DB::table('lesson_attendance')->insert([
                        'id' => (string) Str::uuid(),
                        'student_id' => $studentId,
                        'class_id' => $class->id,
                        'subject_id' => $subjects[$schedule['subject']],
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $lessonCheckIn,
                        'check_in_status' => $lessonStatus,
                        'academic_year' => $academicYear,
                        'semester' => $semester,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $date->addDay();
        }

        $this->command->info('July ' . $startYear . ' dummy schedules and attendance seeded for ' . count($studentIds) . ' students.');
    }
}
