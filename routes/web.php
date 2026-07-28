<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// Backend Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScreenShareController;
use App\Http\Controllers\WebRTCController;
use App\Http\Controllers\API\RFIDController;
use App\Http\Controllers\Backend\AcademicReportController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\ClassesController;
use App\Http\Controllers\Backend\ExamController;
use App\Http\Controllers\Backend\FaceRecognitionController;
use App\Http\Controllers\Backend\GalleryController;
use App\Http\Controllers\Backend\LessonAttendanceController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\ParentsController;
use App\Http\Controllers\Backend\PendaftaranSiswaController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\SemesterController;
use App\Http\Controllers\Backend\SettingScheduleController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\StudentGradesController;
use App\Http\Controllers\Backend\StudentPermissionController;
use App\Http\Controllers\Backend\SubjectController;
use App\Http\Controllers\Backend\TeacherController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserPreferenceController;
use App\Http\Controllers\Backend\WaNotificationController;

// Frontend Controllers
use App\Http\Controllers\Frontend\BeritaController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\PendaftaranController;
use App\Http\Controllers\Frontend\QrCodeController;
use App\Http\Controllers\Frontend\StudentExamController;

// Models
use App\Models\Gallery;
use App\Models\News;

/*
|--------------------------------------------------------------------------
| Language Routes
|--------------------------------------------------------------------------
*/

Route::get('language/{lang}', function ($lang) {
    if (!in_array($lang, ['id', 'en'])) {
        $lang = 'id';
    }

    session(['locale' => $lang]);

    if (auth()->check()) {
        $user = auth()->user();
        $user->language = $lang;
        $user->save();
    }

    app()->setLocale($lang);

    return redirect()->back()->with('language_changed', __('index.language_changed'));
})->name('language.switch');

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/

Route::middleware(['frontend'])->group(function () {

    // Homepage
    Route::get('/', function () {
        $featuredNews = News::published()->latest('published_at')->first();
        $latestNews = News::published()->latest('published_at')->skip(1)->take(3)->get();
        $categoriesNews = News::published()->latest('published_at')->where('category', 'Kegiatan')->take(3)->get();
        $homepageNews = News::published()->latest('published_at')->take(4)->get();

        return view('home.index', compact('featuredNews', 'latestNews', 'categoriesNews', 'homepageNews'));
    })->name('/');

    // Berita
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/kategori/{category}', [BeritaController::class, 'byCategory'])->name('berita.category');
    Route::get('/berita/tag/{tag}', [BeritaController::class, 'byTag'])->name('berita.tag');
    Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // About
    Route::get('/about', function () {
        return view('home.profile');
    })->name('about');

    // Jurusan & Galeri
    Route::get('/teknik-komputer-jaringan', function () {
        $galleries = Gallery::where('jurusan', 'Teknik Komputer dan Jaringan')->latest()->take(3)->get();
        return view('home.tkj', compact('galleries'));
    })->name('tkj.index');

    Route::get('/galeri/teknik-komputer-jaringan', function () {
        $jurusan = 'Teknik Komputer dan Jaringan';
        $galleries = Gallery::where('jurusan', $jurusan)->latest()->paginate(12);
        return view('home.gallery', compact('galleries', 'jurusan'));
    })->name('gallery.tkj');

    Route::get('/teknik-kendaraan-ringan', function () {
        $galleries = Gallery::where('jurusan', 'Teknik Kendaraan Ringan')->latest()->take(8)->get();
        return view('home.tkr', compact('galleries'));
    })->name('tkr.index');

    Route::get('/galeri/teknik-kendaraan-ringan', function () {
        $jurusan = 'Teknik Kendaraan Ringan';
        $galleries = Gallery::where('jurusan', $jurusan)->latest()->paginate(12);
        return view('home.gallery', compact('galleries', 'jurusan'));
    })->name('gallery.tkr');

    Route::get('/kimia-industri', function () {
        $galleries = Gallery::where('jurusan', 'Teknik Kimia Industri')->latest()->take(8)->get();
        return view('home.kimia', compact('galleries'));
    })->name('kimia.index');

    Route::get('/galeri/kimia-industri', function () {
        $jurusan = 'Teknik Kimia Industri';
        $galleries = Gallery::where('jurusan', $jurusan)->latest()->paginate(12);
        return view('home.gallery', compact('galleries', 'jurusan'));
    })->name('gallery.kimia');

    Route::get('/teknik-bisnis-sepeda-motor', function () {
        $galleries = Gallery::where('jurusan', 'Teknik Bisnis Sepeda Motor')->latest()->take(8)->get();
        return view('home.tbsm', compact('galleries'));
    })->name('tbsm.index');

    Route::get('/galeri/teknik-bisnis-sepeda-motor', function () {
        $jurusan = 'Teknik Bisnis Sepeda Motor';
        $galleries = Gallery::where('jurusan', $jurusan)->latest()->paginate(12);
        return view('home.gallery', compact('galleries', 'jurusan'));
    })->name('gallery.tbsm');

    // QR Code Generator
    Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode.index');
    Route::match(['get', 'post'], '/qrcode/generate', [QrCodeController::class, 'generate'])->name('qrcode.generate');

    // Pendaftaran
    Route::resource('/pendaftaran', PendaftaranController::class);
});

