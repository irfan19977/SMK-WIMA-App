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
                'student.name as student_name',
                'student.nisn',
                'classes.name as class_name',
                'attendance.check_in',
                'attendance.date',
                'attendance.check_in_status',
                'users.email'
            )
            ->orderBy('attendance.check_in', 'desc')
            ->get();

        return view('dashboard.index', compact('lateStudents', 'date'));
    }
}