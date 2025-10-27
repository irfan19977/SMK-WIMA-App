<?php

use App\Http\Controllers\API\RFIDController;
use App\Http\Controllers\Backend\AsramaController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\ClassesController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EkstrakurikulerController;
use App\Http\Controllers\Backend\FaceRecognitionController;
use App\Http\Controllers\Backend\LessonAttendanceController;
use App\Http\Controllers\Backend\ParentsController;
use App\Http\Controllers\Frontend\PendaftaranController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RegistrationController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\SettingScheduleController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\SubjectController;
use App\Http\Controllers\Backend\TeacherController;
use App\Http\Controllers\Backend\PendaftaranSiswaController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StudentGradesController;
use App\Http\Controllers\Backend\TahfizController;
use App\Models\Ekstrakurikuler;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
})->name('/');
Route::get('/profile-sekolah', function () {
    return view('home.profile');
})->name('profile-sekolah.index');

Route::get('/teknik-komputer-jaringan', function () {
    return view('home.tkj');
})->name('tkj.index');

Route::get('/teknik-kendaraan-ringan', function () {
    return view('home.tkr');
})->name('tkr.index');

Route::get('/kimia-industri', function () {
    return view('home.kimia');
})->name('kimia.index');

Route::get('/teknik-bisnis-sepeda-motor', function () {
    return view('home.tbsm');
})->name('tbsm.index');

Route::get('/berita', function () {
    return view('home.berita');
})->name('berita.index');

Route::get('/detail', function () {
    return view('home.detail');
})->name('berita.detail');

Route::get('/contact', function () {
    return view('home.contact');
})->name('contact.index');

Route::resource('/pendaftaran', PendaftaranController::class);

    Route::post('/rfid-detect', [RFIDController::class, 'detectRFID']);
    Route::get('/get-latest-rfid', [RFIDController::class, 'getLatestRFID'])->name('get.latest.rfid');
    Route::post('/clear-rfid-cache', [RFIDController::class, 'clearRFIDCache'])->name('clear.rfid');