/*
|--------------------------------------------------------------------------
| Public API Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

// RFID
Route::post('/rfid/detect', [RFIDController::class, 'detect']);
Route::get('/clear-rfid-cache', [RFIDController::class, 'clearCache']);
Route::get('/get-latest-rfid', [RFIDController::class, 'getLatest']);
Route::post('/rfid/auto-attendance', [RFIDController::class, 'autoAttendance']);

// WebRTC Signaling (outside auth for WebRTC to work)
Route::prefix('screen-sharing')->group(function () {
    Route::post('/signal', [WebRTCController::class, 'signal'])->name('webrtc.signal');
    Route::get('/signal/{screenShareId}', [WebRTCController::class, 'poll'])->name('webrtc.poll');
    Route::get('/signal-responses/{screenShareId}', [WebRTCController::class, 'getResponses'])->name('webrtc.responses');
    Route::get('/events/{screenShareId}', [WebRTCController::class, 'events'])->name('webrtc.events');
});

// Polling: Check new attendance
Route::get('/check-new-attendance', function () {
    static $lastChecked = null;
    $currentTime = now();

    $attendances = \App\Models\Attendance::with(['student', 'class'])
        ->where('created_at', '>=', $currentTime->subSeconds(10))
        ->orderBy('created_at', 'desc')
        ->first();

    if ($attendances && (!$lastChecked || $attendances->created_at->greaterThan($lastChecked))) {
        $lastChecked = $attendances->created_at;

        return response()->json([
            'has_new' => true,
            'attendance' => [
                'attendance_id' => $attendances->id,
                'student_name' => $attendances->student->name ?? 'Unknown',
                'nisn' => $attendances->student->nisn ?? '-',
                'class_name' => $attendances->class->name ?? 'Unknown',
                'check_in_time' => $attendances->check_in,
                'check_in_status' => $attendances->check_in_status,
                'date' => $attendances->date,
                'status_text' => $attendances->check_in_status === 'tepat' ? 'Tepat Waktu' : 'Terlambat',
            ],
        ]);
    }

    return response()->json(['has_new' => false]);
});

// Polling: Check new lesson attendance
Route::get('/check-new-lesson-attendance', function () {
    $currentTime = now();

    $lessonAttendance = \App\Models\LessonAttendance::with(['student', 'subject'])
        ->where('created_at', '>=', $currentTime->subSeconds(10))
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'desc')
        ->first();

    if ($lessonAttendance) {
        $studentClass = \App\Models\StudentClass::join('classes', 'student_class.class_id', '=', 'classes.id')
            ->where('student_class.student_id', $lessonAttendance->student_id)
            ->where('student_class.status', 'active')
            ->whereNull('student_class.deleted_at')
            ->select('classes.id as class_id', 'classes.name as class_name')
            ->first();

        return response()->json([
            'has_new' => true,
            'attendance' => [
                'id' => $lessonAttendance->id,
                'student_name' => $lessonAttendance->student->name ?? 'Unknown',
                'student_nisn' => $lessonAttendance->student->nisn ?? '-',
                'user_id' => $lessonAttendance->student->user_id ?? null,
                'class_id' => $studentClass->class_id ?? null,
                'class_name' => $studentClass->class_name ?? 'Unknown',
                'subject_name' => $lessonAttendance->subject->name ?? 'Unknown',
                'check_in' => $lessonAttendance->check_in ? \Carbon\Carbon::parse($lessonAttendance->check_in)->format('H:i') : '-',
                'status' => $lessonAttendance->check_in_status,
                'date' => $lessonAttendance->date,
            ],
        ]);
    }

    return response()->json(['has_new' => false]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::post('{userId}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
        Route::resource('/', UserController::class)->parameters(['' => 'user']);
    });

    // User Preferences
    Route::prefix('preferences')->name('preferences.')->group(function () {
        Route::get('/', [UserPreferenceController::class, 'get'])->name('get');
        Route::post('/', [UserPreferenceController::class, 'update'])->name('update');
    });

    // Dashboard
    Route::prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/attendance-data', [DashboardController::class, 'getAttendanceData'])->name('.attendance-data');
        Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('.chart-data');
        Route::get('/parent-chart-data', [DashboardController::class, 'parentChartData'])->name('.parent-chart-data');
        Route::get('/student-chart-data', [DashboardController::class, 'studentChartData'])->name('.student-chart-data');
    });

    // Parent Routes
    Route::get('/parent/attendance', [DashboardController::class, 'parentAttendance'])->name('parent.attendance');
    Route::get('/parent/attendance/export', [DashboardController::class, 'parentAttendanceExport'])->name('parent.attendance.export');
    Route::get('/parent/schedule', [DashboardController::class, 'parentSchedule'])->name('parent.schedule');
    Route::get('/parent/lesson-attendance', [DashboardController::class, 'parentLessonAttendance'])->name('parent.lesson-attendance');

    // Student Routes
    Route::get('/student/attendance', [DashboardController::class, 'studentAttendance'])->name('student.attendance');
    Route::get('/student/attendance/export', [DashboardController::class, 'studentAttendanceExport'])->name('student.attendance.export');
    Route::get('/student/schedule', [DashboardController::class, 'studentSchedule'])->name('student.schedule');
    Route::get('/student/lesson-attendance', [DashboardController::class, 'studentLessonAttendance'])->name('student.lesson-attendance');

    // News Management
    Route::resource('news', NewsController::class);
    Route::post('news/upload-image', [NewsController::class, 'uploadImage'])->name('news.upload-image');

    // Galleries Management
    Route::resource('galleries', GalleryController::class);

    // Pendaftaran Siswa
    Route::prefix('pendaftaran-siswa')->name('pendaftaran-siswa.')->group(function () {
        Route::get('export', [PendaftaranSiswaController::class, 'export'])->name('export');
        Route::get('export-excel', [PendaftaranSiswaController::class, 'exportExcel'])->name('export-excel');
        Route::get('print', [PendaftaranSiswaController::class, 'print'])->name('print');
        Route::post('set-per-page', [PendaftaranSiswaController::class, 'setPerPage'])->name('set-per-page');
        Route::post('{pendaftaran_siswa}/accept', [PendaftaranSiswaController::class, 'accept'])->name('accept');
        Route::post('{pendaftaran_siswa}/reject', [PendaftaranSiswaController::class, 'reject'])->name('reject');
    });
    Route::resource('pendaftaran-siswa', PendaftaranSiswaController::class);

    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::post('{userId}/toggle-active', [StudentController::class, 'toggleActive'])->name('toggle-active');
        Route::post('recognize-face', [StudentController::class, 'recognizeFace'])->name('recognize-face');
        Route::get('search', [StudentController::class, 'search'])->name('search');
        Route::resource('/', StudentController::class)->parameters(['' => 'student']);
    });

    // Teacher Management
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::resource('/', TeacherController::class)->parameters(['' => 'teachers']);
        Route::post('/{teacherId}/toggle-active', [TeacherController::class, 'toggleActive'])->name('toggle-active');
    });

    // Parent Management
    Route::resource('parents', ParentsController::class);
    Route::post('/parents/{parentId}/toggle-active', [ParentsController::class, 'toggleActive'])->name('parents.toggle-active');

    // Class Management
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/search', [ClassesController::class, 'search'])->name('search');
        Route::get('/promote-data', [ClassesController::class, 'getPromoteData'])->name('promote-data');
        Route::post('/open-next-semester-bulk', [ClassesController::class, 'openNextSemesterBulk'])->name('open-next-semester-bulk');
        Route::post('/promote-bulk', [ClassesController::class, 'promoteStudentsBulk'])->name('promote-bulk');
        Route::get('/check-promotion-period', [ClassesController::class, 'checkPromotionPeriod'])->name('check-promotion-period');
        Route::get('/preview-promotion', [ClassesController::class, 'previewPromotion'])->name('preview-promotion');

        Route::resource('/', ClassesController::class)->parameters(['' => 'class']);

        Route::post('{class}/assign-student', [ClassesController::class, 'assignStudent'])->name('assign-student');
        Route::post('{class}/bulk-assign', [ClassesController::class, 'bulkAssign'])->name('bulk-assign');
        Route::delete('{class}/remove-student', [ClassesController::class, 'removeStudent'])->name('remove-student');
        Route::delete('remove-student/{studentId}', [ClassesController::class, 'removeStudentFromClass'])->name('remove-student-from-class');
        Route::get('{class}/attendance-data', [ClassesController::class, 'getAttendanceData'])->name('attendance-data');
        Route::post('{class}/toggle-archive', [ClassesController::class, 'toggleArchive'])->name('toggle-archive');
        Route::post('{class}/open-next-semester', [ClassesController::class, 'openNextSemester'])->name('open-next-semester');
        Route::get('{class}/export-attendance-excel', [ClassesController::class, 'exportAttendanceExcel'])->name('export-attendance-excel');
        Route::get('{class}/export-attendance-pdf', [ClassesController::class, 'exportAttendancePdf'])->name('export-attendance-pdf');
    });

    // Student Grades
    Route::prefix('student-grades')->name('student-grades.')->group(function () {
        Route::get('get-students', [StudentGradesController::class, 'getStudents'])->name('get-students');
        Route::get('get-grades', [StudentGradesController::class, 'getGrades'])->name('get-grades');
        Route::post('bulk-update', [StudentGradesController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('statistics', [StudentGradesController::class, 'getStatistics'])->name('statistics');
        Route::get('get-subjects-by-class', [StudentGradesController::class, 'getSubjectsByClass'])->name('get-subjects-by-class');
        Route::resource('/', StudentGradesController::class);
    });

    // Academic Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('academic', [AcademicReportController::class, 'index'])->name('academic');
        Route::get('academic/semester-data', [AcademicReportController::class, 'semesterData'])->name('academic.semester-data');
        Route::get('academic/all-subjects-data', [AcademicReportController::class, 'allSubjectsData'])->name('academic.all-subjects-data');
        Route::get('academic/export-pdf', [AcademicReportController::class, 'exportPdf'])->name('academic.export-pdf');
        Route::get('academic/export-excel', [AcademicReportController::class, 'exportExcel'])->name('academic.export-excel');
    });

    // Exam Management
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::resource('/', ExamController::class)->parameters(['' => 'exam']);
        Route::post('/{exam}/publish', [ExamController::class, 'publish'])->name('publish');
        Route::get('/subjects', [ExamController::class, 'getSubjects'])->name('subjects');
        Route::get('/classes', [ExamController::class, 'getClasses'])->name('classes');

        // Questions
        Route::get('/questions/create', [ExamController::class, 'createQuestion'])->name('questions.create');
        Route::post('/questions', [ExamController::class, 'storeQuestion'])->name('questions.store');
        Route::get('/questions/{question}/edit', [ExamController::class, 'editQuestion'])->name('questions.edit');
        Route::put('/questions/{question}', [ExamController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{question}', [ExamController::class, 'destroyQuestion'])->name('questions.destroy');

        // Submissions
        Route::get('/{exam}/submissions/{submission}', [ExamController::class, 'showSubmission'])->name('submissions.show');

        // API untuk form dinamis
        Route::get('/subjects-by-class/{classId}', [ExamController::class, 'getSubjectsByClass'])->name('subjects-by-class');
        Route::get('/teacher-by-schedule/{classId}/{subjectId}', [ExamController::class, 'getTeacherBySchedule'])->name('teacher-by-schedule');
    });

    // Student Exam
    Route::prefix('student/exams')->name('student.exams.')->group(function () {
        Route::get('/', [StudentExamController::class, 'index'])->name('index');
        Route::get('/{examId}/take', [StudentExamController::class, 'take'])->name('take');
        Route::post('/{examId}/submit', [StudentExamController::class, 'submit'])->name('submit');
        Route::get('/{examId}/result', [StudentExamController::class, 'result'])->name('result');
        Route::get('/{examId}/review', [StudentExamController::class, 'review'])->name('review');
    });

    // Schedule Management
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::resource('/', ScheduleController::class)->parameters(['' => 'schedule']);
        Route::get('/class/{classId}', [ScheduleController::class, 'getSchedulesByClass'])->name('by-class');
        Route::get('/export', [ScheduleController::class, 'exportExcel'])->name('export');
        Route::get('/print', [ScheduleController::class, 'printPDF'])->name('print');
    });

    // Attendance (In/Out)
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/export-excel', [AttendanceController::class, 'exportExcel'])->name('export-excel');
        Route::get('/print-pdf', [AttendanceController::class, 'printPdf'])->name('print-pdf');
        Route::resource('/', AttendanceController::class)->parameters(['' => 'attendance']);
        Route::get('/find-by-nisn/{nisn}', [AttendanceController::class, 'findByNisn']);
        Route::post('/find-existing', [AttendanceController::class, 'findExistingAttendance']);
    });

    // Lesson Attendance (Absensi Harian)
    Route::prefix('lesson-attendances')->name('lesson-attendances.')->group(function () {
        Route::get('/export-excel', [LessonAttendanceController::class, 'exportExcel'])->name('export-excel');
        Route::get('/print-pdf', [LessonAttendanceController::class, 'printPDF'])->name('print-pdf');
        Route::resource('/', LessonAttendanceController::class)->except(['create', 'edit', 'show']);
        Route::get('/create', [LessonAttendanceController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [LessonAttendanceController::class, 'edit'])->name('edit');
        Route::match(['get', 'post'], '/get-subjects-by-class', [LessonAttendanceController::class, 'getSubjectsByClass'])->name('get-subjects-by-class');
        Route::match(['get', 'post'], '/get-students', [LessonAttendanceController::class, 'getStudents'])->name('get-students');
        Route::match(['get', 'post'], '/get-current-subject-by-class', [LessonAttendanceController::class, 'getCurrentSubjectByClass'])->name('get-current-subject-by-class');
        Route::get('/get-attendance', [LessonAttendanceController::class, 'getAttendance'])->name('get-attendance');
        Route::get('/get-attendance-calendar', [LessonAttendanceController::class, 'getAttendanceCalendar'])->name('get-attendance-calendar');
        Route::get('/get-general-attendance-calendar', [LessonAttendanceController::class, 'getGeneralAttendanceCalendar'])->name('get-general-attendance-calendar');
        Route::post('/bulk-update', [LessonAttendanceController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export-pdf', [LessonAttendanceController::class, 'exportLessonAttendancePdf'])->name('export-pdf');
    });

    // Face Recognition
    Route::prefix('face-recognition')->group(function () {
        Route::get('/', [FaceRecognitionController::class, 'index'])->name('face-recognition.index');
        Route::get('/create', [FaceRecognitionController::class, 'create'])->name('face-recognition.create');
        Route::post('/store', [FaceRecognitionController::class, 'store'])->name('face-recognition.store');
        Route::get('/Student', [FaceRecognitionController::class, 'getRegisteredStudents'])->name('face-recognition.students');
        Route::post('/record-attendance', [FaceRecognitionController::class, 'recordAttendance'])->name('face-recognition.record-attendance');
        Route::post('/identify', [FaceRecognitionController::class, 'identifyFace'])->name('face-recognition.identify');
        Route::post('/auto-attendance', [FaceRecognitionController::class, 'autoAttendance'])->name('auto-attendance');
        Route::get('/students/{studentId}/class', [FaceRecognitionController::class, 'getStudentClass'])->name('student-class');
    });

    // Student Permissions
    Route::prefix('student-permissions')->name('student-permissions.')->group(function () {
        Route::resource('/', StudentPermissionController::class)
            ->parameters(['' => 'student-permission'])
            ->except(['show']);
        Route::post('/{id}/approve', [StudentPermissionController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [StudentPermissionController::class, 'reject'])->name('reject');
    });

    // Izin (alias for student-permissions)
    Route::prefix('izin')->name('izin.')->group(function () {
        Route::resource('/', StudentPermissionController::class)
            ->parameters(['' => 'izin'])
            ->except(['show']);
        Route::post('/{id}/approve', [StudentPermissionController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [StudentPermissionController::class, 'reject'])->name('reject');
        Route::post('/get-students-by-class', [StudentPermissionController::class, 'getStudentsByClass'])->name('get-students-by-class');
    });

    // Subjects
    Route::resource('subjects', SubjectController::class);
    Route::post('subjects/{id}/restore', [SubjectController::class, 'restore'])->name('subjects.restore');
    Route::delete('subjects/{id}/force-delete', [SubjectController::class, 'forceDelete'])->name('subjects.force-delete');

    // Setting Schedule, Permissions, Roles, Semester
    Route::resource('setting-schedule', SettingScheduleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/export', [PermissionController::class, 'export'])->name('permissions.export');
    Route::post('permissions/import', [PermissionController::class, 'import'])->name('permissions.import');
    Route::resource('roles', RoleController::class);
    Route::resource('semester', SemesterController::class);
    Route::post('semester/generate', [SemesterController::class, 'generateSemester'])->name('semester.generate');
    Route::post('semester/{semester}/set-active', [SemesterController::class, 'setActive'])->name('semester.set-active');

    // Screen Sharing (Teacher)
    Route::prefix('screen-shares')->name('screen-shares.')->group(function () {
        Route::get('/', [ScreenShareController::class, 'index'])->name('index');
        Route::get('/create', [ScreenShareController::class, 'create'])->name('create');
        Route::post('/', [ScreenShareController::class, 'store'])->name('store');
        Route::get('/{screenShare}', [ScreenShareController::class, 'show'])->name('show');
        Route::post('/{screenShare}/end', [ScreenShareController::class, 'end'])->name('end');
        Route::post('/{screenShare}/broadcast', [ScreenShareController::class, 'broadcast'])->name('broadcast');
        Route::get('/{screenShareId}/update', [ScreenShareController::class, 'update'])->name('update');
    });

    // Screen Sharing (Student Join)
    Route::prefix('join-screen')->name('screen-shares.')->group(function () {
        Route::get('/', [ScreenShareController::class, 'joinRoom'])->name('join');
        Route::post('/join', [ScreenShareController::class, 'join'])->name('join.submit');
        Route::get('/{roomCode}', [ScreenShareController::class, 'view'])->name('view');
    });

    // WhatsApp Notification
    Route::prefix('wa-notification')->name('wa-notification.')->group(function () {
        Route::get('/', [WaNotificationController::class, 'index'])->name('index');
        Route::post('/test', [WaNotificationController::class, 'testConnection'])->name('test');
        Route::post('/send-custom', [WaNotificationController::class, 'sendCustom'])->name('send-custom');
        Route::post('/broadcast', [WaNotificationController::class, 'broadcast'])->name('broadcast');
        Route::post('/clear-logs', [WaNotificationController::class, 'clearLogs'])->name('clear-logs');
        Route::delete('/{id}', [WaNotificationController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Debug / Test Routes (remove in production)
|--------------------------------------------------------------------------
*/

