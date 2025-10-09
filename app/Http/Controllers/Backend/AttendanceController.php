<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('attendances.index');
        
        $query = $request->get('q');
        $user = Auth::user();
        
        // Query untuk menggabungkan data check_in dan check_out dalam satu baris
        $attendancesQuery = DB::table('attendance as a1')
            ->select([
                'a1.id as attendance_id',
                'a1.student_id',
                'a1.class_id', 
                'a1.date',
                's.name as student_name',
                'c.name as class_name',
                // Data check in
                'a1.check_in',
                'a1.check_in_status',
                // Data check out
                'a2.check_out',
                'a2.check_out_status',
                'a2.id as checkout_id'
            ])
            ->leftJoin('attendance as a2', function($join) {
                $join->on('a1.student_id', '=', 'a2.student_id')
                    ->on('a1.class_id', '=', 'a2.class_id')
                    ->on('a1.date', '=', 'a2.date')
                    ->whereNotNull('a2.check_out');
            })
            ->join('student as s', 'a1.student_id', '=', 's.id')
            ->join('classes as c', 'a1.class_id', '=', 'c.id')
            ->whereNotNull('a1.check_in')
            ->whereNull('a1.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('c.deleted_at');
        
        // Jika user adalah parent, batasi hanya data anak mereka
        if ($user->hasRole('parent')) {
            $parent = DB::table('parent')
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();
            
            if ($parent && $parent->student_id) {
                $attendancesQuery->where('a1.student_id', $parent->student_id);
            } else {
                // Jika parent tidak memiliki student_id, return empty collection
                $attendances = collect();
            }
            
            // Parent tidak bisa melakukan pencarian
            $query = null;
        }
        
        // Jika bukan parent, bisa melakukan pencarian
        if (!$user->hasRole('parent') && $query) {
            $attendancesQuery->where(function($subQuery) use ($query) {
                $subQuery->where('s.name', 'LIKE', "%{$query}%")
                        ->orWhere('c.name', 'LIKE', "%{$query}%")
                        ->orWhere('a1.date', 'LIKE', "%{$query}%");
            });
        }
        
        $attendances = isset($attendances) ? $attendances : $attendancesQuery
            ->orderBy('a1.date', 'desc')
            ->orderBy('a1.check_in', 'asc')
            ->get();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'attendances' => $attendances
            ]);
        }
        
        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $students = Student::all();
        $classes = Classes::all();
        
        return response()->json([
            'success' => true,
            'title' => 'Tambah Data Absensi',
            'students' => $students,
            'classes' => $classes
        ]);
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'check_in_status' => 'nullable|in:tepat,terlambat,izin,sakit,alpha',
            'check_out_status' => 'nullable|in:tepat,lebih_awal,tidak_absen,izin,sakit,alpha'
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah sudah ada data untuk student, class, dan date yang sama
            $existingAttendance = Attendance::where([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'date' => $request->date
            ])->first();

            if ($existingAttendance) {
                // Jika siswa sudah absen, cek apakah sudah jam pulang atau belum
                $dayName = $this->convertDayToIndonesian(Carbon::parse($request->date)->format('l'));
                $schedule = DB::table('setting_schedule')
                    ->where('day', $dayName)
                    ->first();

                if ($schedule) {
                    // Set timezone ke Asia/Jakarta
                    $currentTime = Carbon::now('Asia/Jakarta');
                    $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                    
                    // Jika belum jam pulang (bandingkan waktu langsung dengan Carbon)
                    if ($currentTime->format('H:i:s') < $schedule->end_time) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Siswa sudah melakukan absen kehadiran. Waktu pulang: ' . $scheduleEndTime->format('H:i')
                        ]);
                    }
                    
                    // Jika sudah jam pulang, cek apakah sudah ada check_out
                    if ($existingAttendance->check_out) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Siswa sudah melakukan absen kehadiran dan pulang'
                        ]);
                    }
                    
                    // Jika sudah jam pulang dan belum check_out, tambahkan check_out
                    $checkOutTime = $request->check_out ?? Carbon::now('Asia/Jakarta')->format('H:i');
                    $checkOutTimeCarbon = Carbon::createFromFormat('H:i', $checkOutTime, 'Asia/Jakarta');
                    $scheduleEndTimeCarbon = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                    
                    // Tentukan status check_out otomatis
                    $checkOutStatus = $request->check_out_status;
                    if (!$checkOutStatus) {
                        if ($checkOutTimeCarbon->lt($scheduleEndTimeCarbon)) {
                            $checkOutStatus = 'lebih_awal';
                        } else {
                            $checkOutStatus = 'tepat';
                        }
                    }
                    
                    // Update existing attendance dengan check_out
                    $existingAttendance->update([
                        'check_out' => $checkOutTime,
                        'check_out_status' => $checkOutStatus,
                        'updated_by' => Auth::id(),
                    ]);
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Absen pulang berhasil ditambahkan!'
                    ]);
                } else {
                    // Jika tidak ada jadwal, tampilkan pesan sudah absen
                    return response()->json([
                        'success' => false,
                        'message' => 'Siswa sudah melakukan absen kehadiran'
                    ]);
                }
            }

            // Jika tidak ada existing attendance, buat record baru
            $attendanceData = [
                'id' => Str::uuid(),
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'date' => $request->date,
                'created_by' => Auth::id(),
            ];

            // Jika ada check_in, tambahkan data check_in
            if ($request->check_in) {
                // Ambil hari dari tanggal dengan timezone Asia/Jakarta
                $dayName = $this->convertDayToIndonesian(Carbon::parse($request->date, 'Asia/Jakarta')->format('l'));
                
                // Cari jadwal berdasarkan hari
                $schedule = DB::table('setting_schedule')
                    ->where('day', $dayName)
                    ->first();

                // Tentukan status check_in otomatis jika tidak diset manual
                $checkInStatus = $request->check_in_status;
                if (!$checkInStatus && $schedule) {
                    $checkInTime = Carbon::createFromFormat('H:i', $request->check_in, 'Asia/Jakarta');
                    $scheduleStartTime = Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
                    
                    // Jika check_in lebih dari waktu mulai jadwal, maka terlambat
                    if ($checkInTime->gt($scheduleStartTime)) {
                        $checkInStatus = 'terlambat';
                    } else {
                        $checkInStatus = 'tepat';
                    }
                } else if (!$checkInStatus) {
                    // Jika tidak ada jadwal dan tidak ada status manual, default tepat
                    $checkInStatus = 'tepat';
                }

                $attendanceData['check_in'] = $request->check_in;
                $attendanceData['check_in_status'] = $checkInStatus;
            }

            // Jika ada check_out, tambahkan data check_out
            if ($request->check_out) {
                // Ambil hari dari tanggal dengan timezone Asia/Jakarta
                $dayName = $this->convertDayToIndonesian(Carbon::parse($request->date, 'Asia/Jakarta')->format('l'));
                
                // Cari jadwal berdasarkan hari
                $schedule = DB::table('setting_schedule')
                    ->where('day', $dayName)
                    ->first();

                // Tentukan status check_out otomatis jika tidak diset manual
                $checkOutStatus = $request->check_out_status;
                if (!$checkOutStatus && $schedule) {
                    $checkOutTime = Carbon::createFromFormat('H:i', $request->check_out, 'Asia/Jakarta');
                    $scheduleEndTime = Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                    
                    // Jika check_out kurang dari waktu selesai jadwal, maka lebih awal
                    if ($checkOutTime->lt($scheduleEndTime)) {
                        $checkOutStatus = 'lebih_awal';
                    } else {
                        $checkOutStatus = 'tepat';
                    }
                } else if (!$checkOutStatus) {
                    // Jika tidak ada jadwal dan tidak ada status manual, default tepat
                    $checkOutStatus = 'tepat';
                }

                $attendanceData['check_out'] = $request->check_out;
                $attendanceData['check_out_status'] = $checkOutStatus;
            }

            // Buat record attendance baru
            Attendance::create($attendanceData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        // Detail absensi berdasarkan student_id dan date
        $attendance = DB::table('attendance as a1')
            ->select([
                'a1.student_id',
                'a1.class_id', 
                'a1.date',
                's.name as student_name',
                'c.name as class_name',
                'a1.check_in',
                'a1.check_in_status',
                'a2.check_out',
                'a2.check_out_status'
            ])
            ->leftJoin('attendance as a2', function($join) {
                $join->on('a1.student_id', '=', 'a2.student_id')
                     ->on('a1.class_id', '=', 'a2.class_id')
                     ->on('a1.date', '=', 'a2.date')
                     ->whereNotNull('a2.check_out');
            })
            ->join('student as s', 'a1.student_id', '=', 's.id')
            ->join('classes as c', 'a1.class_id', '=', 'c.id')
            ->where('a1.id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'attendance' => $attendance
        ]);
    }

    public function edit($id)
    {
        // Ambil data berdasarkan attendance ID
        $attendance = Attendance::find($id);
        
        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Ambil semua data attendance untuk student, class, dan date yang sama
        $allAttendances = Attendance::where([
            'student_id' => $attendance->student_id,
            'class_id' => $attendance->class_id,
            'date' => $attendance->date
        ])->get();

        $checkIn = $allAttendances->where('check_in', '!=', null)->first();
        $checkOut = $allAttendances->where('check_out', '!=', null)->first();

        $students = Student::all();
        $classes = Classes::all();

        return response()->json([
            'success' => true,
            'title' => 'Edit Data Absensi',
            'attendance' => [
                'student_id' => $attendance->student_id,
                'class_id' => $attendance->class_id,
                'date' => $attendance->date,
                'check_in' => $checkIn->check_in ?? null,
                'check_in_status' => $checkIn->check_in_status ?? 'tepat',
                'check_out' => $checkOut->check_out ?? null,
                'check_out_status' => $checkOut->check_out_status ?? 'tepat'
            ],
            'students' => $students,
            'classes' => $classes
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'check_in_status' => 'nullable|in:tepat,terlambat,izin,sakit,alpha',
            'check_out_status' => 'nullable|in:tepat,lebih_awal,tidak_absen,izin,sakit,alpha'
        ]);

        try {
            DB::beginTransaction();

            // Gunakan updateOrCreate untuk memastikan hanya 1 record
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'class_id' => $request->class_id,
                    'date' => $request->date,
                ],
                [
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'check_in_status' => $request->check_in_status,
                    'check_out_status' => $request->check_out_status,
                    'updated_by' => Auth::id()
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $attendance = Attendance::find($id);
            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            // Hapus semua data attendance untuk student, class, dan date yang sama
            Attendance::where([
                'student_id' => $attendance->student_id,
                'class_id' => $attendance->class_id,
                'date' => $attendance->date
            ])->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function findByNisn($nisn)
    {
        try {
            // Find student by NISN
            $student = Student::where('nisn', $nisn)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa dengan NISN tersebut tidak ditemukan'
                ], 404);
            }

            // Get student's class from student_class pivot table
            $studentClass = DB::table('student_class')
                ->join('classes', 'student_class.class_id', '=', 'classes.id')
                ->where('student_class.student_id', $student->id)
                ->whereNull('student_class.deleted_at') // Pastikan tidak soft deleted
                ->select('classes.id as class_id', 'classes.name as class_name')
                ->first(); // Ambil kelas pertama jika ada multiple

            $className = $studentClass ? $studentClass->class_name : 'Tidak ada kelas';
            $classId = $studentClass ? $studentClass->class_id : null;

            // Return student data
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'nisn' => $student->nisn,
                    'class_name' => $className,
                    'class_id' => $classId
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari siswa: ' . $e->getMessage()
            ], 500);
        }
    }
}