Route::group(['middleware' => 'auth'], function () {

    // Dashboard Routes
    Route::prefix('dashboard')->name('dashboard')->group(function() {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/attendance-data', [DashboardController::class, 'getAttendanceData'])->name('.attendance-data');
    });

    Route::get('pendaftaran-siswa/export', [PendaftaranSiswaController::class, 'export'])->name('pendaftaran-siswa.export');
    Route::get('pendaftaran-siswa/export-excel', [PendaftaranSiswaController::class, 'exportExcel'])->name('pendaftaran-siswa.export-excel');
    Route::get('pendaftaran-siswa/print', [PendaftaranSiswaController::class, 'print'])->name('pendaftaran-siswa.print');
    Route::post('pendaftaran-siswa/{pendaftaran_siswa}/accept', [PendaftaranSiswaController::class, 'accept'])->name('pendaftaran-siswa.accept');
    Route::resource('pendaftaran-siswa', PendaftaranSiswaController::class);

    // Student Management Routes
    Route::prefix('students')->name('students.')->group(function() {
        Route::post('{userId}/toggle-active', [StudentController::class, 'toggleActive'])->name('toggle-active');
        Route::post('recognize-face', [StudentController::class, 'recognizeFace'])->name('recognize-face');
        Route::resource('/', StudentController::class)->parameters(['' => 'student']);
    });

    // Teacher Management Routes
    Route::prefix('teachers')->name('teachers.')->group(function() {
        Route::resource('/', TeacherController::class)->parameters(['' => 'teachers']);
        Route::post('/{teacherId}/toggle-active', [TeacherController::class, 'toggleActive'])->name('toggle-active');
    });
    
    // Parent Management Routes
    Route::prefix('parents')->name('parents.')->group(function() {
        Route::resource('/', ParentsController::class)->parameters(['']);
        Route::post('/{parentId}/toggle-active', [ParentsController::class, 'toggleActive'])->name('toggle-active');
    });

    // Class Management Routes
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/search', [ClassesController::class, 'search'])->name('search');
        Route::resource('/', ClassesController::class)->parameters(['' => 'class']);
        Route::post('{class}/assign-student', [ClassesController::class, 'assignStudent'])->name('assign-student');
        Route::post('{class}/bulk-assign', [ClassesController::class, 'bulkAssign'])->name('bulk-assign');
        Route::delete('{class}/remove-student', [ClassesController::class, 'removeStudent'])->name('remove-student');
        Route::get('{class}/attendance-data', [ClassesController::class, 'getAttendanceData'])->name('attendance-data');
    });

    // Student Grades Routes
    Route::prefix('student-grades')->name('student-grades.')->group(function () {
        Route::get('get-students', [StudentGradesController::class, 'getStudents'])->name('get-students');        
        Route::get('get-grades', [StudentGradesController::class, 'getGrades'])->name('get-grades');        
        Route::post('bulk-update', [StudentGradesController::class, 'bulkUpdate'])->name('bulk-update');        
        Route::get('statistics', [StudentGradesController::class, 'getStatistics'])->name('statistics');            
        Route::get('get-subjects-by-class', [StudentGradesController::class, 'getSubjectsByClass'])->name('get-subjects-by-class');
        Route::resource('/', StudentGradesController::class);
    });

    // Tahfiz Management Routes
    Route::prefix('tahfiz')->name('tahfiz.')->group(function () {
        Route::get('get-students', [TahfizController::class, 'getStudents'])->name('getStudents');
        Route::get('get-tahfiz-records', [TahfizController::class, 'getTahfizRecords'])->name('getTahfizRecords');
        Route::post('bulk-update', [TahfizController::class, 'bulkUpdate'])->name('bulkUpdate');
        Route::get('statistics', [TahfizController::class, 'getStatistics'])->name('getStatistics');
        Route::resource('/', TahfizController::class);
    });

    // Asrama Management Routes
    Route::prefix('asrama')->name('asrama.')->group(function() {
        Route::resource('/', AsramaController::class)->parameters(['' => 'asrama']);
        Route::post('/{asrama}/bulk-assign', [AsramaController::class, 'bulkAssign'])->name('bulk-assign'); 
        Route::delete('/{asrama}/remove-student', [AsramaController::class, 'removeStudent'])->name('remove-student'); 
        Route::get('/{asrama}/grades', [AsramaController::class, 'getGrades'])->name('get-grades');     
        Route::post('/grades', [AsramaController::class, 'storeGrade'])->name('store-grade');     
        Route::put('/grades/{grade}', [AsramaController::class, 'updateGrade'])->name('update-grade');     
        Route::delete('/grades/{grade}', [AsramaController::class, 'deleteGrade'])->name('delete-grade');     
        Route::post('/grades/bulk-update', [AsramaController::class, 'bulkUpdateGrades'])->name('bulk-update-grades');
    });
    
    // Schedule Management Routes
    Route::prefix('schedules')->name('schedules.')->group(function() {
        Route::resource('/', ScheduleController::class);
        Route::get('/class/{classId}', [ScheduleController::class, 'getSchedulesByClass'])->name('by-class');
    });

    // Attendance Management Routes
    Route::prefix('attendances')-> name('attendances.')->group(function() {
        Route::resource('/', AttendanceController::class);
        Route::get('/find-by-nisn/{nisn}', [AttendanceController::class, 'findByNisn']);
    });
    
    // Lesson Attendance Routes
    Route::prefix('lesson-attendances')->name('lesson-attendances.')->group(function() {
        Route::resource('/', LessonAttendanceController::class)->except(['create', 'edit', 'show']);
    
        // Additional routes for AJAX calls
        Route::get('/get-subjects-by-class', [LessonAttendanceController::class, 'getSubjectsByClass'])->name('get-subjects-by-class');
        Route::get('/get-students', [LessonAttendanceController::class, 'getStudents'])->name('get-students');
        Route::get('/get-attendance', [LessonAttendanceController::class, 'getAttendance'])->name('get-attendance');
        Route::post('/bulk-update', [LessonAttendanceController::class, 'bulkUpdate'])->name('bulk-update');
    });

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

    Route::prefix('ekstrakurikuler')->name('ekstrakurikuler.')->group(function() {
        Route::resource('/', EkstrakurikulerController::class)->parameters(['' => 'ekstrakurikuler']);
        Route::post('/{ekstrakurikuler}/bulk-assign', [EkstrakurikulerController::class, 'bulkAssign'])->name('bulk-assign'); 
        Route::delete('/{ekstrakurikuler}/remove-student', [EkstrakurikulerController::class, 'removeStudent'])->name('remove-student'); 
        Route::get('/{ekstrakurikuler}/grades', [EkstrakurikulerController::class, 'getGrades'])->name('get-grades');     
        Route::post('/grades', [EkstrakurikulerController::class, 'storeGrade'])->name('store-grade');     
        Route::put('/grades/{grade}', [EkstrakurikulerController::class, 'updateGrade'])->name('update-grade');     
        Route::delete('/grades/{grade}', [EkstrakurikulerController::class, 'deleteGrade'])->name('delete-grade');     
        Route::post('/grades/bulk-update', [EkstrakurikulerController::class, 'bulkUpdateGrades'])->name('bulk-update-grades');

    });
    Route::resource('subjects', SubjectController::class);
    Route::resource('setting-schedule', SettingScheduleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';