Route::get('/test-language', function () {
    $user = auth()->user();
    $language = $user ? $user->language : session('locale', 'id');

    return response()->json([
        'user_authenticated' => auth()->check(),
        'user_id' => $user?->id,
        'user_name' => $user?->name,
        'user_language' => $user?->language,
        'session_locale' => session('locale'),
        'app_locale' => app()->getLocale(),
        'detected_language' => $language,
        'translation_test' => __('index.total_students'),
    ]);
});

Route::get('/sync-language', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $language = $user->language ?? 'id';
        session(['locale' => $language]);
        app()->setLocale($language);

        return response()->json([
            'message' => 'Language synced from database',
            'user_language' => $user->language,
            'session_locale' => session('locale'),
            'app_locale' => app()->getLocale(),
        ]);
    }

    return response()->json(['message' => 'User not authenticated']);
});

Route::get('/public-test-screen/{screenShareId}', function ($screenShareId) {
    $frame = Cache::get("screen_frame_{$screenShareId}");

    return response()->json($frame ? [
        'success' => true,
        'frame_found' => true,
        'image_length' => strlen($frame['image_data']),
        'timestamp' => $frame['timestamp'],
        'teacher_name' => $frame['teacher_name'],
    ] : [
        'success' => false,
        'frame_found' => false,
        'message' => 'No frame in cache',
    ]);
});

