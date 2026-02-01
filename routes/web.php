<?php

use App\Http\Controllers\API\RFIDController;
use App\Http\Controllers\Backend\AsramaController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\ClassesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Backend\EkstrakurikulerController;
use App\Http\Controllers\Backend\FaceRecognitionController;
use App\Http\Controllers\Backend\LessonAttendanceController;
use App\Http\Controllers\Backend\ParentsController;
use App\Http\Controllers\Frontend\PendaftaranController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RegistrationController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\SemesterController;
use App\Http\Controllers\Backend\SettingScheduleController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\SubjectController;
use App\Http\Controllers\Backend\TeacherController;
use App\Http\Controllers\Backend\PendaftaranSiswaController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StudentGradesController;
use App\Http\Controllers\Backend\TahfizController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\GalleryController;
use App\Http\Controllers\Backend\AcademicReportController;
use App\Http\Controllers\Frontend\BeritaController;
use App\Http\Controllers\Frontend\QrCodeController;
use App\Http\Controllers\ScreenShareController;
use App\Http\Controllers\WebRTCController;
use App\Models\Ekstrakurikuler;
use App\Models\Student;
use App\Models\Gallery;
use App\Models\News;

// Frontend Routes
Route::get('/', function () {
    // Ambil 1 berita terbaru untuk bagian atas
    $featuredNews = News::published()
        ->latest('published_at')
        ->first();
        
    // Ambil 3 berita terbaru untuk bagian informasi terbaru
    $latestNews = News::published()
        ->latest('published_at')
        ->skip(1) // Lewati berita yang sudah diambil untuk featured
        ->take(3)
        ->get();

    $categoriesNews = News::published()
        ->latest('published_at')
        ->where('category', 'Kegiatan')
        ->take(3)
        ->get();

    // Ambil 4 berita untuk news area di homepage
    $homepageNews = News::published()
        ->latest('published_at')
        ->take(4)
        ->get();

    return view('home.index', compact('featuredNews', 'latestNews', 'categoriesNews', 'homepageNews'));
})->name('/');


// Berita Routes - Keep these and remove duplicates below
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/kategori/{category}', [BeritaController::class, 'byCategory'])->name('berita.category');
Route::get('/berita/tag/{tag}', [BeritaController::class, 'byTag'])->name('berita.tag');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail'); // Changed from .detail to .show

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/about', function () {
    return view('home.profile');
})->name('about');

// Route untuk halaman jurusan
Route::get('/teknik-komputer-jaringan', function () {
    $galleries = Gallery::where('jurusan', 'Teknik Komputer dan Jaringan')->latest()->take(3)->get();
    return view('home.tkj', compact('galleries'));
})->name('tkj.index');

// Route untuk galeri lengkap per jurusan
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

// QR Code Generator
Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode.index');
Route::match(['get', 'post'], '/qrcode/generate', [QrCodeController::class, 'generate'])->name('qrcode.generate');

Route::get('/galeri/teknik-bisnis-sepeda-motor', function () {
    $jurusan = 'Teknik Bisnis Sepeda Motor';
    $galleries = Gallery::where('jurusan', $jurusan)->latest()->paginate(12);
    return view('home.gallery', compact('galleries', 'jurusan'));
})->name('gallery.tbsm');

Route::get('/contact', function () {
    return view('home.contact');
})->name('contact.index');

Route::resource('/pendaftaran', PendaftaranController::class);

    Route::post('/rfid-detect', [RFIDController::class, 'detectRFID']);
    Route::get('/get-latest-rfid', [RFIDController::class, 'getLatestRFID'])->name('get.latest.rfid');
    Route::post('/clear-rfid-cache', [RFIDController::class, 'clearRFIDCache'])->name('clear.rfid');

// Public test route for debugging screen sharing (outside auth)
Route::get('/public-test-screen/{screenShareId}', function($screenShareId) {
    $frame = Cache::get("screen_frame_{$screenShareId}");
    if ($frame) {
        return response()->json([
            'success' => true,
            'frame_found' => true,
            'image_length' => strlen($frame['image_data']),
            'timestamp' => $frame['timestamp'],
            'teacher_name' => $frame['teacher_name']
        ]);
    } else {
        return response()->json([
            'success' => false,
            'frame_found' => false,
            'message' => 'No frame in cache'
        ]);
    }
});

// WebRTC Signaling Routes (outside auth for WebRTC to work)
Route::prefix('screen-sharing')->group(function() {
    Route::post('/signal', [WebRTCController::class, 'signal'])->name('webrtc.signal');
    Route::get('/signal/{screenShareId}', [WebRTCController::class, 'poll'])->name('webrtc.poll');
    Route::get('/signal-responses/{screenShareId}', [WebRTCController::class, 'getResponses'])->name('webrtc.responses');
    Route::get('/events/{screenShareId}', [WebRTCController::class, 'events'])->name('webrtc.events');
});

