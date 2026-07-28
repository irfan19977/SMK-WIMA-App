<?php

namespace App\Services;

use App\Models\WaNotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl = 'https://api.fonnte.com/send';
    protected $token;
    protected $enabled;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->enabled = config('services.fonnte.enabled', false);
    }

    /**
     * Kirim pesan WhatsApp via Fonnte API
     */
    public function send(string $phone, string $message, string $type = 'general', ?string $referenceId = null): array
    {
        if (!$this->enabled) {
            Log::info('WhatsApp notification disabled', ['phone' => $phone, 'type' => $type]);
            return ['success' => false, 'reason' => 'disabled'];
        }

        if (empty($this->token)) {
            Log::warning('Fonnte API token not configured');
            return ['success' => false, 'reason' => 'no_token'];
        }

        $phone = $this->formatPhone($phone);
        if (empty($phone)) {
            return ['success' => false, 'reason' => 'invalid_phone'];
        }

        try {
            // KIRIM DAN MENERIMA
            $response = Http::timeout(5)->connectTimeout(3)->withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();
            $success = $response->successful() && ($result['status'] ?? false);

            // Log notifikasi
            WaNotificationLog::create([
                'phone' => $phone,
                'message' => $message,
                'type' => $type,
                'reference_id' => $referenceId,
                'status' => $success ? 'sent' : 'failed',
                'response' => json_encode($result),
            ]);

            if (!$success) {
                Log::warning('WhatsApp notification failed', [
                    'phone' => $phone,
                    'type' => $type,
                    'response' => $result,
                ]);
            }

            return [
                'success' => $success,
                'response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp notification error', [
                'phone' => $phone,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            WaNotificationLog::create([
                'phone' => $phone,
                'message' => $message,
                'type' => $type,
                'reference_id' => $referenceId,
                'status' => 'error',
                'response' => json_encode(['error' => $e->getMessage()]),
            ]);

            return ['success' => false, 'reason' => 'exception', 'error' => $e->getMessage()];
        }
    }

    /**
     * Format nomor telepon ke format internasional (62xxx)
     */
    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (empty($phone)) {
            return '';
        }

        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        } elseif (str_starts_with($phone, '+62')) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Notifikasi kehadiran siswa ke orang tua
     */
    public function sendAttendanceNotification($student, $attendance, $lessonSubject = null): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $statusLabel = match($attendance->check_in_status) {
            'tepat' => '✅ Tepat Waktu',
            'terlambat' => '⚠️ Terlambat',
            'izin' => '📋 Izin',
            'sakit' => '🏥 Sakit',
            'alpha' => '❌ Alpha',
            default => $attendance->check_in_status,
        };

        $className = $student->getCurrentClass()?->name ?? '-';

        $message = "📢 *Notifikasi Kehadiran SMK WIMA*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($attendance->date, 'Asia/Jakarta')->format('d M Y') . "\n"
            . "🕐 Jam Masuk: " . ($attendance->check_in ?? '-') . "\n"
            . "📊 Status: {$statusLabel}\n";

        if ($attendance->check_out) {
            $checkOutLabel = match($attendance->check_out_status) {
                'tepat' => '✅ Tepat Waktu',
                'lebih_awal' => '⚠️ Lebih Awal',
                default => $attendance->check_out_status ?? '-',
            };
            $message .= "🕐 Jam Pulang: {$attendance->check_out}\n"
                . "📊 Status Pulang: {$checkOutLabel}\n";
        }

        if ($lessonSubject) {
            $message .= "\n📚 *Absensi Pelajaran*\n"
                . "📖 Mata Pelajaran: {$lessonSubject->name}\n"
                . "✅ Status Pelajaran: Hadir\n";
        }

        $message .= "\nTerima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'attendance', $attendance->id ?? null);
    }

    /**
     * Notifikasi keterlambatan siswa
     */
    public function sendLateNotification($student, $attendance, $lessonSubject = null): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $message = "⚠️ *Pemberitahuan Keterlambatan*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "Putra/Putri Bapak/Ibu datang *terlambat* pada:\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($attendance->date, 'Asia/Jakarta')->format('d M Y') . "\n"
            . "🕐 Jam Masuk: {$attendance->check_in}\n";

        if ($lessonSubject) {
            $message .= "\n📚 *Absensi Pelajaran*\n"
                . "📖 Mata Pelajaran: {$lessonSubject->name}\n"
                . "⚠️ Status Pelajaran: Terlambat\n";
        }

        $message .= "\nMohon perhatian agar putra/putri datang tepat waktu.\n\n"
            . "Terima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'late', $attendance->id ?? null);
    }

    /**
     * Notifikasi alpha (tidak hadir tanpa keterangan)
     */
    public function sendAlphaNotification($student, $date): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $message = "❌ *Pemberitahuan Ketidakhadiran*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "Putra/Putri Bapak/Ibu *tidak hadir tanpa keterangan (Alpha)* pada:\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($date, 'Asia/Jakarta')->format('d M Y') . "\n\n"
            . "Mohon konfirmasi atau hubungi pihak sekolah.\n\n"
            . "Terima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'alpha', null);
    }

    /**
     * Notifikasi izin siswa (disetujui/ditolak)
     */
    public function sendPermissionNotification($student, $permission, string $action): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $statusEmoji = $action === 'approved' ? '✅' : '❌';
        $statusText = $action === 'approved' ? 'DISETUJUI' : 'DITOLAK';

        $dateRange = \Carbon\Carbon::parse($permission->start_date, 'Asia/Jakarta')->format('d M Y');
        if ($permission->end_date) {
            $dateRange .= ' - ' . \Carbon\Carbon::parse($permission->end_date, 'Asia/Jakarta')->format('d M Y');
        }

        $message = "{$statusEmoji} *Notifikasi Izin Siswa*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "Izin {$permission->type_label} telah *{$statusText}*\n"
            . "📅 Tanggal: {$dateRange}\n"
            . "📝 Alasan: {$permission->reason}\n";

        if ($action === 'rejected' && $permission->rejection_reason) {
            $message .= "💬 Alasan Ditolak: {$permission->rejection_reason}\n";
        }

        $message .= "\nTerima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'permission', $permission->id);
    }

    /**
     * Notifikasi hasil ujian
     */
    public function sendExamResultNotification($student, $exam, $submission): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $message = "📝 *Notifikasi Hasil Ujian*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "Ujian: *{$exam->title}*\n"
            . "📚 Mata Pelajaran: " . ($exam->subject->name ?? '-') . "\n"
            . "📊 Nilai: *{$submission->score}*\n"
            . "✅ Benar: {$submission->correct_answers}\n"
            . "❌ Salah: {$submission->wrong_answers}\n\n"
            . "Terima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'exam_result', $submission->id ?? null);
    }

    /**
     * Notifikasi siswa belum check-in sama sekali hari ini
     */
    public function sendMissingCheckInNotification($student, string $date): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $message = "❌ *Pemberitahuan Ketidakhadiran*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "Putra/Putri Bapak/Ibu *belum tercatat hadir* di sekolah pada:\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($date, 'Asia/Jakarta')->format('d M Y') . "\n\n"
            . "Mohon konfirmasi atau hubungi pihak sekolah jika ada keterangan.\n\n"
            . "Terima kasih.\n_SMK WIMA_";

        $referenceId = $student->id . '_' . $date;

        return $this->send($parentPhone, $message, 'missing_checkin', $referenceId);
    }

    /**
     * Notifikasi siswa tidak hadir di pelajaran tertentu
     */
    public function sendMissingLessonNotification($student, $schedule, string $date): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';
        $subjectName = $schedule->subject?->name ?? '-';
        $startTime = \Carbon\Carbon::createFromTimeString($schedule->start_time, 'Asia/Jakarta')->format('H:i');
        $endTime = \Carbon\Carbon::createFromTimeString($schedule->end_time, 'Asia/Jakarta')->format('H:i');

        $message = "⚠️ *Pemberitahuan Ketidakhadiran Pelajaran*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "Putra/Putri Bapak/Ibu *tidak tercatat hadir* pada:\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($date, 'Asia/Jakarta')->format('d M Y') . "\n"
            . "📚 Mata Pelajaran: *{$subjectName}*\n"
            . "🕐 Jam: {$startTime} - {$endTime}\n\n"
            . "Mohon konfirmasi atau hubungi pihak sekolah.\n\n"
            . "Terima kasih.\n_SMK WIMA_";

        $referenceId = $student->id . '_' . ($schedule->subject_id ?? '') . '_' . $date;

        return $this->send($parentPhone, $message, 'missing_lesson', $referenceId);
    }

    /**
     * Notifikasi check-out (pulang) siswa ke orang tua
     */
    public function sendCheckOutNotification($student, $attendance): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $checkOutLabel = match($attendance->check_out_status) {
            'tepat' => '✅ Tepat Waktu',
            'lebih_awal' => '⚠️ Lebih Awal',
            default => $attendance->check_out_status ?? '-',
        };

        $message = "📢 *Notifikasi Kepulangan SMK WIMA*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($attendance->date, 'Asia/Jakarta')->format('d M Y') . "\n"
            . "🕐 Jam Pulang: {$attendance->check_out}\n"
            . "📊 Status: {$checkOutLabel}\n"
            . "\nTerima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'attendance', $attendance->id ?? null);
    }

    /**
     * Notifikasi kehadiran pelajaran siswa ke orang tua
     */
    public function sendLessonAttendanceNotification($student, $attendance, $lessonSubject, string $lessonStatus = 'hadir'): array
    {
        $parentPhone = $this->getParentPhone($student);
        if (!$parentPhone) {
            return ['success' => false, 'reason' => 'no_parent_phone'];
        }

        $className = $student->getCurrentClass()?->name ?? '-';

        $statusLabel = match($lessonStatus) {
            'hadir' => '✅ Hadir',
            'terlambat' => '⚠️ Terlambat',
            'izin' => '📋 Izin',
            'sakit' => '🏥 Sakit',
            'alpha' => '❌ Alpha',
            default => ucfirst($lessonStatus),
        };

        $subjectName = is_object($lessonSubject) ? $lessonSubject->name : $lessonSubject;
        $date = is_object($attendance) ? $attendance->date : $attendance;
        $attendanceId = is_object($attendance) ? ($attendance->id ?? null) : null;

        $message = "📚 *Notifikasi Absensi Pelajaran SMK WIMA*\n\n"
            . "Yth. Orang Tua/Wali dari:\n"
            . "👤 *{$student->name}*\n"
            . "🏫 Kelas: {$className}\n\n"
            . "📅 Tanggal: " . \Carbon\Carbon::parse($date, 'Asia/Jakarta')->format('d M Y') . "\n"
            . "📖 Mata Pelajaran: *{$subjectName}*\n"
            . "🕐 Jam: " . now('Asia/Jakarta')->format('H:i') . "\n"
            . "📊 Status: {$statusLabel}\n\n"
            . "Terima kasih.\n_SMK WIMA_";

        return $this->send($parentPhone, $message, 'lesson_attendance', $attendanceId);
    }

    /**
     * Susun pesan dengan template identitas sekolah
     */
    protected function formatSchoolMessage(string $message): string
    {
        $schoolName = config('app.name', 'Sekolah');

        return "📢 *Pesan dari {$schoolName}*\n\n"
            . $message
            . "\n\n_Terima kasih._\n_{$schoolName}_";
    }

    /**
     * Notifikasi umum/custom
     */
    public function sendCustomNotification(string $phone, string $message): array
    {
        return $this->send($phone, $this->formatSchoolMessage($message), 'custom');
    }

    /**
     * Kirim notifikasi broadcast ke semua orang tua dari siswa di kelas tertentu
     */
    public function sendBroadcast(string $message, ?string $classId = null): array
    {
        $query = \App\Models\Student::query();

        if ($classId) {
            $query->whereHas('classes', function ($q) use ($classId) {
                $q->where('classes.id', $classId)
                  ->where('student_class.status', 'active');
            });
        }

        $students = $query->get();
        $results = ['total' => 0, 'sent' => 0, 'failed' => 0];
        $processedPhones = [];
        $formattedMessage = $this->formatSchoolMessage($message);

        foreach ($students as $student) {
            $parentPhone = $this->getParentPhone($student);
            if (!$parentPhone) {
                continue;
            }

            // Hindari kirim berulang ke nomor yang sama
            $normalizedPhone = $this->formatPhone($parentPhone);
            if (in_array($normalizedPhone, $processedPhones, true)) {
                continue;
            }
            $processedPhones[] = $normalizedPhone;
            $results['total']++;

            $result = $this->send($parentPhone, $formattedMessage, 'broadcast');
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }

    /**
     * Dapatkan nomor HP orang tua dari siswa
     */
    protected function getParentPhone($student): ?string
    {
        $parent = \App\Models\ParentModel::where('student_id', $student->id)->first();
        if ($parent && !empty($parent->phone)) {
            return $parent->phone;
        }

        return null;
    }
}
