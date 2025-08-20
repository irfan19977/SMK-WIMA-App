<?php

use App\Http\Controllers\API\RFIDController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaceRecognitionController;
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
use App\Http\Controllers\StudentGradesController;
use App\Models\Student;
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

    // Student Grades Routes - CUSTOM ROUTES MUST COME FIRST!
    Route::get('student-grades/get-students', [StudentGradesController::class, 'getStudents'])
        ->name('student-grades.get-students');
    
    Route::get('student-grades/get-grades', [StudentGradesController::class, 'getGrades'])
        ->name('student-grades.get-grades');
    
    Route::post('student-grades/bulk-update', [StudentGradesController::class, 'bulkUpdate'])
        ->name('student-grades.bulk-update');
    
    Route::get('student-grades/statistics', [StudentGradesController::class, 'getStatistics'])
        ->name('student-grades.statistics');
    Route::get('student-grades/get-subjects-by-class', [StudentGradesController::class, 'getSubjectsByClass'])->name('student-grades.get-subjects-by-class');

    // Resource routes AFTER custom routes
    Route::resource('student-grades', StudentGradesController::class);
    
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

    Route::resource('roles', RoleController::class);

    // Face Recognition Routes
    Route::prefix('face-recognition')->group(function () {
        Route::get('/', [FaceRecognitionController::class, 'index'])->name('face-recognition.index');
        Route::get('/create', [FaceRecognitionController::class, 'create'])
            ->name('face-recognition.create');
            Route::post('/store', [FaceRecognitionController::class, 'store'])->name('face-recognition.store');
        Route::get('/Student', [FaceRecognitionController::class, 'getRegisteredStudents'])->name('face-recognition.students');
        Route::post('/record-attendance', [FaceRecognitionController::class, 'recordAttendance'])
            ->name('face-recognition.record-attendance');
        Route::post('/identify', [FaceRecognitionController::class, 'identifyFace'])->name('face-recognition.identify');

        // Route untuk attendance otomatis via face recognition
        Route::post('/auto-attendance', [FaceRecognitionController::class, 'autoAttendance'])->name('auto-attendance');
        Route::get('/students/{studentId}/class', [FaceRecognitionController::class, 'getStudentClass'])->name('student-class');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