Route::group(['middleware' => 'auth'], function () {
    // Admin News Management
    Route::resource('news', NewsController::class);

    // Admin Galleries Management
    Route::resource('galleries', GalleryController::class);

    // Dashboard Routes
    Route::prefix('dashboard')->name('dashboard')->group(function() {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/attendance-data', [DashboardController::class, 'getAttendanceData'])->name('.attendance-data');
        Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('.chart-data');
    });

    Route::get('pendaftaran-siswa/export', [PendaftaranSiswaController::class, 'export'])->name('pendaftaran-siswa.export');
    Route::get('pendaftaran-siswa/export-excel', [PendaftaranSiswaController::class, 'exportExcel'])->name('pendaftaran-siswa.export-excel');
    Route::get('pendaftaran-siswa/print', [PendaftaranSiswaController::class, 'print'])->name('pendaftaran-siswa.print');
    Route::post('pendaftaran-siswa/{pendaftaran_siswa}/accept', [PendaftaranSiswaController::class, 'accept'])->name('pendaftaran-siswa.accept');
    Route::post('pendaftaran-siswa/{pendaftaran_siswa}/reject', [PendaftaranSiswaController::class, 'reject'])->name('pendaftaran-siswa.reject');
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
        // Route khusus harus didefinisikan sebelum resource untuk menghindari bentrok dengan {class}
        Route::get('/promote-data', [ClassesController::class, 'getPromoteData'])->name('promote-data');
        Route::post('/open-next-semester-bulk', [ClassesController::class, 'openNextSemesterBulk'])->name('open-next-semester-bulk');
        Route::post('/promote-bulk', [ClassesController::class, 'promoteStudentsBulk'])->name('promote-bulk');
        Route::get('/check-promotion-period', [ClassesController::class, 'checkPromotionPeriod'])->name('check-promotion-period');
        Route::get('/preview-promotion', [ClassesController::class, 'previewPromotion'])->name('preview-promotion');

        Route::resource('/', ClassesController::class)->parameters(['' => 'class']);
        Route::post('{class}/assign-student', [ClassesController::class, 'assignStudent'])->name('assign-student');
        Route::post('{class}/bulk-assign', [ClassesController::class, 'bulkAssign'])->name('bulk-assign');
        Route::delete('{class}/remove-student', [ClassesController::class, 'removeStudent'])->name('remove-student');
        Route::get('{class}/attendance-data', [ClassesController::class, 'getAttendanceData'])->name('attendance-data');
        Route::post('{class}/toggle-archive', [ClassesController::class, 'toggleArchive'])->name('toggle-archive');
        Route::post('{class}/open-next-semester', [ClassesController::class, 'openNextSemester'])->name('open-next-semester');
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

    // Academic Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('academic', [AcademicReportController::class, 'index'])->name('academic');
        Route::get('academic/semester-data', [AcademicReportController::class, 'semesterData'])->name('academic.semester-data');
        Route::get('academic/all-subjects-data', [AcademicReportController::class, 'allSubjectsData'])->name('academic.all-subjects-data');
        Route::get('academic/export-pdf', [AcademicReportController::class, 'exportPdf'])->name('academic.export-pdf');
        Route::get('academic/export-excel', [AcademicReportController::class, 'exportExcel'])->name('academic.export-excel');
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
    Route::resource('semester', SemesterController::class);
    Route::post('semester/generate', [SemesterController::class, 'generateSemester'])->name('semester.generate');
    Route::post('semester/{semester}/set-active', [SemesterController::class, 'setActive'])->name('semester.set-active');

    // Screen Sharing Routes
    Route::prefix('screen-shares')->name('screen-shares.')->group(function() {
        Route::get('/', [ScreenShareController::class, 'index'])->name('index');
        Route::get('/create', [ScreenShareController::class, 'create'])->name('create');
        Route::post('/', [ScreenShareController::class, 'store'])->name('store');
        Route::get('/{screenShare}', [ScreenShareController::class, 'show'])->name('show');
        Route::post('/{screenShare}/end', [ScreenShareController::class, 'end'])->name('end');
        Route::post('/{screenShare}/broadcast', [ScreenShareController::class, 'broadcast'])->name('broadcast');
        Route::get('/{screenShareId}/update', [ScreenShareController::class, 'update'])->name('update');
    });

    // Student Screen Sharing Routes
    Route::prefix('join-screen')->name('screen-shares.')->group(function() {
        Route::get('/', [ScreenShareController::class, 'joinRoom'])->name('join');
        Route::post('/join', [ScreenShareController::class, 'join'])->name('join.submit');
        Route::get('/{roomCode}', [ScreenShareController::class, 'view'])->name('view');
    });

    // Debug test route (public for testing)
    Route::get('/public-test-screen/{screenShareId}', function($screenShareId) {
        $frame = Cache::get("screen_frame_{$screenShareId}");
        if ($frame) {
            return response()->json([
                'success' => true,
                'frame_found' => true,
                'image_length' => strlen($frame['image_data']),
                'timestamp' => $frame['timestamp'],
                'teacher_name' => $frame['teacher_name']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'frame_found' => false,
                'message' => 'No frame in cache'
            ]);
        }
    });

    // Debug test route
    Route::get('/test-screen/{screenShareId}', function($screenShareId) {
        $frame = Cache::get("screen_frame_{$screenShareId}");
        if ($frame) {
            return response()->json([
                'success' => true,
                'frame_found' => true,
                'image_length' => strlen($frame['image_data']),
                'timestamp' => $frame['timestamp']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'frame_found' => false,
                'message' => 'No frame in cache'
            ]);
        }
    });

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';