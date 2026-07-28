<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\LessonAttendance;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\Student;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotifyMissingLessonAttendance extends Command
{
    protected $signature = 'attendance:notify-missing-lesson';
    protected $description = 'Kirim notifikasi ke orang tua siswa yang tidak hadir di pelajaran yang sudah berakhir';

    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->format('Y-m-d');
        $dayName = $this->convertDayToIndonesian($now->format('l'));
        $currentTimeOnly = $now->format('H:i:s');

        $activeSemester = Semester::getCurrentActiveSemester();
        $academicYear = $activeSemester?->academic_year;
        $semesterType = $activeSemester?->semester_type;

        // Ambil semua jadwal pelajaran yang sudah BERAKHIR hari ini
        // Tabel schedule menyimpan nama hari dalam lowercase (senin, selasa, ...)
        $endedSchedulesQuery = Schedule::with(['subject', 'classRoom'])
            ->where('day', strtolower($dayName))
            ->where('end_time', '<=', $currentTimeOnly);

        if ($academicYear) {
            $endedSchedulesQuery->where('academic_year', $academicYear);
        }
        if ($semesterType) {
            if (strtolower($semesterType) === 'ganjil') {
                $endedSchedulesQuery->where(function ($q) {
                    $q->whereIn('semester', ['Ganjil', 'ganjil', '1', 1])
                      ->orWhereNull('semester')
                      ->orWhere('semester', '');
                });
            } elseif (strtolower($semesterType) === 'genap') {
                $endedSchedulesQuery->whereIn('semester', ['Genap', 'genap', '2', 2]);
            }
        }

        $endedSchedules = $endedSchedulesQuery->get();

        if ($endedSchedules->isEmpty()) {
            $this->info('Tidak ada jadwal pelajaran yang sudah berakhir hari ini.');
            return Command::SUCCESS;
        }

        $wa = app(WhatsAppService::class);
        $notifSent = 0;
        $notifSkipped = 0;
        $notifFailed = 0;
        $notifNoPhone = 0;
        $processedCheckin = [];

        foreach ($endedSchedules as $schedule) {
            // Ambil semua siswa aktif di kelas ini
            $students = Student::whereHas('classes', function ($q) use ($schedule, $academicYear) {
                $q->where('classes.id', $schedule->class_id)
                  ->where('student_class.status', 'active');
                if ($academicYear) {
                    $q->where('student_class.academic_year', $academicYear);
                }
            })->get();

            foreach ($students as $student) {
                // Cek apakah siswa sudah check-in harian hari ini
                $hasCheckIn = Attendance::where([
                    'student_id' => $student->id,
                    'class_id'   => $schedule->class_id,
                    'date'       => $today,
                ])->whereNotNull('check_in')->exists();

                // Jika belum check-in, kirim notif ketidakhadiran (1x per hari)
                if (!$hasCheckIn && !in_array($student->id, $processedCheckin)) {
                    $alreadyNotifiedAbsent = DB::table('wa_notification_logs')
                        ->where('type', 'missing_checkin')
                        ->where('reference_id', $student->id . '_' . $today)
                        ->where('status', 'sent')
                        ->exists();

                    if (!$alreadyNotifiedAbsent) {
                        $result = $wa->sendMissingCheckInNotification($student, $today);
                        $processedCheckin[] = $student->id;
                        if ($result['success']) {
                            $notifSent++;
                            Log::info("Missing check-in notif sent: {$student->name}");
                        } elseif (($result['reason'] ?? '') === 'no_parent_phone') {
                            $notifNoPhone++;
                        } else {
                            $notifFailed++;
                            Log::warning("Missing check-in notif failed: {$student->name} - reason: " . ($result['reason'] ?? 'unknown'));
                        }
                    } else {
                        $notifSkipped++;
                    }
                }

                // Cek apakah sudah ada lesson attendance untuk pelajaran ini hari ini
                $hasAttendance = LessonAttendance::where([
                    'student_id' => $student->id,
                    'class_id'   => $schedule->class_id,
                    'subject_id' => $schedule->subject_id,
                    'date'       => $today,
                ])->exists();

                if ($hasAttendance) {
                    $notifSkipped++;
                    continue;
                }

                // Cek apakah notifikasi pelajaran terlewat sudah pernah dikirim hari ini
                $alreadyNotified = DB::table('wa_notification_logs')
                    ->where('type', 'missing_lesson')
                    ->where('reference_id', $student->id . '_' . $schedule->subject_id . '_' . $today)
                    ->where('status', 'sent')
                    ->exists();

                if ($alreadyNotified) {
                    $notifSkipped++;
                    continue;
                }

                // Kirim notifikasi pelajaran terlewat
                $result = $wa->sendMissingLessonNotification($student, $schedule, $today);

                if ($result['success']) {
                    $notifSent++;
                    Log::info("Missing lesson notif sent: {$student->name} - {$schedule->subject->name}");
                } elseif (($result['reason'] ?? '') === 'no_parent_phone') {
                    $notifNoPhone++;
                } else {
                    $notifFailed++;
                    Log::warning("Missing lesson notif failed: {$student->name} - reason: " . ($result['reason'] ?? 'unknown'));
                }
            }
        }

        $this->info("Selesai. Terkirim: {$notifSent}, gagal: {$notifFailed}, tanpa nomor ortu: {$notifNoPhone}, dilewati: {$notifSkipped}");
        return Command::SUCCESS;
    }

    private function convertDayToIndonesian(string $day): string
    {
        return match($day) {
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
            default     => $day,
        };
    }
}
