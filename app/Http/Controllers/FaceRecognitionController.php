<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FaceRecognitionController extends Controller
{
    use AuthorizesRequests;
    // PERBAIKAN: Konstanta untuk threshold yang lebih ketat
    const FACE_RECOGNITION_THRESHOLD = 0.45;  // Lebih ketat dari 0.6
    const MIN_CONFIDENCE_PERCENTAGE = 75;     // Minimum 75% confidence
    const EXCELLENT_CONFIDENCE = 90;          // 90%+ = Excellent
    const GOOD_CONFIDENCE = 80;               // 80-89% = Good
    const FAIR_CONFIDENCE = 75;               // 75-79% = Fair (minimum)

    /**
     * Show face recognition attendance page
     */
    public function index()
    {
        $this->authorize('face_recognition.index');
        // Get all registered students for face recognition
        $registeredStudents = Student::whereNotNull('face_registered_at')
            ->whereNotNull('face_encoding')
            ->orderBy('name')
            ->get();

        return view('recognition.index', compact('registeredStudents'));
    }

    /**
     * Get registered students for face recognition (API endpoint)
     */
    public function getRegisteredStudents()
    {
        try {
            $students = Student::whereNotNull('face_registered_at')
                ->whereNotNull('face_encoding')
                ->select('id', 'name', 'nisn', 'face_encoding')
                ->get();

            return response()->json([
                'success' => true,
                'students' => $students,
                'count' => $students->count()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load registered students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Identify face from camera input - DIPERBAIKI
     */
    public function identifyFace(Request $request)
    {
        try {
            $request->validate([
                'face_encoding' => ['required', 'string'], // JSON array of face encoding
            ]);

            // Parse the input encoding
            $inputEncoding = json_decode($request->face_encoding, true);
            if (!is_array($inputEncoding) || count($inputEncoding) !== 128) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid face encoding format. Expected 128-dimensional array.'
                ], 422);
            }

            // Get all registered students
            $students = Student::whereNotNull('face_registered_at')
                ->whereNotNull('face_encoding')
                ->get();

            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No registered faces found in database'
                ], 404);
            }

            $bestMatch = null;
            $bestDistance = PHP_FLOAT_MAX;
            $allMatches = []; // TAMBAHAN: untuk debugging

            // Compare with each registered student
            foreach ($students as $student) {
                $storedEncoding = json_decode($student->face_encoding, true);
                if (!is_array($storedEncoding) || count($storedEncoding) !== 128) {
                    continue; // Skip invalid encodings
                }

                // Calculate Euclidean distance
                $distance = $this->calculateEuclideanDistance($inputEncoding, $storedEncoding);
                
                // TAMBAHAN: Simpan semua hasil untuk debugging
                $allMatches[] = [
                    'student_name' => $student->name,
                    'distance' => round($distance, 4),
                    'confidence_raw' => $this->calculateConfidenceFromDistance($distance)
                ];
                
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestMatch = $student;
                }
            }

            // PERBAIKAN: Validasi threshold yang lebih ketat
            if ($bestMatch && $bestDistance <= self::FACE_RECOGNITION_THRESHOLD) {
                
                // PERBAIKAN: Hitung confidence dengan formula yang lebih baik
                $confidence = $this->calculateConfidenceFromDistance($bestDistance);
                $confidencePercentage = round($confidence * 100, 1);

                // PERBAIKAN: Validasi minimum confidence
                if ($confidencePercentage < self::MIN_CONFIDENCE_PERCENTAGE) {
                    return response()->json([
                        'success' => true,
                        'match_found' => false,
                        'message' => 'Face similarity found but confidence too low',
                        'best_match' => [
                            'name' => $bestMatch->name,
                            'distance' => round($bestDistance, 4),
                            'confidence' => $confidencePercentage . '%',
                            'required_min' => self::MIN_CONFIDENCE_PERCENTAGE . '%'
                        ],
                        'debug_info' => [
                            'threshold' => self::FACE_RECOGNITION_THRESHOLD,
                            'all_matches' => array_slice($allMatches, 0, 5) // Top 5 matches untuk debugging
                        ]
                    ]);
                }

                // Get student's class information
                $studentClass = DB::table('student_class')
                    ->join('classes', 'student_class.class_id', '=', 'classes.id')
                    ->where('student_class.student_id', $bestMatch->id)
                    ->whereNull('student_class.deleted_at')
                    ->whereNull('classes.deleted_at')
                    ->select('classes.name as class_name')
                    ->first();

                $className = $studentClass ? $studentClass->class_name : 'Kelas tidak tersedia';

                // PERBAIKAN: Determine quality based on confidence
                $qualityLabel = $this->getConfidenceQualityLabel($confidencePercentage);

                return response()->json([
                    'success' => true,
                    'match_found' => true,
                    'student' => [
                        'id' => $bestMatch->id,
                        'name' => $bestMatch->name,
                        'nisn' => $bestMatch->nisn,
                        'class' => $className,
                        'gender' => $bestMatch->gender ?? '-',
                        'birth_date' => $bestMatch->birth_date ?? '-',
                        'address' => $bestMatch->address ?? '-',
                        'face_photo_url' => $bestMatch->face_photo ? Storage::disk('public')->url($bestMatch->face_photo) : null
                    ],
                    'confidence' => round($confidence, 3),
                    'confidence_percentage' => $confidencePercentage,
                    'quality_label' => $qualityLabel,
                    'distance' => round($bestDistance, 4),
                    'recognition_info' => [
                        'threshold_used' => self::FACE_RECOGNITION_THRESHOLD,
                        'min_confidence_required' => self::MIN_CONFIDENCE_PERCENTAGE . '%',
                        'encoding_dimension' => count($inputEncoding)
                    ]
                ]);
            } else {
                // PERBAIKAN: Response yang lebih informatif untuk no match
                $debugInfo = [
                    'threshold_used' => self::FACE_RECOGNITION_THRESHOLD,
                    'min_confidence_required' => self::MIN_CONFIDENCE_PERCENTAGE . '%',
                    'best_distance' => $bestMatch ? round($bestDistance, 4) : null,
                    'distance_too_high' => $bestMatch ? ($bestDistance > self::FACE_RECOGNITION_THRESHOLD) : false,
                    'registered_students_count' => $students->count(),
                    'encoding_dimension' => count($inputEncoding)
                ];

                if ($bestMatch) {
                    $confidence = $this->calculateConfidenceFromDistance($bestDistance);
                    $debugInfo['best_match_confidence'] = round($confidence * 100, 1) . '%';
                    $debugInfo['best_match_name'] = $bestMatch->name;
                }

                return response()->json([
                    'success' => true,
                    'match_found' => false,
                    'message' => 'No matching face found in database with sufficient confidence',
                    'debug_info' => $debugInfo,
                    'top_matches' => array_slice($allMatches, 0, 3) // Top 3 untuk debugging
                ]);
            }

        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Face identification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * PERBAIKAN: Fungsi untuk menghitung confidence dari distance
     */
    private function calculateConfidenceFromDistance($distance)
    {
        // Formula yang lebih baik untuk konversi distance ke confidence
        // Semakin kecil distance, semakin tinggi confidence
        
        if ($distance <= 0.2) {
            // Excellent match
            return 0.95 - ($distance * 0.25); // 95% - adjustment
        } elseif ($distance <= 0.35) {
            // Good match  
            return 0.90 - (($distance - 0.2) * 0.5); // Scale down from 90%
        } elseif ($distance <= self::FACE_RECOGNITION_THRESHOLD) {
            // Fair match
            $range = self::FACE_RECOGNITION_THRESHOLD - 0.35;
            $position = ($distance - 0.35) / $range;
            return 0.85 - ($position * 0.10); // Scale from 85% to 75%
        } else {
            // Poor match
            return max(0, 0.75 - (($distance - self::FACE_RECOGNITION_THRESHOLD) * 2));
        }
    }

    /**
     * PERBAIKAN: Fungsi untuk label kualitas berdasarkan confidence
     */
    private function getConfidenceQualityLabel($confidencePercentage)
    {
        if ($confidencePercentage >= self::EXCELLENT_CONFIDENCE) {
            return 'Excellent';
        } elseif ($confidencePercentage >= self::GOOD_CONFIDENCE) {
            return 'Good';
        } elseif ($confidencePercentage >= self::FAIR_CONFIDENCE) {
            return 'Fair';
        } else {
            return 'Poor';
        }
    }

    /**
     * Record attendance via face recognition
     */
    public function recordAttendance(Request $request)
    {
        try {
            $request->validate([
                'student_id' => ['required', Rule::exists((new Student)->getTable(), 'id')],
                'confidence' => ['required', 'numeric', 'between:0,1'],
                'attendance_photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120']
            ]);

            $student = Student::findOrFail($request->student_id);

            // PERBAIKAN: Validasi minimum confidence untuk attendance
            $confidencePercentage = $request->confidence * 100;
            if ($confidencePercentage < self::MIN_CONFIDENCE_PERCENTAGE) {
                return response()->json([
                    'success' => false,
                    'message' => "Confidence too low ({$confidencePercentage}%). Minimum required: " . self::MIN_CONFIDENCE_PERCENTAGE . "%"
                ], 422);
            }

            // Check if already attended today (optional - remove if you allow multiple check-ins)
            $today = Carbon::today();
            $existingAttendance = DB::table('attendance')
                ->where('student_id', $student->id)
                ->whereDate('created_at', $today)
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student has already been marked present today'
                ], 422);
            }

            // Save attendance photo
            $filenameBase = Str::slug($student->nisn ?: $student->name, '_');
            $filename = $filenameBase . '_attendance_' . now()->format('Ymd_His') . '.' . $request->file('attendance_photo')->extension();
            $photoPath = $request->file('attendance_photo')->storeAs('attendance_photos', $filename, 'public');

            // Record attendance
            DB::table('attendance')->insert([
                'id' => Str::uuid(),
                'student_id' => $student->id,
                'attendance_date' => $today,
                'check_in' => now(),
                'method' => 'face_recognition',
                'confidence_score' => $request->confidence,
                'attendance_photo' => $photoPath,
                'status' => 'present',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'student' => [
                    'name' => $student->name,
                    'nisn' => $student->nisn
                ],
                'time' => now()->format('H:i:s'),
                'confidence' => round($request->confidence * 100, 1) . '%'
            ]);

        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to record attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $this->authorize('face_recognition.create');
        // Get students without face registration or all students
        $students = Student::with('user')
            ->orderBy('name')
            ->get();

        // Get recently registered students (last 10)
        $recentRegistrations = Student::whereNotNull('face_registered_at')
            ->orderBy('face_registered_at', 'desc')
            ->limit(10)
            ->get();

        return view('recognition.create2', compact('students', 'recentRegistrations'));
    }

    public function store(Request $request)
    {
        try {
            // Ambil nama tabel dari model supaya tidak salah (students vs student)
            $table = (new Student)->getTable();

            $request->validate([
                'student_id'   => ['required', Rule::exists($table, 'id')],
                'face_encoding'=> ['required', 'string'], // JSON string
                'face_photo'   => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'], // maks 5MB
            ]);

            // PERBAIKAN: Validasi face encoding format
            $faceEncoding = json_decode($request->input('face_encoding'), true);
            if (!is_array($faceEncoding) || count($faceEncoding) !== 128) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid face encoding format. Expected 128-dimensional array.',
                ], 422);
            }

            /** @var Student $student */
            $student = Student::findOrFail($request->student_id);

            // Hapus foto lama (jika ada)
            if ($student->face_photo && Storage::disk('public')->exists($student->face_photo)) {
                Storage::disk('public')->delete($student->face_photo);
            }

            // Simpan foto dengan nama yang lebih clean
            $filenameBase = Str::slug($student->nisn ?: $student->name, '_');
            $filename = $filenameBase . '_' . now()->format('Ymd_His') . '.' . $request->file('face_photo')->extension();

            // Simpan ke folder faces
            $path = $request->file('face_photo')->storeAs('faces', $filename, 'public');

            // Update student record
            $student->face_encoding = $request->input('face_encoding');
            $student->face_photo = $path; // Simpan path lengkap: faces/filename.jpg
            $student->face_registered_at = now();
            $student->save();

            // Return response dengan URL yang bisa diakses
            return response()->json([
                'success' => true,
                'message' => 'Face registration saved successfully.',
                'student_id' => $student->id,
                'student_name' => $student->name,
                'face_photo_url' => asset('storage/' . $path), // URL lengkap untuk akses
                'face_photo_path' => $path, // Path di database
                'encoding_validation' => [
                    'dimension' => count($faceEncoding),
                    'is_valid' => count($faceEncoding) === 128
                ]
            ]);

        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save face registration: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * PERBAIKAN: Calculate Euclidean distance dengan validasi
     */
    private function calculateEuclideanDistance($encoding1, $encoding2)
    {
        if (count($encoding1) !== count($encoding2)) {
            return PHP_FLOAT_MAX; // Invalid comparison
        }

        if (count($encoding1) !== 128) {
            return PHP_FLOAT_MAX; // Expect 128-dimensional encodings
        }

        $sum = 0;
        for ($i = 0; $i < count($encoding1); $i++) {
            if (!is_numeric($encoding1[$i]) || !is_numeric($encoding2[$i])) {
                return PHP_FLOAT_MAX; // Invalid numeric values
            }
            $diff = $encoding1[$i] - $encoding2[$i];
            $sum += $diff * $diff;
        }

        return sqrt($sum);
    }

    public function getStudentClass($studentId)
    {
        try {
            // Validasi studentId
            if (!$studentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student ID tidak valid'
                ], 400);
            }

            // Get student's class from student_class pivot table
            $studentClass = DB::table('student_class')
                ->join('classes', 'student_class.class_id', '=', 'classes.id')
                ->where('student_class.student_id', $studentId)
                ->whereNull('student_class.deleted_at')
                ->whereNull('classes.deleted_at') // Pastikan class juga tidak deleted
                ->select('classes.id as class_id', 'classes.name as class_name')
                ->first();

            if (!$studentClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak memiliki kelas atau kelas tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'class_id' => $studentClass->class_id,
                'class_name' => $studentClass->class_name
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    } 
    
    public function autoAttendance(Request $request)
    {
        try {
            $request->validate([
                'student_id' => ['required', Rule::exists((new Student)->getTable(), 'id')],
                'confidence' => ['required', 'numeric', 'between:0,1'],
            ]);

            // Early return if confidence is too low
            $confidencePercentage = $request->confidence * 100;
            if ($confidencePercentage < self::MIN_CONFIDENCE_PERCENTAGE) {
                return response()->json([
                    'success' => false,
                    'message' => "Auto-attendance rejected: Confidence too low"
                ], 422);
            }

            $student = Student::findOrFail($request->student_id);
            
            // Check if already attended today
            $existingAttendance = DB::table('attendance')
                ->where('student_id', $student->id)
                ->whereDate('created_at', Carbon::today())
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah absen hari ini',
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                        'class' => $student->studentClass?->name ?? 'Tidak ada kelas',
                        'face_photo_url' => $student->face_photo ? Storage::disk('public')->url($student->face_photo) : null
                    ]
                ]);
            }

            // Process new attendance
            // Get student's class from student_class pivot table
            $studentClass = DB::table('student_class')
                ->join('classes', 'student_class.class_id', '=', 'classes.id')
                ->where('student_class.student_id', $student->id)
                ->whereNull('student_class.deleted_at')
                ->whereNull('classes.deleted_at')
                ->select('classes.id as class_id', 'classes.name as class_name')
                ->first();

            if (!$studentClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak memiliki kelas yang terdaftar',
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nisn' => $student->nisn,
                        'class' => 'Tidak ada kelas',
                        'gender' => $student->gender ?? '-',
                        'birth_date' => $student->birth_date ?? '-',
                        'address' => $student->address ?? '-',
                        'face_photo_url' => $student->face_photo ? asset('storage/' . $student->face_photo) : null
                    ]
                ], 422);
            }

            // Get current date and time with Asia/Jakarta timezone
            $currentDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $currentTime = Carbon::now('Asia/Jakarta')->format('H:i');

            // Prepare student data - SIMPLIFIED VERSION (hanya class)
            $studentData = [
                'id' => $student->id,
                'name' => $student->name,
                'nisn' => $student->nisn,
                'class' => $studentClass->class_name,
                'gender' => $student->gender ?? '-',
                'birth_date' => $student->birth_date ?? '-',
                'address' => $student->address ?? '-',
                // 'face_photo_url' => $student->face_photo ? Storage::disk('public')->url($student->face_photo) : null
            ];

            // Check if already attended today
            $existingAttendance = DB::table('attendance')
                ->where('student_id', $student->id)
                ->where('class_id', $studentClass->class_id)
                ->where('date', $currentDate)
                ->first();

            if ($existingAttendance) {
                // Check if it's time for check-out
                $dayName = $this->convertDayToIndonesian(Carbon::parse($currentDate)->format('l'));
                $schedule = DB::table('setting_schedule')
                    ->where('day', $dayName)
                    ->first();

                if ($schedule && $existingAttendance->check_in && !$existingAttendance->check_out) {
                    $currentTimeCarbon = Carbon::createFromFormat('H:i', $currentTime, 'Asia/Jakarta');
                    $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                    
                    // If current time is after schedule end time, process check-out
                    if ($currentTimeCarbon->gte($scheduleEndTime)) {
                        $checkOutStatus = $currentTimeCarbon->gt($scheduleEndTime) ? 'tepat' : 'lebih_awal';
                        
                        DB::table('attendance')
                            ->where('id', $existingAttendance->id)
                            ->update([
                                'check_out' => $currentTime,
                                'check_out_status' => $checkOutStatus,
                                'updated_by' => Auth::id() ?? 'face_recognition_system',
                                'updated_at' => now()
                            ]);

                        return response()->json([
                            'success' => true,
                            'message' => 'Check-out berhasil! Selamat pulang ' . $student->name,
                            'type' => 'check_out',
                            'student' => $studentData,
                            'time' => $currentTime,
                            'status' => $checkOutStatus,
                            'confidence' => round($request->confidence * 100, 1) . '%',
                            'confidence_label' => $this->getConfidenceQualityLabel($confidencePercentage)
                        ]);
                    }
                }

                // Response untuk yang sudah absen
                return response()->json([
                    'success' => false,
                    'message' => $student->name . ' sudah melakukan absen hari ini',
                    'student' => $studentData,
                    'time' => $currentTime,
                    'confidence' => round($request->confidence * 100, 1) . '%',
                    'confidence_label' => $this->getConfidenceQualityLabel($confidencePercentage),
                    'attendance_info' => [
                        'check_in' => $existingAttendance->check_in,
                        'check_in_status' => $existingAttendance->check_in_status,
                        'check_out' => $existingAttendance->check_out,
                        'check_out_status' => $existingAttendance->check_out_status
                    ]
                ]);
            }

            // Determine check-in status based on schedule
            $checkInStatus = 'tepat';
            $dayName = $this->convertDayToIndonesian(Carbon::parse($currentDate)->format('l'));
            $schedule = DB::table('setting_schedule')
                ->where('day', $dayName)
                ->first();

            if ($schedule) {
                $currentTimeCarbon = Carbon::createFromFormat('H:i', $currentTime, 'Asia/Jakarta');
                $scheduleStartTime = Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
                
                if ($currentTimeCarbon->gt($scheduleStartTime)) {
                    $checkInStatus = 'terlambat';
                }
            }

            // Create new attendance record
            DB::table('attendance')->insert([
                'id' => Str::uuid(),
                'student_id' => $student->id,
                'class_id' => $studentClass->class_id,
                'date' => $currentDate,
                'check_in' => $currentTime,
                'check_in_status' => $checkInStatus,
                'created_by' => Auth::id() ?? 'face_recognition_system',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil! Selamat datang ' . $student->name,
                'type' => 'check_in',
                'student' => $studentData,
                'time' => $currentTime,
                'status' => $checkInStatus,
                'confidence' => round($request->confidence * 100, 1) . '%',
                'confidence_label' => $this->getConfidenceQualityLabel($confidencePercentage)
            ]);

        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert English day to Indonesian
     */
    private function convertDayToIndonesian($day)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $days[$day] ?? $day;
    }
}