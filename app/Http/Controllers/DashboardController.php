<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        // Get statistics for dashboard cards
        $totalStudents = DB::table('student')->count();
        $activeClasses = DB::table('classes')->where('academic_year', '2025/2026')->count();
        $todayAttendance = $this->getTodayAttendancePercentage();
        $totalSubjects = DB::table('subject')->count();

        // Get late students for today
        $lateStudents = $this->getLateStudents();
        
        // Get late statistics for chart
        $lateStatistics = $this->getLateStatistics();
        
        // Get donut chart statistics
        $donutStatistics = $this->getDonutStatistics();

        return view('dashboard.index', compact(
            'totalStudents',
            'activeClasses', 
            'todayAttendance',
            'totalSubjects',
            'lateStudents',
            'lateStatistics',
            'donutStatistics'
        ));
    }

    private function getTodayAttendancePercentage()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $totalPresent = DB::table('attendance')
            ->where('date', $today)
            ->whereIn('check_in_status', ['tepat', 'terlambat'])
            ->count();

        $totalStudents = DB::table('student')->count();

        if ($totalStudents == 0) {
            return 0;
        }

        return round(($totalPresent / $totalStudents) * 100, 1);
    }

    private function getLateStudents()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        return DB::table('attendance as a')
            ->join('student as s', 'a.student_id', '=', 's.id')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->join('classes as c', 'a.class_id', '=', 'c.id')  // Direct join to classes
            ->where('a.date', $today)
            ->where('a.check_in_status', 'terlambat')
            ->whereNull('a.deleted_at')
            ->whereNull('s.deleted_at')
            ->select([
                's.nisn as nis',
                's.name',
                'c.name as class',
                'a.date',
                'a.check_in as time'
            ])
            ->orderBy('a.check_in', 'desc')
            ->get()
            ->map(function ($student) {
                // Calculate late duration manually
                $checkInTime = strtotime($student->time);
                $startTime = strtotime('07:00:00');
                $student->late_duration = max(0, round(($checkInTime - $startTime) / 60));
                return $student;
            });
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', '1y');
        
        switch ($period) {
            case '1m':
                $data = $this->getOneMonthData();
                break;
            case '6m':
                $data = $this->getSixMonthsData();
                break;
            case 'all':
                $data = $this->getAllData();
                break;
            default: // 1y
                $data = $this->getLateStatistics();
                break;
        }
        
        return response()->json($data);
    }
    
    public function getAttendanceData(Request $request)
    {
        $period = $request->get('period', '1m');
        
        switch ($period) {
            case '1m':
                $data = $this->getOneMonthData();
                break;
            case '6m':
                $data = $this->getSixMonthsData();
                break;
            case 'all':
                $data = $this->getAllData();
                break;
            default: // 1y
                $data = $this->getLateStatistics();
                break;
        }
        
        return response()->json($data);
    }
    
    private function getOneMonthData()
    {
        $data = [
            'months' => [],
            'lateCount' => [],
            'onTimeCount' => []
        ];
        
        $currentDate = Carbon::now();
        $startDate = $currentDate->copy()->startOfMonth();
        
        // Generate days for current month
        $daysInMonth = $currentDate->daysInMonth;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $startDate->copy()->addDays($day - 1);
            
            $lateCount = DB::table('attendance')
                ->whereDate('date', $date)
                ->where('check_in_status', 'terlambat')
                ->count();
                
            $onTimeCount = DB::table('attendance')
                ->whereDate('date', $date)
                ->where('check_in_status', 'tepat')
                ->count();
            
            $data['months'][] = $date->format('d');
            $data['lateCount'][] = $lateCount;
            $data['onTimeCount'][] = $onTimeCount;
        }
        
        return $data;
    }
    
    private function getSixMonthsData()
    {
        $data = [
            'months' => [],
            'lateCount' => [],
            'onTimeCount' => []
        ];
        
        $currentDate = Carbon::now();
        $startDate = $currentDate->copy()->subMonths(5)->startOfMonth();
        
        for ($i = 0; $i < 6; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $monthName = $month->format('M');
            
            $lateCount = DB::table('attendance')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('check_in_status', 'terlambat')
                ->count();
                
            $onTimeCount = DB::table('attendance')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('check_in_status', 'tepat')
                ->count();
            
            $data['months'][] = $monthName;
            $data['lateCount'][] = $lateCount;
            $data['onTimeCount'][] = $onTimeCount;
        }
        
        return $data;
    }
    
    private function getAllData()
    {
        $data = [
            'months' => [],
            'lateCount' => [],
            'onTimeCount' => []
        ];
        
        // Get all data from the beginning
        $firstRecord = DB::table('attendance')->orderBy('date')->first();
        
        if ($firstRecord) {
            $startDate = Carbon::parse($firstRecord->date)->startOfMonth();
            $currentDate = Carbon::now();
            
            $currentMonth = $startDate->copy();
            while ($currentMonth->lte($currentDate)) {
                $monthName = $currentMonth->format('M Y');
                
                $lateCount = DB::table('attendance')
                    ->whereMonth('date', $currentMonth->month)
                    ->whereYear('date', $currentMonth->year)
                    ->where('check_in_status', 'terlambat')
                    ->count();
                    
                $onTimeCount = DB::table('attendance')
                    ->whereMonth('date', $currentMonth->month)
                    ->whereYear('date', $currentMonth->year)
                    ->where('check_in_status', 'tepat')
                    ->count();
                
                $data['months'][] = $monthName;
                $data['lateCount'][] = $lateCount;
                $data['onTimeCount'][] = $onTimeCount;
                
                $currentMonth->addMonth();
            }
        }
        
        return $data;
    }

    private function getLateStatistics()
    {
        $data = [
            'months' => [],
            'lateCount' => [],
            'onTimeCount' => []
        ];
        
        // Data 12 bulan terakhir (Feb 2025 - Jan 2026)
        $currentDate = Carbon::now();
        
        // Generate 12 bulan terakhir secara manual
        $targetMonths = [];
        $startDate = $currentDate->copy()->startOfMonth()->subMonths(11);
        
        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $targetMonths[] = $month;
        }
        
        foreach ($targetMonths as $month) {
            $monthName = $month->format('M');
            
            $lateCount = DB::table('attendance')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('check_in_status', 'terlambat')
                ->count();
                
            $onTimeCount = DB::table('attendance')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('check_in_status', 'tepat')
                ->count();
            
            $data['months'][] = $monthName;
            $data['lateCount'][] = $lateCount;
            $data['onTimeCount'][] = $onTimeCount;
        }
        
        // Debug: Log the data
        \Log::info('Late Statistics Data: ' . json_encode($data));
        
        return $data;
    }

    private function getDonutStatistics()
    {
        // Data 1 bulan terakhir
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $tepatCount = DB::table('attendance')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('check_in_status', 'tepat')
            ->count();
            
        $terlambatCount = DB::table('attendance')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('check_in_status', 'terlambat')
            ->count();
            
        $otherCount = DB::table('attendance')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('check_in_status', ['izin', 'sakit', 'alpha'])
            ->count();

        // Get labels based on current locale
        $labels = [
            __('index.on_time'),
            __('index.late'),
            __('index.others')
        ];

        $data = [
            'labels' => $labels,
            'data' => [$tepatCount, $terlambatCount, $otherCount]
        ];
        
        // Debug: Log the data
        \Log::info('Donut Statistics Data: ' . json_encode($data));
        
        return $data;
    }
}
