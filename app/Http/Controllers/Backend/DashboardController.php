<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari request, default hari ini
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        // Query untuk mendapatkan siswa yang terlambat
        $lateStudents = DB::table('attendance')
            ->join('student', 'attendance.student_id', '=', 'student.id')
            ->join('users', 'student.user_id', '=', 'users.id')
            ->join('classes', 'attendance.class_id', '=', 'classes.id')
            ->where('attendance.date', $date)
            ->where('attendance.check_in_status', 'terlambat')
            ->whereNull('attendance.deleted_at')
            ->whereNull('student.deleted_at')
            ->select(
                'student.id as student_id',
                'student.name as name',
                'student.nisn as nis',
                'classes.name as class',
                'attendance.check_in as time',
                'attendance.date',
                'attendance.check_in_status',
                'users.email'
            )
            ->orderBy('attendance.check_in', 'desc')
            ->get();
        
        // Calculate late duration for each student
        $lateStudents = $lateStudents->map(function($student) {
            // Assuming school starts at 07:00 AM
            $schoolStartTime = Carbon::createFromTime(7, 0, 0);
            $checkInTime = Carbon::createFromFormat('H:i:s', $student->time);
            
            // Calculate minutes past 7:00 AM
            $minutesPast7AM = ($checkInTime->hour - 7) * 60 + $checkInTime->minute;
            $lateDuration = max(0, $minutesPast7AM);
            
            $student->late_duration = $lateDuration;
            
            return $student;
        });

        // Get statistics for charts
        $lateStatistics = $this->getLateStatistics();
        $donutStatistics = $this->getDonutStatistics();
        
        // Get other dashboard metrics
        $totalStudents = \App\Models\Student::whereNull('deleted_at')->count();
        $activeClasses = \App\Models\Classes::whereNull('deleted_at')->count();
        $todayAttendance = $this->getTodayAttendancePercentage();
        $totalSubjects = \App\Models\Subject::whereNull('deleted_at')->count();

        return view('dashboard.index', compact(
            'lateStudents', 
            'date', 
            'lateStatistics', 
            'donutStatistics',
            'totalStudents',
            'activeClasses', 
            'todayAttendance',
            'totalSubjects'
        ));
    }
    
    private function getLateStatistics()
    {
        // Get late statistics for the past 30 days
        $statistics = DB::table('attendance')
            ->selectRaw('DATE(date) as date, COUNT(*) as count')
            ->where('check_in_status', 'terlambat')
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->whereNull('deleted_at')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return [
            'dates' => $statistics->pluck('date')->toArray(),
            'counts' => $statistics->pluck('count')->toArray()
        ];
    }
    
    private function getDonutStatistics()
    {
        // Get attendance status distribution for today
        $statistics = DB::table('attendance')
            ->selectRaw('check_in_status, COUNT(*) as count')
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->whereNull('deleted_at')
            ->groupBy('check_in_status')
            ->get();
            
        return [
            'labels' => $statistics->pluck('check_in_status')->toArray(),
            'values' => $statistics->pluck('count')->toArray()
        ];
    }
    
    private function getTodayAttendancePercentage()
    {
        $totalPresent = DB::table('attendance')
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->whereIn('check_in_status', ['hadir', 'terlambat'])
            ->whereNull('deleted_at')
            ->count();
            
        $totalStudents = \App\Models\Student::whereNull('deleted_at')->count();
        
        if ($totalStudents > 0) {
            return round(($totalPresent / $totalStudents) * 100, 1);
        }
        
        return 0;
    }
    
    public function getChartData(Request $request)
    {
        $period = $request->get('period', '1m');
        
        $startDate = match($period) {
            '1m' => Carbon::now()->subMonth(),
            '6m' => Carbon::now()->subMonths(6),
            '1y' => Carbon::now()->subYear(),
            'all' => Carbon::now()->subYears(5), // Last 5 years for 'all'
            default => Carbon::now()->subMonth(),
        };
        
        // Get monthly attendance data
        $attendanceData = DB::table('attendance')
            ->selectRaw('
                DATE_FORMAT(date, "%Y-%m") as month,
                SUM(CASE WHEN check_in_status IN ("hadir", "tepat") THEN 1 ELSE 0 END) as on_time_count,
                SUM(CASE WHEN check_in_status = "terlambat" THEN 1 ELSE 0 END) as late_count
            ')
            ->where('date', '>=', $startDate)
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Fill missing months with zero
        $months = [];
        $onTimeCount = [];
        $lateCount = [];
        
        $current = $startDate->copy()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        
        while ($current <= $end) {
            $monthKey = $current->format('Y-m');
            $monthLabel = $current->format('M Y');
            
            $data = $attendanceData->where('month', $monthKey)->first();
            
            $months[] = $monthLabel;
            $onTimeCount[] = $data ? $data->on_time_count : 0;
            $lateCount[] = $data ? $data->late_count : 0;
            
            $current->addMonth();
        }
        
        return response()->json([
            'months' => $months,
            'onTimeCount' => $onTimeCount,
            'lateCount' => $lateCount
        ]);
    }
}