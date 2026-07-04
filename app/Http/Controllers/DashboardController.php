<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use App\Helpers\AcademicYearHelper;

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

        // Check if user is parent
        if (Auth::check() && Auth::user()->hasRole('Parent')) {
            return $this->parentDashboard();
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
        
        // Get donut chart statistics for the default 1-year period
        $donutStartDate = Carbon::now()->subMonths(11)->startOfMonth();
        $donutEndDate = Carbon::now()->endOfMonth();
        $donutStatistics = $this->getDonutStatisticsForDateRange($donutStartDate, $donutEndDate);

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

    private function parentDashboard()
    {
        // Get parent data
        $parent = \App\Models\ParentModel::where('user_id', Auth::id())->first();
        
        if (!$parent || !$parent->student_id) {
            return redirect()->back()->with('error', 'Data parent atau siswa tidak ditemukan');
        }

        // Get student data
        $student = \App\Models\Student::with(['user', 'classes'])->find($parent->student_id);
        
        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
        }

        $studentClass = $student->classes->first();
        
        // Get attendance statistics for the student
        $attendanceStats = $this->getStudentAttendanceStats($student->id);
        
        // Get recent attendance for the student
        $recentAttendance = DB::table('attendance')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Get today's attendance for the student
        $todayAttendance = DB::table('attendance')
            ->where('student_id', $student->id)
            ->whereDate('date', Carbon::today())
            ->first();

        // Get chart data for the student
        $chartData = $this->getStudentChartData($student->id);
        $donutData = $this->getStudentDonutData($student->id);

        // Get student's schedule for the active semester
        $activeSemester = (object) [
            'academic_year' => AcademicYearHelper::getCurrentAcademicYear(),
            'semester_type' => AcademicYearHelper::getCurrentSemester(),
            'display_name' => 'Semester ' . ucfirst(AcademicYearHelper::getCurrentSemester()) . ' ' . AcademicYearHelper::getCurrentAcademicYear(),
        ];
        $schedules = [];
        if ($studentClass) {
            $query = \App\Models\Schedule::with('subject', 'teacher')
                ->where('class_id', $studentClass->id)
                ->where('academic_year', $activeSemester->academic_year)
                ->where('semester', $activeSemester->semester_type);

            $schedules = $query->orderBy('day')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');

            $dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
            $orderedSchedules = [];
            
            foreach ($dayOrder as $day) {
                if (isset($schedules[$day])) {
                    $orderedSchedules[$day] = $schedules[$day];
                }
            }
        }

        return view('dashboard.parent', compact(
            'student',
            'studentClass',
            'activeSemester',
            'attendanceStats',
            'recentAttendance',
            'todayAttendance',
            'orderedSchedules',
            'chartData',
            'donutData'
        ));
    }

    public function parentAttendance(Request $request)
    {
        // Get parent data
        $parent = \App\Models\ParentModel::where('user_id', Auth::id())->first();
        
        if (!$parent || !$parent->student_id) {
            return redirect()->back()->with('error', 'Data parent atau siswa tidak ditemukan');
        }

        // Get student data
        $student = \App\Models\Student::with(['user', 'classes'])->find($parent->student_id);
        
        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
        }

        $studentClass = $student->classes->first();
        
        // Get attendance statistics for the student
        $attendanceStats = $this->getStudentAttendanceStats($student->id);

        // Build attendance query with filters
        $query = DB::table('attendance')
            ->where('student_id', $student->id);

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('check_in_status', $request->status);
        }

        // Order and paginate
        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        return view('dashboard.parent-attendance', compact(
            'student',
            'studentClass',
            'attendanceStats',
            'attendances'
        ));
    }

    public function parentSchedule(Request $request)
    {
        // Get parent data
        $parent = \App\Models\ParentModel::where('user_id', Auth::id())->first();
        
        if (!$parent || !$parent->student_id) {
            return redirect()->back()->with('error', 'Data parent atau siswa tidak ditemukan');
        }

        // Get student data
        $student = \App\Models\Student::with(['user', 'classes'])->find($parent->student_id);
        
        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
        }

        $studentClass = $student->classes->first();
        
        if (!$studentClass) {
            return redirect()->back()->with('error', 'Siswa belum memiliki kelas');
        }

        // Get student's schedule for the active semester
        $activeSemester = (object) [
            'academic_year' => AcademicYearHelper::getCurrentAcademicYear(),
            'semester_type' => AcademicYearHelper::getCurrentSemester(),
            'display_name' => 'Semester ' . ucfirst(AcademicYearHelper::getCurrentSemester()) . ' ' . AcademicYearHelper::getCurrentAcademicYear(),
        ];
        $schedules = \App\Models\Schedule::with('subject', 'teacher')
            ->where('class_id', $studentClass->id)
            ->where('academic_year', $activeSemester->academic_year)
            ->where('semester', $activeSemester->semester_type)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        $dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        $orderedSchedules = [];
        
        foreach ($dayOrder as $day) {
            if (isset($schedules[$day])) {
                $orderedSchedules[$day] = $schedules[$day];
            }
        }

        // Get selected month from request, default to current month
        $selectedMonth = $request->input('month', \Carbon\Carbon::now()->format('Y-m'));

        // Get attendance for each subject for the selected month
        $subjectAttendance = [];
        
        // Get all attendance records for this student for the selected month
        $allAttendance = DB::table('attendance')
            ->where('student_id', $student->id)
            ->whereYear('date', \Carbon\Carbon::parse($selectedMonth)->year)
            ->whereMonth('date', \Carbon\Carbon::parse($selectedMonth)->month)
            ->orderBy('date')
            ->get();
        
        // Organize by subject with schedule-based meetings
        foreach ($orderedSchedules as $day => $daySchedules) {
            foreach ($daySchedules as $schedule) {
                $subjectId = $schedule->subject_id;
                
                if (!isset($subjectAttendance[$subjectId])) {
                    $subjectAttendance[$subjectId] = [
                        'subject_name' => $schedule->subject->name,
                        'teacher_name' => $schedule->teacher->name,
                        'day' => $schedule->day,
                        'meetings' => []
                    ];
                }
                
                // Find attendance records that match the schedule day
                foreach ($allAttendance as $attendance) {
                    $attendanceDay = \Carbon\Carbon::parse($attendance->date)->format('l');
                    $dayName = strtolower($attendanceDay);
                    
                    // Map English day names to Indonesian
                    $dayMap = [
                        'monday' => 'senin',
                        'tuesday' => 'selasa',
                        'wednesday' => 'rabu',
                        'thursday' => 'kamis',
                        'friday' => 'jumat',
                        'saturday' => 'sabtu',
                        'sunday' => 'minggu'
                    ];
                    
                    if (isset($dayMap[$dayName]) && $dayMap[$dayName] == $schedule->day) {
                        $subjectAttendance[$subjectId]['meetings'][] = [
                            'date' => $attendance->date,
                            'check_in' => $attendance->check_in,
                            'check_out' => $attendance->check_out,
                            'check_in_status' => $attendance->check_in_status,
                            'check_out_status' => $attendance->check_out_status
                        ];
                    }
                }
            }
        }

        // Get available months for filter
        $availableMonths = DB::table('attendance')
            ->where('student_id', $student->id)
            ->selectRaw('DISTINCT DATE_FORMAT(date, "%Y-%m") as month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        // Get chart data for the student
        $chartData = $this->getStudentChartData($student->id);
        $donutData = $this->getStudentDonutData($student->id);

        return view('dashboard.parent-schedule', compact(
            'student',
            'studentClass',
            'activeSemester',
            'orderedSchedules',
            'subjectAttendance',
            'selectedMonth',
            'availableMonths',
            'chartData',
            'donutData'
        ));
    }

    private function getStudentChartData($studentId)
    {
        $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        return $this->getStudentChartDataForDateRange($studentId, $startDate, $endDate);
    }

    private function getStudentChartDataForDateRange($studentId, $startDate, $endDate)
    {
        $data = [
            'months' => [],
            'lateCount' => [],
            'onTimeCount' => []
        ];

        $start = $startDate->copy()->startOfMonth();
        $end = $endDate->copy()->endOfMonth();
        $totalMonths = intval($start->diffInMonths($end)) + 1;

        for ($i = 0; $i < $totalMonths; $i++) {
            $month = $start->copy()->addMonths($i);
            $monthName = $month->format('M');

            $lateCount = DB::table('attendance')
                ->where('student_id', $studentId)
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('check_in_status', 'terlambat')
                ->count();

            $onTimeCount = DB::table('attendance')
                ->where('student_id', $studentId)
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

    private function getStudentDonutData($studentId)
    {
        $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        return $this->getStudentDonutDataForDateRange($studentId, $startDate, $endDate);
    }

    private function getStudentDonutDataForDateRange($studentId, $startDate, $endDate)
    {
        $statuses = ['tepat', 'terlambat', 'izin', 'sakit', 'alpha'];
        $labels = [
            __('index.on_time'),
            __('index.late'),
            __('index.permission'),
            __('index.sick'),
            __('index.absent'),
        ];

        $counts = [];
        foreach ($statuses as $status) {
            $counts[] = DB::table('attendance')
                ->where('student_id', $studentId)
                ->whereBetween('date', [$startDate, $endDate])
                ->where('check_in_status', $status)
                ->count();
        }

        $total = array_sum($counts);
        $percentages = [];
        if ($total > 0) {
            foreach ($counts as $count) {
                $percentages[] = round($count / $total * 100);
            }

            $diff = 100 - array_sum($percentages);
            if ($diff !== 0) {
                $maxIndex = array_search(max($percentages), $percentages);
                $percentages[$maxIndex] += $diff;
            }
        } else {
            $percentages = [0, 0, 0, 0, 0];
        }

        return [
            'labels' => $labels,
            'data' => $percentages,
            'counts' => $counts
        ];
    }

    public function parentChartData(Request $request)
    {
        $parent = \App\Models\ParentModel::where('user_id', Auth::id())->first();

        if (!$parent || !$parent->student_id) {
            return response()->json(['error' => 'Data parent atau siswa tidak ditemukan'], 403);
        }

        $student = \App\Models\Student::find($parent->student_id);

        if (!$student) {
            return response()->json(['error' => 'Data siswa tidak ditemukan'], 404);
        }

        $period = $request->get('period', '6m');
        $endDate = Carbon::now()->endOfMonth();

        switch ($period) {
            case '1m':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case '1y':
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                break;
            default:
                $startDate = Carbon::now()->subMonths(5)->startOfMonth();
                $period = '6m';
        }

        $data = $this->getStudentChartDataForDateRange($student->id, $startDate, $endDate);
        $data['donut'] = $this->getStudentDonutDataForDateRange($student->id, $startDate, $endDate);

        return response()->json($data);
    }

    private function getStudentAttendanceStats($studentId)
    {
        $attendances = DB::table('attendance')->where('student_id', $studentId)->get();
        
        $total = $attendances->count();
        $hadir = $attendances->where('check_in_status', 'tepat')->count();
        $terlambat = $attendances->where('check_in_status', 'terlambat')->count();
        $izin = $attendances->where('check_in_status', 'izin')->count();
        $sakit = $attendances->where('check_in_status', 'sakit')->count();
        $alpha = $attendances->where('check_in_status', 'alpha')->count();
        
        $presentPercentage = $total > 0 ? round((($hadir + $terlambat) / $total) * 100, 2) : 0;
        
        return [
            'total' => $total,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
            'present_percentage' => $presentPercentage,
        ];
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
        $endDate = Carbon::now()->endOfMonth();
        
        switch ($period) {
            case '1m':
                $data = $this->getOneMonthData();
                $startDate = Carbon::now()->startOfMonth();
                break;
            case '6m':
                $data = $this->getSixMonthsData();
                $startDate = Carbon::now()->subMonths(5)->startOfMonth();
                break;
            case 'all':
                $data = $this->getAllData();
                $firstRecord = DB::table('attendance')->orderBy('date')->first();
                $startDate = $firstRecord ? Carbon::parse($firstRecord->date)->startOfMonth() : Carbon::now()->startOfMonth();
                break;
            default: // 1y
                $data = $this->getLateStatistics();
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                break;
        }
        
        $data['donut'] = $this->getDonutStatisticsForDateRange($startDate, $endDate);
        
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
        
        [$data['onTimePercentage'], $data['latePercentage']] = $this->calculatePercentages($data['onTimeCount'], $data['lateCount']);
        
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
        
        [$data['onTimePercentage'], $data['latePercentage']] = $this->calculatePercentages($data['onTimeCount'], $data['lateCount']);
        
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
        
        [$data['onTimePercentage'], $data['latePercentage']] = $this->calculatePercentages($data['onTimeCount'], $data['lateCount']);
        
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
        
        [$data['onTimePercentage'], $data['latePercentage']] = $this->calculatePercentages($data['onTimeCount'], $data['lateCount']);
        
        // Debug: Log the data
        \Log::info('Late Statistics Data: ' . json_encode($data));
        
        return $data;
    }

    private function getDonutStatistics()
    {
        // Data 1 bulan terakhir
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        return $this->getDonutStatisticsForDateRange($startDate, $endDate);
    }

    private function calculatePercentages($onTimeCount, $lateCount)
    {
        $onTimePercentage = [];
        $latePercentage = [];
        
        for ($i = 0; $i < count($onTimeCount); $i++) {
            $total = $onTimeCount[$i] + $lateCount[$i];
            if ($total > 0) {
                $onTimePercentage[] = round($onTimeCount[$i] / $total * 100);
                $latePercentage[] = 100 - $onTimePercentage[$i];
            } else {
                $onTimePercentage[] = 0;
                $latePercentage[] = 0;
            }
        }
        
        return [$onTimePercentage, $latePercentage];
    }

    private function getDonutStatisticsForDateRange($startDate, $endDate)
    {
        $statuses = ['tepat', 'terlambat', 'izin', 'sakit', 'alpha'];
        $labels = [
            __('index.on_time'),
            __('index.late'),
            __('index.permission'),
            __('index.sick'),
            __('index.absent'),
        ];

        $counts = [];
        foreach ($statuses as $status) {
            $counts[] = DB::table('attendance')
                ->whereBetween('date', [$startDate, $endDate])
                ->where('check_in_status', $status)
                ->count();
        }

        // Convert to percentages so total becomes 100%
        $total = array_sum($counts);
        $percentages = [];
        if ($total > 0) {
            foreach ($counts as $count) {
                $percentages[] = round($count / $total * 100);
            }

            // Adjust largest slice to ensure the sum is exactly 100
            $diff = 100 - array_sum($percentages);
            if ($diff !== 0) {
                $maxIndex = array_search(max($percentages), $percentages);
                $percentages[$maxIndex] += $diff;
            }
        } else {
            $percentages = [0, 0, 0, 0, 0];
        }

        return [
            'labels' => $labels,
            'data' => $percentages,
            'counts' => $counts
        ];
    }
}