Route::get('/test-subjects/{classId}', function ($classId) {
    $currentAcademicYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
    $currentSemester = \App\Helpers\AcademicYearHelper::getCurrentSemester();

    try {
        $subjects = \DB::table('schedule')
            ->join('subject', 'schedule.subject_id', '=', 'subject.id')
            ->where('schedule.class_id', $classId)
            ->where('schedule.academic_year', $currentAcademicYear)
            ->where('schedule.semester', $currentSemester)
            ->whereNull('schedule.deleted_at')
            ->select('subject.*')
            ->get();

        return response()->json(['success' => true, 'subjects' => $subjects, 'count' => $subjects->count()]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::get('/test-teacher/{classId}/{subjectId}', function ($classId, $subjectId) {
    $currentAcademicYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
    $currentSemester = \App\Helpers\AcademicYearHelper::getCurrentSemester();

    try {
        $schedule = \DB::table('schedule')
            ->join('teacher', 'schedule.teacher_id', '=', 'teacher.id')
            ->where('schedule.class_id', $classId)
            ->where('schedule.subject_id', $subjectId)
            ->where('schedule.academic_year', $currentAcademicYear)
            ->where('schedule.semester', $currentSemester)
            ->whereNull('schedule.deleted_at')
            ->select('teacher.*')
            ->first();

        return response()->json(['success' => true, 'teacher' => $schedule, 'count' => $schedule ? 1 : 0]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

require __DIR__.'/auth.php';