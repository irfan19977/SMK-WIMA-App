@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header dengan gradient -->
            <div class="header-section mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-dark mb-1">{{ $classes->name }}</h2>
                        <p class="mb-0">
                            <i class="fas fa-graduation-cap mr-2"></i>{{ $classes->major }} -
                            <span class="badge badge-light">{{ $classes->code }}</span>
                        </p>
                    </div>
                    <div>
                        @can('classes.edit')    
                            <a href="{{ route('classes.edit', $classes->id) }}" class="btn btn-primary btn-shadow">
                                <i class="fas fa-edit"></i> Edit Kelas
                            </a>
                        @endcan
                        <a href="{{ route('classes.index') }}" class="btn btn-light btn-shadow">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Total Siswa</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center" style="color: #667eea; sixe: 50rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ $students->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-success">
                        <div class="card-header">
                            <h4>Hadir Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ rand(20, $students->count()) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4>Tidak Hadir</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ rand(0, 5) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-info">
                        <div class="card-header">
                            <h4>Kehadiran</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-percentage text-info"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ rand(85, 98) }}%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Tabs -->
            <div class="card card-modern">
                <div class="card-header bg-white">
                    <ul class="nav nav-pills card-header-pills" id="mainTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="pill" href="#info" role="tab">
                                <i class="fas fa-info-circle mr-2"></i>Informasi Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="students-tab" data-toggle="pill" href="#students" role="tab">
                                <i class="fas fa-users mr-2"></i>Daftar Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attendance-tab" data-toggle="pill" href="#attendance" role="tab">
                                <i class="fas fa-calendar-check mr-2"></i>Absensi
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="mainTabsContent">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light text-dark">
                                            <h5 class="card-title w-100 mb-0">
                                                Detail Kelas
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Nama Kelas</label>
                                                    <div class="font-weight-bold">{{ $classes->name }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Jurusan</label>
                                                    <div class="font-weight-bold">{{ $classes->major }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Kode Kelas</label>
                                                    <div><span
                                                            class="badge badge-light badge-lg">{{ $classes->code }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Tingkat</label>
                                                    <div class="font-weight-bold">{{ $classes->grade ?? '-' }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Tahun Akademik</label>
                                                    <div class="font-weight-bold">
                                                        {{ $classes->academic_year ?? '2024/2025' }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Total Siswa</label>
                                                    <div><span
                                                            class="badge badge-light badge-lg">{{ $students->count() }}
                                                            siswa</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light text-dark">
                                            <h5 class="card-title mb-0" style="text-bold">
                                                <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @can('classes.edit')
                                                @if($availableStudents->count() > 0)
                                                <button type="button" class="btn btn-primary btn-block btn-modern mb-3"
                                                    data-toggle="modal" data-target="#bulkAssignModal">
                                                    <i class="fas fa-users mr-2"></i>Tambah Siswa
                                                </button>
                                                <button type="button" class="btn btn-info btn-block btn-modern mb-3"
                                                    onclick="window.print()">
                                                    <i class="fas fa-print mr-2"></i>Cetak Daftar Siswa
                                                </button>
                                                <button type="button" class="btn btn-success btn-block btn-modern mb-3"
                                                    onclick="window.print()">
                                                    <i class="fas fa-print mr-2"></i>Cetak Daftar Absen
                                                </button>
                                                @else
                                            @endcan
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                Tidak ada siswa yang tersedia untuk ditambahkan.
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Students Tab -->
                        <div class="tab-pane fade" id="students" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Daftar Siswa ({{ $students->count() }})</h5>
                                <div class="search-box">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control border-left-0" id="searchStudent"
                                            placeholder="Cari nama, email, atau NISN...">
                                    </div>
                                </div>
                            </div>

                            @if($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-modern" id="studentsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Nama Siswa</th>
                                            <th>Email</th>
                                            <th>NISN</th>
                                            <th>Gender</th>
                                            @can('classes.edit')
                                                <th width="100">Aksi</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($student->face_photo && Storage::disk('public')->exists($student->face_photo))
                                                        <img src="{{ asset('storage/' . $student->face_photo) }}" alt="{{ $student->name }}" class="avatar-modern mr-3" style="object-fit:cover;">
                                                    @else
                                                        <div class="avatar-modern mr-3">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold">{{ $student->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $student->user->email ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-outline-info">{{ $student->nisn }}</span>
                                            </td>
                                            <td>
                                                @if($student->gender)
                                                <span
                                                    class="badge {{ $student->gender == 'laki-laki' ? 'badge-outline-primary' : 'badge-outline-pink' }}">
                                                    <i
                                                        class="fas {{ $student->gender == 'laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                                    {{ ucfirst($student->gender) }}
                                                </span>
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            @can('classes.edit')
                                                <td>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="removeStudent('{{ $student->id }}', '{{ $student->name }}')"
                                                        title="Hapus dari kelas">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="empty-state text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="empty-state-title">Belum ada siswa di kelas ini</h5>
                                <p class="empty-state-text text-muted">Mulai tambahkan siswa dengan menekan tombol
                                    "Tambah Siswa"</p>
                                @if($availableStudents->count() > 0)
                                <button type="button" class="btn btn-primary btn-modern" data-toggle="modal"
                                    data-target="#bulkAssignModal">
                                    <i class="fas fa-user-plus mr-2"></i>Tambah Siswa Pertama
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Attendance Tab -->
                        <div class="tab-pane fade" id="attendance" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attendanceMonth">Pilih Bulan:</label>
                                        <select class="form-control" id="attendanceMonth" onchange="filterAttendance()">
                                            @php
                                                $currentYear = date('Y');
                                                $currentMonth = request('month', date('Y-m'));
                                                $months = [
                                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                                ];
                                            @endphp
                                            @foreach($months as $monthNum => $monthName)
                                                @php $monthValue = $currentYear.'-'.$monthNum; @endphp
                                                <option value="{{ $monthValue }}" 
                                                    {{ $currentMonth == $monthValue ? 'selected' : '' }}>
                                                    {{ $monthName }} {{ $currentYear }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-end h-100">
                                        <button class="btn btn-success btn-modern mr-2" onclick="exportAttendance()">
                                            <i class="fas fa-file-excel mr-2"></i>Export Absensi
                                        </button>
                                        <button class="btn btn-primary btn-modern" onclick="window.print()">
                                            <i class="fas fa-print mr-2"></i>Cetak Absensi
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-gradient-success text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        Absensi Bulan <span id="selectedMonth">{{ \Carbon\Carbon::createFromFormat('Y-m', $currentMonth ?? date('Y-m'))->format('F Y') }}</span>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-modern mb-0" id="attendanceTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="50">No</th>
                                                    <th width="200">Nama Siswa</th>
                                                    @for($day = 1; $day <= ($daysInMonth ?? 30); $day++)
                                                        <th width="80">{{ $day }}</th>
                                                    @endfor
                                                    <th width="100">Hadir</th>
                                                    <th width="100">Sakit</th>
                                                    <th width="100">Izin</th>
                                                    <th width="100">Alpha</th>
                                                    <th width="100">Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody id="attendanceTableBody">
                                                @if($students->count() > 0)
                                                    @foreach($students as $index => $student)
                                                        @php
                                                            $presentCount = 0;
                                                            $sickCount = 0;
                                                            $permitCount = 0;
                                                            $absentCount = 0;
                                                            $currentMonthData = \Carbon\Carbon::createFromFormat('Y-m', $currentMonth ?? date('Y-m'));
                                                            $totalDaysInMonth = $daysInMonth ?? $currentMonthData->daysInMonth;
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @if($student->face_photo && Storage::disk('public')->exists($student->face_photo))
                                                                        <img src="{{ asset('storage/' . $student->face_photo) }}" alt="{{ $student->name }}" class="avatar-modern mr-3" style="object-fit:cover;">
                                                                    @else
                                                                        <div class="avatar-modern mr-3">
                                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <div class="font-weight-bold">{{ $student->name }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @for($day = 1; $day <= $totalDaysInMonth; $day++)
                                                                @php
                                                                    // Get attendance for this student and day
                                                                    $attendanceForDay = $attendanceData->get($student->id, collect())->get($day);
                                                                    $checkDate = $currentMonthData->copy()->day($day);
                                                                    $status = '-'; // Default to belum absen
                                                                    
                                                                    if ($attendanceForDay) {
                                                                        $attendance = $attendanceForDay->first();
                                                                        // Determine status based on check_in_status or check_out_status
                                                                        $checkStatus = $attendance->check_in_status ?? $attendance->check_out_status;
                                                                        switch($checkStatus) {
                                                                            case 'tepat':
                                                                            case 'terlambat':
                                                                                $status = 'H';
                                                                                $presentCount++;
                                                                                break;
                                                                            case 'sakit':
                                                                                $status = 'S';
                                                                                $sickCount++;
                                                                                break;
                                                                            case 'izin':
                                                                                $status = 'I';
                                                                                $permitCount++;
                                                                                break;
                                                                            case 'alpha':
                                                                            default:
                                                                                $status = 'A';
                                                                                $absentCount++;
                                                                                break;
                                                                        }
                                                                    } else {
                                                                        // PERBAIKAN LOGIKA: cek kondisi hari
                                                                        if ($checkDate->isFuture()) {
                                                                            // Jika tanggal belum tiba
                                                                            $status = '-';
                                                                        } elseif ($checkDate->isWeekend()) {
                                                                            // Jika weekend/libur
                                                                            $status = 'L';
                                                                        } else {
                                                                            // Jika hari sudah berlalu tapi tidak ada absen
                                                                            $status = 'A';
                                                                            $absentCount++;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <td class="text-center">
                                                                    <span class="attendance-status attendance-{{ strtolower($status) }}">
                                                                        {{ $status }}
                                                                    </span>
                                                                </td>
                                                            @endfor
                                                            <td class="text-center">
                                                                <span class="badge badge-success">{{ $presentCount }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-warning">{{ $sickCount }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-info">{{ $permitCount }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge badge-danger">{{ $absentCount }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                @php
                                                                    $totalValidDays = $presentCount + $sickCount + $permitCount + $absentCount;
                                                                    $percentage = $totalValidDays > 0 ? round(($presentCount / $totalValidDays) * 100, 1) : 0;
                                                                @endphp
                                                                <span class="badge {{ $percentage >= 80 ? 'badge-success' : ($percentage >= 70 ? 'badge-warning' : 'badge-danger') }}">
                                                                    {{ $percentage }}%
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="{{ 7 + ($daysInMonth ?? 30) }}" class="text-center py-4">
                                                            <div class="text-muted">Tidak ada data siswa untuk ditampilkan</div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Legend - UPDATE dengan status baru -->
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center justify-content-center flex-wrap">
                                                    <span class="mr-4 mb-1">
                                                        <span class="attendance-status attendance-h mr-1">H</span>
                                                        <small>Hadir</small>
                                                    </span>
                                                    <span class="mr-4 mb-1">
                                                        <span class="attendance-status attendance-s mr-1">S</span>
                                                        <small>Sakit</small>
                                                    </span>
                                                    <span class="mr-4 mb-1">
                                                        <span class="attendance-status attendance-i mr-1">I</span>
                                                        <small>Izin</small>
                                                    </span>
                                                    <span class="mr-4 mb-1">
                                                        <span class="attendance-status attendance-a mr-1">A</span>
                                                        <small>Alpha</small>
                                                    </span>
                                                    <span class="mr-4 mb-1">
                                                        <span class="attendance-status attendance-l mr-1">L</span>
                                                        <small>Libur</small>
                                                    </span>
                                                    <span class="mb-1">
                                                        <span class="attendance-status attendance-- mr-1">-</span>
                                                        <small>Belum Absen</small>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($availableStudents->count() > 0)
<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Tambah Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('classes.bulk-assign', $classes->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label>Pilih Siswa:</label>
                            <div>
                                <button type="button" class="btn btn-primary" onclick="selectAll()">Pilih Semua</button>
                                <button type="button" class="btn btn-secondary" onclick="deselectAll()">Batal Semua</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchAvailableStudent" placeholder="Cari nama atau NISN...">
                        </div>
                        <div class="students-list" id="availableStudentsList">
                            @foreach($availableStudents as $student)
                            <div class="custom-control custom-checkbox mb-2 student-item">
                                <input type="checkbox" class="custom-control-input student-checkbox"
                                    id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}">
                                <label class="custom-control-label d-flex align-items-center"
                                    for="student_{{ $student->id }}">
                                    <div class="avatar-small mr-2">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-medium">{{ $student->name }}</div>
                                        <small class="text-muted">{{ $student->nisn }}</small>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-users mr-2"></i>Tambah Siswa Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endif

    <form id="removeStudentForm" style="display: none;">
        @csrf
        <input type="hidden" name="student_id" id="removeStudentId">
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#searchAvailableStudent').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                var found = false;
                $('#availableStudentsList .student-item').each(function () {
                    var match = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(match);
                    if (match) found = true;
                });

                // Remove previous message
                $('#availableStudentsList .no-result-message').remove();

                if (!found) {
                    $('#availableStudentsList').append(
                        '<div class="no-result-message text-center text-muted py-3">Tidak ada siswa ditemukan.</div>'
                    );
                }
            });
        });
    </script>
    <script>
        function removeStudent(studentId, studentName) {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Siswa " + studentName + " akan dihapus dari kelas ini!",
                icon: "warning",
                buttons: ['Tidak', 'Ya, Hapus'],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: "Menghapus...",
                        text: "Mohon tunggu sebentar",
                        icon: "info",
                        buttons: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                    
                    // Use FormData instead of JSON
                    const formData = new FormData();
                    formData.append('_method', 'DELETE');
                    formData.append('student_id', studentId);
                    formData.append('_token', csrfToken);

                    fetch("{{ route('classes.remove-student', $classes->id) }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            swal({
                                title: "Berhasil!",
                                text: data.message,
                                icon: "success",
                                timer: 2000,
                                buttons: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Gagal", data.message || "Terjadi kesalahan", "error");
                        }
                    })
                    .catch(error => {
                        swal("Gagal", "Terjadi kesalahan: " + error.message, "error");
                    });
                }
            });
        }

        // Rest of your existing scripts remain the same
        $(document).ready(function() {
            // Pastikan dropdown bulan ter-initialize dengan benar
            const currentMonth = $('#attendanceMonth').val();
            
            // Bind event handler untuk dropdown
            $('#attendanceMonth').off('change').on('change', filterAttendance);
        });

        function selectAll() {
            $('.student-checkbox').prop('checked', true);
        }

        function deselectAll() {
            $('.student-checkbox').prop('checked', false);
        }

        function filterAttendance() {
    const selectedValue = $('#attendanceMonth').val();
    
    // Validasi input
    if (!selectedValue || !selectedValue.match(/^\d{4}-\d{2}$/)) {
        swal('Error', 'Format bulan tidak valid', 'error');
        return;
    }
    
    // Split tahun dan bulan dari format "YYYY-MM"
    const [year, month] = selectedValue.split('-');
    
    // PERBAIKAN: Pastikan parsing yang benar
    const monthNumber = parseInt(month, 10); // Gunakan radix 10
    const yearNumber = parseInt(year, 10);
    
    // Validasi bulan
    if (monthNumber < 1 || monthNumber > 12) {
        swal('Error', 'Bulan tidak valid', 'error');
        return;
    }
    
    // Array nama bulan dalam bahasa Indonesia
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    // Convert month number to month name (monthNumber-1 karena array dimulai dari 0)
    const monthIndex = monthNumber - 1;
    const monthName = monthNames[monthIndex];
    const displayText = `${monthName} ${year}`;
    
    // DEBUG: Log untuk debugging
    console.log('Selected Value:', selectedValue);
    console.log('Year:', year, 'Month:', month);
    console.log('Month Number:', monthNumber);
    console.log('Month Index:', monthIndex);
    console.log('Month Name:', monthName);
    console.log('Display Text:', displayText);
    
    // Update display text SEBELUM AJAX call
    $('#selectedMonth').text(displayText);
    
    // Show loading message
    swal({
        title: 'Memuat Data...',
        text: 'Sedang memuat data absensi untuk ' + displayText,
        icon: 'info',
        timer: 1500,
        buttons: false
    });
    
    // AJAX call untuk mengambil data attendance baru
    $.ajax({
        url: window.location.pathname + '/attendance-data',
        method: 'GET',
        data: { 
            month: selectedValue, // Kirim format YYYY-MM
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(response) {
            console.log('AJAX Response:', response); // DEBUG log
            
            if (response.success) {
                // PERBAIKAN: Jangan update month name lagi dari response
                // karena sudah di-update sebelum AJAX call
                updateAttendanceTable(response.students, response.days_in_month, displayText);
                
                swal({
                    title: 'Berhasil!',
                    text: 'Data absensi berhasil dimuat untuk ' + displayText,
                    icon: 'success',
                    timer: 1000,
                    buttons: false
                });
            } else {
                swal('Error', response.message || 'Gagal memuat data', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            
            let errorMessage = 'Gagal memuat data absensi';
            if (xhr.status === 404) {
                errorMessage = 'Endpoint tidak ditemukan';
            } else if (xhr.status === 500) {
                errorMessage = 'Terjadi kesalahan server';
            } else if (xhr.status === 403) {
                errorMessage = 'Akses ditolak';
            }
            
            swal('Error', errorMessage, 'error');
        },
        timeout: 10000
    });
}

// PERBAIKAN: Update fungsi updateAttendanceTable
function updateAttendanceTable(students, daysInMonth, monthName) {
    // JANGAN update month name lagi di sini karena sudah benar
    // $('#selectedMonth').text(monthName); // HAPUS BARIS INI
    
    console.log('Updating table for:', monthName); // DEBUG
    console.log('Days in month:', daysInMonth); // DEBUG
    console.log('Students data:', students); // DEBUG
    
    // Rebuild header tabel dengan benar
    const thead = $('#attendanceTable thead tr');
    
    let newHeader = `
        <th width="50">No</th>
        <th width="200">Nama Siswa</th>
    `;
    
    // Tambahkan kolom untuk setiap hari dalam bulan
    for(let day = 1; day <= daysInMonth; day++) {
        newHeader += `<th width="80">${day}</th>`;
    }
    
    // Tambahkan kolom summary
    newHeader += `
        <th width="100">Hadir</th>
        <th width="100">Sakit</th>
        <th width="100">Izin</th>
        <th width="100">Alpha</th>
        <th width="100">Persentase</th>
    `;
    
    // Replace header dengan yang baru
    thead.html(newHeader);
    
    // Clear existing table body
    const tbody = $('#attendanceTableBody');
    tbody.empty();
    
    // Populate table body
    if (students && students.length > 0) {
        students.forEach((studentData, index) => {
            let row = `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-small mr-2">
                                ${studentData.student.name.charAt(0).toUpperCase()}
                            </div>
                            <span class="font-weight-medium">${studentData.student.name}</span>
                        </div>
                    </td>
            `;
            
            // Add daily attendance
            for(let day = 1; day <= daysInMonth; day++) {
                const status = studentData.daily_attendance[day] || '-';
                row += `
                    <td class="text-center">
                        <span class="attendance-status attendance-${status.toLowerCase()}">
                            ${status}
                        </span>
                    </td>
                `;
            }
            
            // Add summary columns
            row += `
                    <td class="text-center">
                        <span class="badge badge-success">${studentData.present_count}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">${studentData.sick_count}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">${studentData.permit_count}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-danger">${studentData.absent_count}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${studentData.percentage >= 80 ? 'badge-success' : (studentData.percentage >= 70 ? 'badge-warning' : 'badge-danger')}">
                            ${studentData.percentage}%
                        </span>
                    </td>
                </tr>
            `;
            
            tbody.append(row);
        });
    } else {
        // No data message
        const totalColumns = 2 + daysInMonth + 5;
        tbody.html(`
            <tr>
                <td colspan="${totalColumns}" class="text-center py-4">
                    <div class="text-muted">Tidak ada data siswa untuk ditampilkan</div>
                </td>
            </tr>
        `);
    }
}

        function exportAttendance() {
            swal({
                title: 'Export Berhasil',
                text: 'Data absensi berhasil diexport ke Excel',
                icon: 'success',
                timer: 1500,
                buttons: false
            });
        }

        function exportToExcel() {
            swal({
                title: 'Export Berhasil',
                text: 'Data siswa berhasil diexport ke Excel',
                icon: 'success',
                timer: 1500,
                buttons: false
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Modern Design Styles */
        .header-section {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .nav-pills .nav-link {
            border-radius: 25px;
            font-weight: 500;
            /* padding: 0.75rem 1.5rem; */
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .btn-modern {
            border-radius: 25px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .avatar-modern {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-modern {
            border: none;
        }

        .table-modern thead th {
            border: none;
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table-modern td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
        }

        .badge-outline-info {
            color: #17a2b8;
            border: 1px solid #17a2b8;
            background: transparent;
        }

        .badge-outline-primary {
            color: #007bff;
            border: 1px solid #007bff;
            background: transparent;
        }

        .badge-outline-pink {
            color: #e83e8c;
            border: 1px solid #e83e8c;
            background: transparent;
        }

        .badge-outline-danger {
            color: #dc3545;
            border: 1px solid #dc3545;
            background: transparent;
        }

        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .search-box {
            width: 300px;
        }

        .search-box .form-control {
            border-radius: 25px;
            padding-left: 0;
        }

        .search-box .input-group-text {
            border-radius: 25px 0 0 25px;
        }

        .empty-state {
            padding: 3rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
        }

        .empty-state-title {
            color: #6c757d;
            font-weight: 600;
        }

        .empty-state-text {
            margin-bottom: 2rem;
        }

        .students-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 0.5rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        /* Attendance Styles */
        .attendance-status {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
        }

        .attendance-h {
            background-color: #28a745;
        }

        .attendance-s {
            background-color: #ffc107;
            color: #212529;
        }

        .attendance-i {
            background-color: #17a2b8;
        }

        .attendance-a {
            background-color: #dc3545;
        }

        #attendanceTable {
            font-size: 0.9rem;
        }

        #attendanceTable th {
            text-align: center;
            padding: 0.5rem;
            white-space: nowrap;
        }

        #attendanceTable td {
            padding: 0.5rem;
            text-align: center;
        }

        .font-weight-medium {
            font-weight: 500;
        }
        
        .attendance-l {
            background-color: #6f42c1; /* Purple untuk libur */
            color: white;
        }

        .attendance-- {
            background-color: #e9ecef; /* Light gray untuk belum absen */
            color: #6c757d;
            border: 1px solid #dee2e6;
        }

        /* Update existing attendance status styles untuk konsistensi */
        .attendance-status {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
        }

        .attendance-h {
            background-color: #28a745; /* Green untuk hadir */
        }

        .attendance-s {
            background-color: #ffc107; /* Yellow untuk sakit */
            color: #212529;
        }

        .attendance-i {
            background-color: #17a2b8; /* Cyan untuk izin */
        }

        .attendance-a {
            background-color: #dc3545; /* Red untuk alpha */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-section {
                padding: 1.5rem;
            }

            .search-box {
                width: 100%;
                margin-top: 1rem;
            }

            .card-stats .numbers {
                text-align: left;
                margin-top: 1rem;
            }

            .btn-modern {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            #attendanceTable {
                font-size: 0.8rem;
            }

            .attendance-status {
                width: 20px;
                height: 20px;
                line-height: 20px;
                font-size: 0.7rem;
            }
            .attendance-status {
                width: 20px;
                height: 20px;
                line-height: 20px;
                font-size: 0.7rem;
            }
            
            #attendanceTable {
                font-size: 0.8rem;
            }
            
            #attendanceTable th,
            #attendanceTable td {
                padding: 0.3rem;
            }
        }

    </style>
@endpush
