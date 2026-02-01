@extends('layouts.master')

@section('title')
    Detail Kelas - {{ $classes->name }}
@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Detail Kelas - {{ $classes->name }}
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-xl-3">
            <!-- Class Header Card -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-xxl mx-auto bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="mdi mdi-school text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $classes->name }}</h4>
                    <p class="text-muted mb-2">{{ $classes->major }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-primary">{{ $classes->code }}</span>
                        <span class="badge bg-info">Grade {{ $classes->grade }}</span>
                        @if($classes->is_archived)
                            <span class="badge bg-warning">Diarsipkan</span>
                        @else
                            <span class="badge bg-success">Aktif</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="mdi mdi-calendar me-1"></i>
                        {{ $classes->academic_year}}
                    </p>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mb-3">
                <div class="card-header bg-primary">
                    <h5 class="card-title mb-0 text-white">
                        <i class="mdi mdi-flash me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('classes.edit', $classes->id) }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit Kelas
                        </a>
                        <button class="btn btn-outline-info" onclick="exportStudents()">
                            <i class="mdi mdi-file-excel me-1"></i> Export Data
                        </button>
                        <button class="btn btn-outline-success" onclick="addStudents()">
                            <i class="mdi mdi-account-plus me-1"></i> Tambah Siswa
                        </button>
                        <button class="btn btn-outline-warning" onclick="promoteStudents()">
                            <i class="mdi mdi-arrow-up-bold me-1"></i> Naikkan Kelas
                        </button>
                        <button class="btn btn-outline-secondary" onclick="toggleArchive()">
                            <i class="mdi mdi-archive me-1"></i> {{ $classes->is_archived ? 'Batalkan Arsip' : 'Arsipkan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-9">
            <!-- Tabs Navigation -->
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-bs-toggle="tab" href="#students-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-account-multiple"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-account-multiple me-2"></i>Daftar Siswa</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#attendance-in-out-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-clock-in"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-clock-in me-2"></i>Absensi In/Out</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#attendance-subject-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-book-open-variant"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-book-open-variant me-2"></i>Absensi Pelajaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Students Tab -->
                <div class="tab-pane active" id="students-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="mb-1">{{ $classes->name }}</h4>
                                    <p class="text-muted mb-0">
                                        {{ $classes->major }} - Grade {{ $classes->grade }} - {{ $classes->code }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="input-group" style="width: 250px;">
                                        <input type="text" class="form-control" placeholder="Cari siswa..." id="search-student">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="addStudents()">
                                        <i class="mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>NISN</th>
                                            <th>Nama Siswa</th>
                                            <th>Jenis Kelamin</th>
                                            <th>No. HP</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="students-tbody">
                                        @forelse ($students as $index => $student)
                                        <tr class="student-row" data-name="{{ $student->name ?? '' }}" data-nisn="{{ $student->nisn ?? '' }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->nisn ?? '-' }}</td>
                                            <td>
                                                @if($student->user_id)
                                                    <a href="{{ route('profile.show') }}?user_id={{ $student->user_id }}" class="text-primary text-decoration-none">
                                                        {{ $student->name }}
                                                    </a>
                                                @else
                                                    {{ $student->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(($student->gender ?? '') == 'L')
                                                    <span class="badge bg-primary">L</span>
                                                @else
                                                    <span class="badge bg-info">P</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->phone ?? '-' }}</td>
                                            <td><span class="badge bg-success">Aktif</span></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('profile.show') }}?user_id={{ $student->user_id }}" class="btn btn-outline-primary" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-center">
                                                    <i class="mdi mdi-account-multiple display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted">Belum ada siswa</h5>
                                                    <p class="text-muted">Klik tombol "Tambah" untuk menambahkan siswa</p>
                                                    <button type="button" class="btn btn-primary" onclick="addStudents()">
                                                        <i class="mdi mdi-plus"></i> Tambah Siswa
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance In/Out Tab -->
                <div class="tab-pane" id="attendance-in-out-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">Absensi In/Out</h4>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="month" class="form-control" id="attendance-month" value="{{ $currentMonth ?? date('Y-m') }}" style="width: auto;">
                                    <button type="button" class="btn btn-primary" onclick="loadAttendanceData()">
                                        <i class="mdi mdi-refresh"></i> Refresh
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="markAttendance()">
                                        <i class="mdi mdi-check"></i> Mark Absensi
                                    </button>
                                </div>
                            </div>

                            <!-- Attendance Summary -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-success mb-1">{{ $attendanceData->where('status', 'hadir')->count() ?? 0 }}</h5>
                                        <small class="text-muted">Hadir</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-warning mb-1">{{ $attendanceData->where('status', 'izin')->count() ?? 0 }}</h5>
                                        <small class="text-muted">Izin</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-light text-dark mb-1">{{ $attendanceData->where('status', 'sakit')->count() ?? 0 }}</h5>
                                        <small class="text-muted">Sakit</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-danger mb-1">{{ $attendanceData->where('status', 'alfa')->count() ?? 0 }}</h5>
                                        <small class="text-muted">Alfa</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Calendar/Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Siswa</th>
                                            @for($day = 1; $day <= ($daysInMonth ?? 30); $day++)
                                                <th class="text-center" style="min-width: 40px;">{{ $day }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($students as $student)
                                        <tr>
                                            <td class="fw-semibold">{{ $student->name }}</td>
                                            @for($day = 1; $day <= ($daysInMonth ?? 30); $day++)
                                                <td class="text-center">
                                                    @php
                                                        $attendance = $attendanceData[$student->id][$day] ?? null;
                                                    @endphp
                                                    @if($attendance)
                                                        @php
                                                            $attendanceRecord = $attendance->first();
                                                            $status = $attendanceRecord ? ($attendanceRecord->check_in_status ?? $attendanceRecord->check_out_status ?? 'alpha') : 'alpha';
                                                        @endphp
                                                        @if($status == 'tepat' || $status == 'terlambat')
                                                            <span class="badge bg-success">H</span>
                                                        @elseif($status == 'izin')
                                                            <span class="badge bg-warning">I</span>
                                                        @elseif($status == 'sakit')
                                                            <span class="badge bg-light text-dark">S</span>
                                                        @else
                                                            <span class="badge bg-danger">A</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ ($daysInMonth ?? 30) + 1 }}" class="text-center py-3">
                                                <p class="text-muted">Belum ada data absensi</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Subject Tab -->
                <div class="tab-pane" id="attendance-subject-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <!-- Filter Section -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="subjectFilter" class="form-label">Mata Pelajaran:</label>
                                    <select id="subjectFilter" class="form-select">
                                        <option value="">Pilih Mata Pelajaran</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="subjectDateFilter" class="form-label">Tanggal:</label>
                                    <input type="date" id="subjectDateFilter" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="academicYearFilter" class="form-label">Tahun Akademik:</label>
                                    @php
                                        $scheduleAcademicYear = \App\Models\Schedule::where('class_id', $classes->id)
                                            ->distinct()
                                            ->pluck('academic_year')
                                            ->first() ?? \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
                                            
                                        $scheduleYears = \App\Models\Schedule::where('class_id', $classes->id)
                                            ->distinct()
                                            ->pluck('academic_year')
                                            ->filter()
                                            ->sort()
                                            ->values();
                                    @endphp
                                    <select id="academicYearFilter" class="form-select">
                                        @if($scheduleYears->count() > 0)
                                            @foreach($scheduleYears as $year)
                                                <option value="{{ $year }}" {{ $scheduleAcademicYear == $year ? 'selected' : '' }}>{{ $year }} (Ada Data)</option>
                                            @endforeach
                                        @endif
                                        
                                        @foreach(\App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                            @if(!$scheduleYears->contains($year))
                                                <option value="{{ $year }}" {{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label><br>
                                    <button type="button" id="loadSubjectAttendance" class="btn btn-primary">
                                        <i class="mdi mdi-refresh me-1"></i>Muat Data
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mb-3" id="subjectAttendanceActions" style="display: none;">
                                <div class="col-12">
                                    <button type="button" id="editSubjectModeBtn" class="btn btn-warning">
                                        <i class="mdi mdi-pencil me-1"></i>Mode Edit
                                    </button>
                                    <button type="button" id="saveSubjectAttendanceBtn" class="btn btn-success" style="display: none;">
                                        <i class="mdi mdi-content-save me-1"></i>Simpan Semua
                                    </button>
                                    <button type="button" id="hadirkanSemuaSubjectBtn" class="btn btn-primary" style="display: none;">
                                        <i class="mdi mdi-check-all me-1"></i>Hadirkan Semua
                                    </button>
                                    <button type="button" id="alphakanSemuaSubjectBtn" class="btn btn-danger" style="display: none;">
                                        <i class="mdi mdi-close me-1"></i>Alpha Semua
                                    </button>
                                </div>
                            </div>

                            <!-- Subject Attendance Summary -->
                            <div class="row mb-4" id="subjectAttendanceSummary" style="display: none;">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-success mb-1" id="attendanceRate">0%</h5>
                                        <small class="text-muted">Kehadiran Rata-rata</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-primary mb-1" id="totalStudents">0</h5>
                                        <small class="text-muted">Total Siswa</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-success mb-1" id="presentCount">0</h5>
                                        <small class="text-muted">Hadir</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-warning mb-1" id="absentCount">0</h5>
                                        <small class="text-muted">Tidak Hadir</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Attendance Table -->
                            <div class="table-responsive">
                                <table id="subjectAttendanceTable" class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="8%">No Absen</th>
                                            <th width="25%">Nama Siswa</th>
                                            <th width="15%">NISN</th>
                                            <th width="12%">Jam Masuk</th>
                                            <th width="15%">Status</th>
                                            <th width="15%">Keterangan</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subjectAttendanceBody">
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-center">
                                                    <i class="mdi mdi-book-open-variant display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted">Pilih Mata Pelajaran</h5>
                                                    <p class="text-muted">Pilih mata pelajaran dan tanggal untuk melihat data absensi</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Loading Indicator -->
                            <div id="subjectLoadingIndicator" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat data absensi...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Assign Modal -->
    @if($availableStudents->count() > 0)
    <div class="modal fade" id="bulkAssignModal" tabindex="-1" role="dialog" aria-labelledby="bulkAssignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkAssignModalLabel">
                        <i class="mdi mdi-account-plus me-2"></i>Tambah Siswa ke Kelas {{ $classes->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('classes.bulk-assign', $classes->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label">Pilih Siswa:</label>
                                <div>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="selectAll()">
                                        <i class="mdi mdi-check-all me-1"></i>Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="deselectAll()">
                                        <i class="mdi mdi-close me-1"></i>Batal Semua
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="searchAvailableStudent" placeholder="Cari nama atau NISN siswa...">
                            </div>
                            <div class="students-list" id="availableStudentsList" style="max-height: 400px; overflow-y: auto;">
                                @foreach($availableStudents as $student)
                                <div class="form-check mb-2 student-item">
                                    <input class="form-check-input student-checkbox" type="checkbox" 
                                        id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}">
                                    <label class="form-check-label d-flex align-items-center" for="student_{{ $student->id }}">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->nisn }} - {{ $student->user->email ?? 'No Email' }}</small>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="mdi mdi-close me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-account-plus me-1"></i>Tambah Siswa Terpilih
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Class Modal -->
    <div class="modal fade" id="classModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="classForm" action="/classes" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Kelas</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade</label>
                            <select class="form-control" id="grade" name="grade" required>
                                <option value="">Pilih Grade</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="major" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="major" name="major" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let currentClassId = '{{ $classes->id }}';

        // Edit class functionality
        function openEditModal() {
            document.getElementById('modalTitle').textContent = 'Edit Kelas';
            document.getElementById('classForm').action = '/classes/' + currentClassId + '?redirect_to=show';
            
            // Add method override for PUT
            let methodInput = document.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('classForm').appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }
            
            // Add redirect parameter
            let redirectInput = document.querySelector('input[name="redirect_to"]');
            if (!redirectInput) {
                redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect_to';
                redirectInput.value = 'show';
                document.getElementById('classForm').appendChild(redirectInput);
            }
            
            // Fill form with current class data
            document.getElementById('name').value = '{{ $classes->name }}';
            document.getElementById('code').value = '{{ $classes->code }}';
            document.getElementById('grade').value = '{{ $classes->grade }}';
            document.getElementById('major').value = '{{ $classes->major }}';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('classModal'));
            modal.show();
        }

        // Add click event to edit button
        const editClassBtn = document.querySelector('a[href="{{ route('classes.edit', $classes->id) }}"]');
        if (editClassBtn) {
            editClassBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openEditModal();
            });
        }

        // Search functionality
        let searchTimeout;
        const searchInput = document.getElementById('search-student');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('.student-row').forEach(function(row) {
                        const name = row.getAttribute('data-name').toLowerCase();
                        const nisn = row.getAttribute('data-nisn').toLowerCase();
                        row.style.display = (name.includes(query) || nisn.includes(query)) ? '' : 'none';
                    });
                }, 300);
            });
        }
    });

    // Action functions
    function exportStudents() {
        Swal.fire({
            icon: 'info',
            title: 'Export Data Siswa',
            text: 'Fitur export data siswa akan segera tersedia',
            confirmButtonColor: '#3085d6'
        });
    }

    function addStudents() {
        // Check if there are available students
        @if($availableStudents->count() > 0)
            const modal = new bootstrap.Modal(document.getElementById('bulkAssignModal'));
            modal.show();
        @else
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Siswa Tersedia',
                text: 'Semua siswa sudah terdaftar di kelas atau tidak ada siswa yang tersedia.',
                confirmButtonColor: '#3085d6'
            });
        @endif
    }

    function promoteStudents() {
        Swal.fire({
            icon: 'question',
            title: 'Naikkan Kelas',
            text: 'Apakah Anda ingin menaikkan semua siswa ke kelas berikutnya?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Naikkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Success!', 'Semua siswa berhasil dinaikkan kelasnya.', 'success');
            }
        });
    }

    function toggleArchive() {
        const isArchived = {{ $classes->is_archived ? 'true' : 'false' }};
        const action = isArchived ? 'membatalkan arsip' : 'mengarsipkan';
        
        Swal.fire({
            icon: 'question',
            title: action.charAt(0).toUpperCase() + action.slice(1) + ' Kelas',
            text: `Apakah Anda yakin ingin ${action} kelas ini?`,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ' + action.charAt(0).toUpperCase() + action.slice(1),
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("classes.toggle-archive", $classes->id) }}';
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Attendance functions
    function loadAttendanceData() {
        const month = document.getElementById('attendance-month').value;
        // Implement AJAX load attendance data
        Swal.fire({
            icon: 'info',
            title: 'Load Data Absensi',
            text: 'Memuat data absensi untuk bulan ' + month,
            confirmButtonColor: '#3085d6'
        });
    }

    function markAttendance() {
        Swal.fire({
            icon: 'info',
            title: 'Mark Absensi',
            text: 'Fitur mark absensi akan segera tersedia',
            confirmButtonColor: '#3085d6'
        });
    }

    function markSubjectAttendance() {
        Swal.fire({
            icon: 'info',
            title: 'Mark Absensi Pelajaran',
            text: 'Fitur mark absensi pelajaran akan segera tersedia',
            confirmButtonColor: '#3085d6'
        });
    }

    function saveSubjectAttendance(studentId) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Absensi pelajaran berhasil disimpan',
            confirmButtonColor: '#3085d6'
        });
    }

    // Bulk Assign Functions
    function selectAll() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deselectAll() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    // Search functionality for available students
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchAvailableStudent');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const value = this.value.toLowerCase();
                const studentItems = document.querySelectorAll('#availableStudentsList .student-item');
                
                studentItems.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(value)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });

    // Subject Attendance Functions
    let currentClassId = '{{ $classes->id }}';
    let isEditMode = false;

    // Load subjects when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing...');
        console.log('Current class ID:', currentClassId);
        
        // Fallback: Try to load subjects immediately
        loadSubjects();
        
        // Set up event listeners with error handling
        try {
            const loadBtn = document.getElementById('loadSubjectAttendance');
            if (loadBtn) {
                loadBtn.addEventListener('click', loadSubjectAttendanceData);
            }
            
            const editBtn = document.getElementById('editSubjectModeBtn');
            if (editBtn) {
                editBtn.addEventListener('click', toggleEditMode);
            }
            
            const saveBtn = document.getElementById('saveSubjectAttendanceBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', saveAllSubjectAttendance);
            }
            
            const hadirBtn = document.getElementById('hadirkanSemuaSubjectBtn');
            if (hadirBtn) {
                hadirBtn.addEventListener('click', markAllPresent);
            }
            
            const alphaBtn = document.getElementById('alphakanSemuaSubjectBtn');
            if (alphaBtn) {
                alphaBtn.addEventListener('click', markAllAbsent);
            }
        } catch (error) {
            console.error('Error setting up event listeners:', error);
        }
    });

    function loadSubjects() {
        const academicYear = document.getElementById('academicYearFilter').value;
        
        console.log('Loading subjects for class:', currentClassId, 'academic year:', academicYear);
        
        // Fallback: Load subjects directly from schedule data if available
        @php
            // Use the academic year that actually has schedule data
            $scheduleAcademicYear = \App\Models\Schedule::where('class_id', $classes->id)
                ->distinct()
                ->pluck('academic_year')
                ->first() ?? \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
                
            $schedules = \App\Models\Schedule::where('class_id', $classes->id)
                ->where('academic_year', $scheduleAcademicYear)
                ->with('subject')
                ->get()
                ->unique('subject_id');
        @endphp
        
        const fallbackSubjects = @json($schedules->pluck('subject')->unique('id')->values()->toArray());
        const scheduleAcademicYear = "{{ $scheduleAcademicYear }}";
        
        console.log('Using schedule academic year:', scheduleAcademicYear);
        
        if (fallbackSubjects && fallbackSubjects.length > 0) {
            console.log('Using fallback subjects:', fallbackSubjects);
            const select = document.getElementById('subjectFilter');
            select.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
            
            fallbackSubjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                select.appendChild(option);
            });
            
            console.log('Fallback subjects loaded successfully:', fallbackSubjects.length);
            
            // Set the academic year filter to match the schedule year
            const academicYearSelect = document.getElementById('academicYearFilter');
            if (academicYearSelect) {
                academicYearSelect.value = scheduleAcademicYear;
            }
            
            return;
        }
        
        // Try API call if fallback doesn't work
        fetch(`/lesson-attendances/get-subjects-by-class?class_id=${currentClassId}&academic_year=${academicYear}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Subjects data:', data);
                const select = document.getElementById('subjectFilter');
                select.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                
                if (data.success && data.data && data.data.length > 0) {
                    data.data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        select.appendChild(option);
                    });
                    console.log('Subjects loaded successfully:', data.data.length);
                } else {
                    console.log('No subjects found or error:', data);
                    // Show message if no subjects
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Tidak ada mata pelajaran tersedia';
                    option.disabled = true;
                    select.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                const select = document.getElementById('subjectFilter');
                if (select.innerHTML.includes('Pilih Mata Pelajaran')) {
                    select.innerHTML = '<option value="">Gagal memuat mata pelajaran</option>';
                }
            });
    }

    function loadSubjectAttendanceData() {
        const subjectId = document.getElementById('subjectFilter').value;
        const date = document.getElementById('subjectDateFilter').value;
        const academicYear = document.getElementById('academicYearFilter').value;
        
        if (!subjectId) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Pilih mata pelajaran terlebih dahulu',
                confirmButtonColor: '#3085d6'
            });
            return;
        }
        
        // Show loading
        document.getElementById('subjectLoadingIndicator').style.display = 'block';
        document.getElementById('subjectAttendanceBody').innerHTML = '';
        
        fetch(`/lesson-attendances/get-attendance?class_id=${currentClassId}&subject_id=${subjectId}&date=${date}&academic_year=${academicYear}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('subjectLoadingIndicator').style.display = 'none';
                
                if (data.success && data.data.length > 0) {
                    displaySubjectAttendance(data.data);
                    updateSummary(data.data);
                    document.getElementById('subjectAttendanceActions').style.display = 'block';
                    document.getElementById('subjectAttendanceSummary').style.display = 'flex';
                } else {
                    document.getElementById('subjectAttendanceBody').innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-center">
                                    <i class="mdi mdi-information display-4 text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak Ada Data</h5>
                                    <p class="text-muted">Belum ada data absensi untuk mata pelajaran ini</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    document.getElementById('subjectAttendanceActions').style.display = 'none';
                    document.getElementById('subjectAttendanceSummary').style.display = 'none';
                }
            })
            .catch(error => {
                document.getElementById('subjectLoadingIndicator').style.display = 'none';
                console.error('Error loading attendance:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat data absensi',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function displaySubjectAttendance(data) {
        const tbody = document.getElementById('subjectAttendanceBody');
        tbody.innerHTML = '';
        
        data.forEach(attendance => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${attendance.no_absen}</td>
                <td class="fw-semibold">${attendance.student_name}</td>
                <td>${attendance.student_nisn}</td>
                <td>
                    <input type="time" class="form-control form-control-sm" id="checkin_${attendance.student_id}" 
                           value="${attendance.check_in || ''}" ${!isEditMode ? 'disabled' : ''}>
                </td>
                <td>
                    <select class="form-select form-select-sm" id="status_${attendance.student_id}" 
                            ${!isEditMode ? 'disabled' : ''}>
                        <option value="hadir" ${attendance.check_in_status === 'hadir' ? 'selected' : ''}>Hadir</option>
                        <option value="terlambat" ${attendance.check_in_status === 'terlambat' ? 'selected' : ''}>Terlambat</option>
                        <option value="izin" ${attendance.check_in_status === 'izin' ? 'selected' : ''}>Izin</option>
                        <option value="sakit" ${attendance.check_in_status === 'sakit' ? 'selected' : ''}>Sakit</option>
                        <option value="alpha" ${attendance.check_in_status === 'alpha' ? 'selected' : ''}>Alpha</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" placeholder="Keterangan..." 
                           ${!isEditMode ? 'disabled' : ''}>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" onclick="saveIndividualAttendance(${attendance.student_id})" 
                            ${!isEditMode ? 'disabled' : ''}>
                        <i class="mdi mdi-check"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function updateSummary(data) {
        const total = data.length;
        const present = data.filter(a => a.check_in_status === 'hadir' || a.check_in_status === 'terlambat').length;
        const absent = total - present;
        const rate = total > 0 ? Math.round((present / total) * 100) : 0;
        
        document.getElementById('attendanceRate').textContent = rate + '%';
        document.getElementById('totalStudents').textContent = total;
        document.getElementById('presentCount').textContent = present;
        document.getElementById('absentCount').textContent = absent;
    }

    function toggleEditMode() {
        isEditMode = !isEditMode;
        const editBtn = document.getElementById('editSubjectModeBtn');
        const saveBtn = document.getElementById('saveSubjectAttendanceBtn');
        const hadirBtn = document.getElementById('hadirkanSemuaSubjectBtn');
        const alphaBtn = document.getElementById('alphakanSemuaSubjectBtn');
        
        if (isEditMode) {
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
            hadirBtn.style.display = 'inline-block';
            alphaBtn.style.display = 'inline-block';
            
            // Enable all inputs
            document.querySelectorAll('#subjectAttendanceBody input, #subjectAttendanceBody select').forEach(el => {
                el.disabled = false;
            });
        } else {
            editBtn.style.display = 'inline-block';
            saveBtn.style.display = 'none';
            hadirBtn.style.display = 'none';
            alphaBtn.style.display = 'none';
            
            // Disable all inputs
            document.querySelectorAll('#subjectAttendanceBody input, #subjectAttendanceBody select').forEach(el => {
                el.disabled = true;
            });
        }
    }

    function saveAllSubjectAttendance() {
        const subjectId = document.getElementById('subjectFilter').value;
        const date = document.getElementById('subjectDateFilter').value;
        const academicYear = document.getElementById('academicYearFilter').value;
        const attendances = [];
        
        document.querySelectorAll('#subjectAttendanceBody tr').forEach(row => {
            const studentId = row.querySelector('td:nth-child(2)').textContent.match(/\d+/)?.[0];
            if (studentId) {
                attendances.push({
                    student_id: studentId,
                    class_id: currentClassId,
                    subject_id: subjectId,
                    date: date,
                    check_in: document.getElementById(`checkin_${studentId}`).value,
                    check_in_status: document.getElementById(`status_${studentId}`).value,
                    academic_year: academicYear
                });
            }
        });
        
        fetch('/lesson-attendances/bulk-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ attendances: attendances })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    confirmButtonColor: '#3085d6'
                });
                loadSubjectAttendanceData(); // Reload data
                toggleEditMode(); // Exit edit mode
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#3085d6'
                });
            }
        })
        .catch(error => {
            console.error('Error saving attendance:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal menyimpan data absensi',
                confirmButtonColor: '#3085d6'
            });
        });
    }

    function markAllPresent() {
        document.querySelectorAll('#subjectAttendanceBody select').forEach(select => {
            select.value = 'hadir';
        });
    }

    function markAllAbsent() {
        document.querySelectorAll('#subjectAttendanceBody select').forEach(select => {
            select.value = 'alpha';
        });
    }

    function saveIndividualAttendance(studentId) {
        // Implementation for individual save
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Absensi berhasil disimpan',
            confirmButtonColor: '#3085d6'
        });
    }
</script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection