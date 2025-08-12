<?php

use App\Http\Controllers\API\RFIDController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonAttendanceController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
})->name('/');
Route::get('/profile-sekolah', function () {
    return view('home.profile');
})->name('profile-sekolah.index');

Route::get('/berita', function () {
    return view('home.berita');
})->name('berita.index');

Route::get('/detail', function () {
    return view('home.detail');
})->name('berita.detail');

Route::get('/pendaftaran', function () {
    return view('home.pendaftaran');
})->name('pendaftaran');

    Route::post('/rfid-detect', [RFIDController::class, 'detectRFID']);
    Route::get('/get-latest-rfid', [RFIDController::class, 'getLatestRFID'])->name('get.latest.rfid');
    Route::post('/clear-rfid-cache', [RFIDController::class, 'clearRFIDCache'])->name('clear.rfid');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/attendance-data', [DashboardController::class, 'getAttendanceData'])->name('dashboard.attendance-data');
    
    Route::resource('students', StudentController::class);
    Route::post('/students/{userId}/toggle-active', [StudentController::class, 'toggleActive'])->name('students.toggle-active');
    Route::post('students/recognize-face', [StudentController::class, 'recognizeFace'])->name('students.recognize-face');

    Route::resource('teachers', TeacherController::class);
    Route::post('/teachers/{teacherId}/toggle-active', [TeacherController::class, 'toggleActive'])->name('teachers.toggle-active');
    
    Route::resource('parents', ParentsController::class);
    Route::post('/parents/{parentId}/toggle-active', [ParentsController::class, 'toggleActive'])->name('parents.toggle-active');

    Route::resource('classes', ClassesController::class);
    Route::post('/classes/{class}/assign-student', [ClassesController::class, 'assignStudent'])->name('classes.assign-student');
    Route::post('/classes/{class}/bulk-assign', [ClassesController::class, 'bulkAssign'])->name('classes.bulk-assign');
    Route::delete('classes/{class}/remove-student', [ClassesController::class, 'removeStudent'])->name('classes.remove-student');
    Route::get('/classes/{class}/attendance-data', [ClassesController::class, 'getAttendanceData'])->name('classes.attendance-data');

    Route::resource('subjects', SubjectController::class);
    
    Route::resource('schedules', ScheduleController::class);
    Route::get('schedules/class/{classId}', [ScheduleController::class, 'getSchedulesByClass'])->name('schedules.by-class');

    Route::resource('setting-schedule', SettingScheduleController::class);

    Route::resource('attendances', AttendanceController::class);
    Route::get('/students/find-by-nisn/{nisn}', [AttendanceController::class, 'findByNisn']);
    
    Route::resource('lesson-attendances', LessonAttendanceController::class);
    // Additional routes for lesson attendance
    Route::get('lesson-attendances/find-by-nisn/{nisn}', [LessonAttendanceController::class, 'findByNisn'])
        ->name('lesson-attendances.find-by-nisn');
    
    Route::get('lesson-attendances/get-subjects-by-class/{classId}', [LessonAttendanceController::class, 'getSubjectsByClass'])
        ->name('lesson-attendances.get-subjects-by-class');

    Route::resource('permissions', PermissionController::class);

    // Route baru untuk face recognition

    Route::resource('roles', RoleController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
