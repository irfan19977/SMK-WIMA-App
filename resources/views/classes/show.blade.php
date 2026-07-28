@extends('layouts.master')

@section('title')
    {{ __('index.class_detail') }} - {{ $classes->name }}
@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .student-item {
            position: relative;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.2s ease;
            background: #fff;
        }
        .student-item:hover {
            border-color: #0d6efd;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
        }
        .student-item.selected {
            border-color: #0d6efd;
            background: #f0f7ff;
        }
        .student-checkbox {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            cursor: pointer;
            z-index: 2;
        }
        .student-label {
            display: flex;
            align-items: center;
            padding: 12px 12px 12px 42px;
            margin: 0;
            cursor: pointer;
            border-radius: 8px;
            min-height: 64px;
        }
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .student-info {
            flex: 1;
            min-width: 0;
        }
        .student-name {
            font-weight: 500;
            color: #212529;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .student-meta {
            font-size: 12px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .student-check {
            font-size: 20px;
            opacity: 0;
            transition: opacity 0.2s ease;
            margin-left: 8px;
            flex-shrink: 0;
        }
        .student-item.selected .student-check {
            opacity: 1;
        }
        .class-overview {
            overflow: hidden;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 26px rgba(22, 56, 74, 0.08);
        }
        .class-overview__hero {
            padding: 26px 24px 22px;
            color: #fff;
            background: linear-gradient(135deg, #087f8c 0%, #0b6173 100%);
        }
        .class-overview__icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 28px;
        }
        .class-overview__body {
            padding: 18px 24px 22px;
        }
        .class-overview__meta {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            color: #657786;
            font-size: 13px;
            border-bottom: 1px solid #edf1f4;
        }
        .class-overview__meta:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }
        .class-stat {
            border: 1px solid #e6eef1;
            border-radius: 12px;
            background: #f8fbfc;
        }
        .class-tabs {
            padding: 8px;
            border: 0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(22, 56, 74, 0.06);
        }
        .class-tabs .nav-link {
            padding: 12px 16px;
            color: #657786;
            font-weight: 600;
            border-radius: 9px;
        }
        .class-tabs .nav-link.active {
            color: #fff;
            background: #0d8494;
            box-shadow: 0 4px 10px rgba(13, 132, 148, 0.22);
        }
        .student-directory {
            overflow: hidden;
            border: 0;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(22, 56, 74, 0.06);
        }
        .student-directory .card-header {
            padding: 22px 24px;
            background: #fff;
            border-bottom: 1px solid #edf1f4;
        }
        .student-directory .card-body {
            padding: 0;
        }
        .student-directory .table {
            margin-bottom: 0;
        }
        .student-directory .table thead th {
            padding: 14px 24px;
            color: #718096;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            background: #f7fafb;
            border: 0;
        }
        .student-directory .table tbody td {
            padding: 15px 24px;
            vertical-align: middle;
            border-color: #edf1f4;
        }
        .student-number {
            display: inline-flex;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            color: #0d8494;
            font-size: 12px;
            font-weight: 700;
            background: #e7f6f7;
            border-radius: 50%;
        }
        .student-profile-link {
            color: #1d3b4a;
            font-weight: 600;
        }
        .student-profile-link:hover {
            color: #0d8494;
        }
        @media (max-width: 1199.98px) {
            .class-overview { margin-bottom: 20px; }
        }
        @media (max-width: 575.98px) {
            .student-directory .card-header { padding: 18px; }
            .student-directory .table thead th, .student-directory .table tbody td { padding: 12px 16px; }
        }
    </style>
@endsection

@section('page-title')
    {{ __('index.class_detail') }} - {{ $classes->name }}
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-xl-3">
            <!-- Class Header Card -->
            <div class="card class-overview mb-3">
                <div class="class-overview__hero">
                    <div class="class-overview__icon mb-3"><i class="mdi mdi-school"></i></div>
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <p class="text-white-50 text-uppercase small fw-semibold mb-1">Detail Kelas</p>
                            <h3 class="mb-1 text-white">{{ $classes->name }}</h3>
                            <p class="mb-0 text-white-50">{{ $classes->major ?: 'Program keahlian belum diatur' }}</p>
                        </div>
                        @if($classes->is_archived)
                            <span class="badge bg-warning text-dark">{{ __('index.archived') }}</span>
                        @else
                            <span class="badge bg-success">{{ __('index.active') }}</span>
                        @endif
                    </div>
                </div>
                <div class="class-overview__body">
                    <div class="class-stat d-flex align-items-center justify-content-between p-3 mb-3">
                        <div>
                            <p class="text-muted small mb-1">Total Siswa</p>
                            <h3 class="mb-0">{{ $students->count() }}</h3>
                        </div>
                        <div class="text-primary"><i class="mdi mdi-account-group mdi-36px"></i></div>
                    </div>
                    <div class="class-overview__meta"><i class="mdi mdi-barcode text-primary"></i><span>{{ $classes->code }}</span></div>
                    <div class="class-overview__meta"><i class="mdi mdi-school-outline text-primary"></i><span>{{ __('index.grade') }} {{ $classes->grade }}</span></div>
                    <div class="class-overview__meta"><i class="mdi mdi-calendar-range text-primary"></i><span>{{ $classes->academic_year }}</span></div>
                    @if($currentSemesterLabel)
                        <div class="class-overview__meta"><i class="mdi mdi-book-open-page-variant text-primary"></i><span>{{ __('index.semester') }} {{ $currentSemesterLabel }}</span></div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            @can('classes.edit')
                <div class="card mb-3">
                    <div class="card-header bg-primary">
                        <h5 class="card-title mb-0 text-white">
                            <i class="mdi mdi-flash me-2"></i>{{ __('index.quick_actions') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="editClassFromShow('{{ $classes->id }}')">
                                <i class="mdi mdi-pencil me-1"></i> {{ __('index.edit_class') }}
                            </button>
                            <button class="btn btn-outline-info" onclick="exportStudents()">
                                <i class="mdi mdi-file-excel me-1"></i> {{ __('index.export_data') }}
                            </button>
                            <button class="btn btn-outline-success" onclick="addStudents()">
                                <i class="mdi mdi-account-plus me-1"></i> {{ __('index.add_students') }}
                            </button>
                            <button class="btn btn-outline-warning" onclick="promoteStudents()">
                                <i class="mdi mdi-arrow-up-bold me-1"></i> {{ __('index.promote_classes') }}
                            </button>
                            <button class="btn btn-outline-secondary" onclick="toggleArchive()">
                                <i class="mdi mdi-archive me-1"></i> {{ $classes->is_archived ? __('index.unarchive') : __('index.archive') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Main Content -->
        <div class="col-xl-9">
            <!-- Tabs Navigation -->
            <div class="card class-tabs mb-3">
                <div class="card-body p-0">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-bs-toggle="tab" href="#students-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-account-multiple"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-account-multiple me-2"></i>{{ __('index.student_list') }}</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#attendance-in-out-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-clock-in"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-clock-in me-2"></i>{{ __('index.attendance_in_out') }}</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#attendance-subject-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-book-open-variant"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-book-open-variant me-2"></i>{{ __('index.attendance_subject') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Students Tab -->
                <div class="tab-pane active" id="students-tab" role="tabpanel">
                    <div class="card student-directory">
                        <div class="card-header">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                <div>
                                    <p class="text-uppercase text-muted small fw-semibold mb-1">Data Kelas</p>
                                    <h4 class="mb-1">Daftar Siswa</h4>
                                    <p class="text-muted mb-0">{{ $students->count() }} siswa terdaftar di {{ $classes->name }}</p>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <div class="input-group" style="width: 250px;">
                                        <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                                        <input type="text" class="form-control" placeholder="{{ __('index.search_student') }}" id="search-student">
                                    </div>
                                    @can('classes.create')
                                        <button type="button" class="btn btn-primary" onclick="addStudents()">
                                            <i class="mdi mdi-plus"></i> {{ __('index.add') }}
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Students Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('index.no') }}</th>
                                            <th>{{ __('index.nisn') }}</th>
                                            <th>{{ __('index.student_name') }}</th>
                                            <th>{{ __('index.gender') }}</th>
                                            <th>{{ __('index.status') }}</th>
                                            @can('classes.edit')
                                            <th>{{ __('index.actions') }}</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody id="students-tbody">
                                        @forelse ($students as $index => $student)
                                        <tr class="student-row" data-name="{{ $student->name ?? '' }}" data-nisn="{{ $student->nisn ?? '' }}">
                                            <td><span class="student-number">{{ $index + 1 }}</span></td>
                                            <td>{{ $student->nisn ?? '-' }}</td>
                                            <td>
                                                @if($student->user_id)
                                                    <a href="{{ route('profile.show') }}?user_id={{ $student->user_id }}" class="student-profile-link text-decoration-none">
                                                        {{ $student->name }}
                                                    </a>
                                                @else
                                                    {{ $student->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(($student->gender ?? '') == 'laki-laki')
                                                    <span class="badge bg-primary">L</span>
                                                @else
                                                    <span class="badge bg-info">P</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-success">Aktif</span></td>
                                            @can('classes.edit')
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="removeStudentFromClass('{{ $student->id }}', '{{ $student->name }}')" title="Hapus dari Kelas">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-center">
                                                    <i class="mdi mdi-account-multiple display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted">{{ __('index.no_students_yet') }}</h5>
                                                    <p class="text-muted">{{ __('index.click_add_students') }}</p>
                                                    <button type="button" class="btn btn-primary" onclick="addStudents()">
                                                        <i class="mdi mdi-plus"></i> {{ __('index.add_students') }}
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
                                        <i class="mdi mdi-refresh"></i> {{ __('index.refresh') }}
                                    </button>
                                    @can('attendances.create')
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-file-export"></i> {{ __('index.export_data') }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="exportAttendance('excel')">
                                                        <i class="mdi mdi-file-excel me-2 text-success"></i>{{ __('index.export_excel') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="exportAttendance('pdf')">
                                                        <i class="mdi mdi-file-pdf me-2 text-danger"></i>{{ __('index.export_pdf') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endcan
                                </div>
                            </div>

                            <!-- Attendance Summary -->
                            <div class="row mb-4" id="attendanceSummary" style="display: flex;">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-success mb-1">{{ $attendanceData->where('status', 'hadir')->count() ?? 0 }}</h5>
                                        <small class="text-muted">{{ __('index.present') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-warning mb-1">{{ $attendanceData->where('status', 'izin')->count() ?? 0 }}</h5>
                                        <small class="text-muted">{{ __('index.permission') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-light text-dark mb-1">{{ $attendanceData->where('status', 'sakit')->count() ?? 0 }}</h5>
                                        <small class="text-muted">{{ __('index.sick') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-danger mb-1">{{ $attendanceData->where('status', 'alfa')->count() ?? 0 }}</h5>
                                        <small class="text-muted">{{ __('index.absent') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Actions -->
                            <div id="attendanceActions" style="display: block;">
                            </div>

                            <!-- Attendance Calendar/Table -->
                            <div class="table-responsive">
                                <!-- Loading Indicator -->
                                <div id="attendanceLoadingIndicator" class="text-center py-5" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">{{ __('index.loading') }}...</span>
                                    </div>
                                    <p class="text-muted mt-2">{{ __('index.loading_attendance_data') }}...</p>
                                </div>
                                
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light" id="attendanceHeader">
                                        <tr>
                                            <th width="50">{{ __('index.no') }}</th>
                                            <th>{{ __('index.nisn') }}</th>
                                            <th>{{ __('index.student_name') }}</th>
                                            @for($day = 1; $day <= ($daysInMonth ?? 30); $day++)
                                                <th class="text-center" style="min-width: 40px;">{{ $day }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceBody">
                                        @forelse ($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->nisn ?? '-' }}</td>
                                            <td>
                                                @if($student->user_id)
                                                    <a href="{{ route('profile.show') }}?user_id={{ $student->user_id }}" class="text-primary text-decoration-none fw-medium">
                                                        {{ $student->name }}
                                                    </a>
                                                @else
                                                    {{ $student->name }}
                                                @endif
                                            </td>
                                            @for($day = 1; $day <= ($daysInMonth ?? 30); $day++)
                                                <td class="text-center">
                                                    @php
                                                        $attendance = $attendanceData[$student->id][$day] ?? null;
                                                        // Cek apakah ada siswa lain yang sudah absen untuk hari ini
                                                        $hasAttendanceForDay = false;
                                                        foreach ($attendanceData as $studentId => $days) {
                                                            if (isset($days[$day]) && $days[$day]->isNotEmpty()) {
                                                                $hasAttendanceForDay = true;
                                                                break;
                                                            }
                                                        }
                                                        // Cek apakah hari ini sudah lewat
                                                        $currentDate = date('Y-m-d');
                                                        $selectedMonth = isset($currentMonth) ? substr($currentMonth, 0, 7) : date('Y-m');
                                                        $dayDate = $selectedMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                                        $isPastDate = $dayDate < $currentDate;
                                                        $isToday = $dayDate == $currentDate;
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
                                                        @if($isPastDate && $hasAttendanceForDay)
                                                            <span class="badge bg-danger">A</span>
                                                        @elseif($isToday)
                                                            <span class="text-muted">-</span>
                                                        @elseif($hasAttendanceForDay)
                                                            <span class="badge bg-danger">A</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ ($daysInMonth ?? 30) + 3 }}" class="text-center py-3">
                                                <p class="text-muted">{{ __('index.no_attendance_data') }}</p>
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
                                <div class="col-md-4">
                                    <label for="subjectFilter" class="form-label">{{ __('index.subject') }}:</label>
                                    <select id="subjectFilter" class="form-select">
                                        <option value="">{{ __('index.select_subject') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="subjectMonthFilter" class="form-label">{{ __('index.month') }}:</label>
                                    <input type="month" class="form-control" id="subjectMonthFilter" value="{{ $currentMonth ?? date('Y-m') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label><br>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="button" id="loadSubjectAttendance" class="btn btn-primary">
                                            <i class="mdi mdi-refresh me-1"></i>{{ __('index.load_data') }}
                                        </button>
                                        @can('lesson_attendances.create')
                                            <button type="button" id="markSubjectAttendance" class="btn btn-success">
                                                <i class="mdi mdi-check me-1"></i>{{ __('index.mark_attendance') }}
                                            </button>
                                        @endcan
                                        <button type="button" class="btn btn-success" onclick="exportLessonAttendanceExcel()">
                                            <i class="mdi mdi-file-excel me-1"></i> Export Excel
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="exportLessonAttendancePdf()">
                                            <i class="mdi mdi-file-pdf me-1"></i> Export PDF
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mb-3" id="subjectAttendanceActions" style="display: none;">
                                <div class="col-12">
                                    <button type="button" id="editSubjectModeBtn" class="btn btn-warning">
                                        <i class="mdi mdi-pencil me-1"></i>{{ __('index.edit_mode') }}
                                    </button>
                                    <button type="button" id="saveSubjectAttendanceBtn" class="btn btn-success" style="display: none;">
                                        <i class="mdi mdi-content-save me-1"></i>{{ __('index.save_all') }}
                                    </button>
                                    <button type="button" id="hadirkanSemuaSubjectBtn" class="btn btn-primary" style="display: none;">
                                        <i class="mdi mdi-check-all me-1"></i>{{ __('index.mark_all_present') }}
                                    </button>
                                    <button type="button" id="alphakanSemuaSubjectBtn" class="btn btn-danger" style="display: none;">
                                        <i class="mdi mdi-close me-1"></i>{{ __('index.mark_all_absent') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Subject Attendance Summary -->
                            <div class="row mb-4" id="subjectAttendanceSummary" style="display: none;">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-success mb-1" id="subjectAttendanceHadir">0</h5>
                                        <small class="text-muted">{{ __('index.present') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-warning mb-1" id="subjectAttendanceIzin">0</h5>
                                        <small class="text-muted">{{ __('index.permission') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-light text-dark mb-1" id="subjectAttendanceSakit">0</h5>
                                        <small class="text-muted">{{ __('index.sick') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h5 class="text-danger mb-1" id="subjectAttendanceAlpa">0</h5>
                                        <small class="text-muted">{{ __('index.absent') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Attendance Calendar/Table -->
                            <div class="table-responsive">
                                <table id="subjectAttendanceTable" class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">{{ __('index.no') }}</th>
                                            <th>{{ __('index.nisn') }}</th>
                                            <th>{{ __('index.student_name') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subjectAttendanceBody">
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="text-center">
                                                    <i class="mdi mdi-book-open-variant display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted">{{ __('index.select_subject') }}</h5>
                                                    <p class="text-muted">{{ __('index.select_subject_month_attendance') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Loading Indicator -->
                            <div id="subjectLoadingIndicator" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">{{ __('index.loading') }}...</span>
                                </div>
                                <p class="mt-2">{{ __('index.loading_attendance_data') }}...</p>
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
                        <i class="mdi mdi-account-plus me-2"></i>{{ __('index.add_students_to_class') }} {{ $classes->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <i class="mdi mdi-information me-2 mt-1"></i>
                        <div>
                            <strong>{{ __('index.information') }}:</strong> {{ __('index.only_students_with_status') }} <span class="badge bg-success">{{ __('index.student') }}</span> {{ __('index.can_be_added_to_class') }}.
                            {{ __('index.students_with_status') }} <span class="badge bg-warning">{{ __('index.prospective_student') }}</span> {{ __('index.will_not_be_displayed') }}.
                        </div>
                    </div>
                    <form action="{{ route('classes.bulk-assign', $classes->id) }}" method="POST" id="bulkAssignForm">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="fw-medium">{{ __('index.select_students') }}</span>
                                <span class="badge bg-primary ms-2" id="selectedCountBadge">0 {{ __('index.selected') }}</span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="selectAll()">
                                    <i class="mdi mdi-check-all me-1"></i>{{ __('index.select_all') }}
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="deselectAll()">
                                    <i class="mdi mdi-close me-1"></i>{{ __('index.deselect_all') }}
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 position-relative">
                            <i class="mdi mdi-magnify position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                            <input type="text" class="form-control ps-5" id="searchAvailableStudent" placeholder="{{ __('index.search_name_or_nisn') }}">
                        </div>
                        <div class="students-list" id="availableStudentsList" style="max-height: 420px; overflow-y: auto;">
                            @foreach($availableStudents as $student)
                            <div class="student-item mb-2">
                                <input class="form-check-input student-checkbox" type="checkbox"
                                    id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}">
                                <label class="student-label" for="student_{{ $student->id }}">
                                    <div class="student-avatar">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div class="student-info">
                                        <div class="student-name">{{ $student->name }}</div>
                                        <div class="student-meta">{{ $student->nisn }} · {{ $student->user->email ?? 'No Email' }}</div>
                                    </div>
                                    <div class="student-check">
                                        <i class="mdi mdi-check-circle text-primary"></i>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                            <div id="noResultsMessage" class="text-center py-4 text-muted d-none">
                                <i class="mdi mdi-magnify display-6"></i>
                                <p class="mb-0 mt-2">{{ __('index.no_students_found') }}</p>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="mdi mdi-close me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary" id="btnAddSelected">
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
                    <h5 class="modal-title" id="modalTitle">{{ __('index.edit_class') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="classForm" action="/classes" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('index.class_name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('index.class_code') }}</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">{{ __('index.grade') }}</label>
                            <select class="form-control" id="grade" name="grade" required>
                                <option value="">{{ __('index.select_grade') }}</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="major" class="form-label">{{ __('index.major') }}</label>
                            <input type="text" class="form-control" id="major" name="major" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('index.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('index.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Class Modal -->
    <div class="modal fade" id="class-modal" tabindex="-1" aria-labelledby="class-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="class-modal-label">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form will be loaded here -->
                </div>
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
                    html: 'Tidak ada siswa dengan status <b>Siswa</b> yang dapat ditambahkan.<br><br>' +
                        '<small>Catatan: Hanya siswa dengan status "Siswa" yang dapat ditambahkan ke kelas. ' +
                        'Siswa dengan status "Calon Siswa" perlu diubah statusnya terlebih dahulu.</small>',
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
            try {
                const month = document.getElementById('attendance-month').value;
                
                console.log('Loading attendance data for month:', month);
                
                if (!month) {
                    console.log('No month selected, skipping load');
                    return;
                }
                
                // Show loading
                const loadingIndicator = document.getElementById('attendanceLoadingIndicator');
                const attendanceBody = document.getElementById('attendanceBody');
                
                if (loadingIndicator) loadingIndicator.style.display = 'block';
                if (attendanceBody) attendanceBody.innerHTML = '';
                
                // Parse month to get year and month
                const [year, monthNum] = month.split('-');
                const daysInMonth = new Date(year, monthNum, 0).getDate();
                
                // Update table header first
                updateAttendanceHeader(year, monthNum);
                
                fetch(`/lesson-attendances/get-general-attendance-calendar?class_id=${currentClassId}&year=${year}&month=${monthNum}`)
                    .then(response => response.json())
                    .then(data => {
                        if (loadingIndicator) loadingIndicator.style.display = 'none';
                        
                        if (data.success) {
                            displayAttendanceCalendar(data.data, data.students, year, monthNum);
                            updateAttendanceSummary(data.summary);
                            
                            const actionsElement = document.getElementById('attendanceActions');
                            const summaryElement = document.getElementById('attendanceSummary');
                            
                            if (actionsElement) actionsElement.style.display = 'block';
                            if (summaryElement) summaryElement.style.display = 'flex';
                        } else {
                            if (attendanceBody) {
                                attendanceBody.innerHTML = 
                                    '<tr>' +
                                        '<td colspan="' + (daysInMonth + 3) + '" class="text-center py-5">' +
                                            '<div class="text-center">' +
                                                '<i class="mdi mdi-information display-4 text-muted mb-3"></i>' +
                                                '<h5 class="text-muted">Tidak Ada Data</h5>' +
                                                '<p class="text-muted">' + (data.message || 'Tidak ada data absensi untuk bulan ini') + '</p>' +
                                            '</div>' +
                                        '</td>' +
                                    '</tr>';
                            }
                            
                            const actionsElement = document.getElementById('attendanceActions');
                            const summaryElement = document.getElementById('attendanceSummary');
                            
                            if (actionsElement) actionsElement.style.display = 'none';
                            if (summaryElement) summaryElement.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading attendance data:', error);
                        if (loadingIndicator) loadingIndicator.style.display = 'none';
                        if (attendanceBody) {
                            attendanceBody.innerHTML = 
                                '<tr>' +
                                    '<td colspan="' + (daysInMonth + 3) + '" class="text-center py-5">' +
                                        '<div class="text-center">' +
                                            '<i class="mdi mdi-alert-circle display-4 text-danger mb-3"></i>' +
                                            '<h5 class="text-danger">Error</h5>' +
                                            '<p class="text-muted">Gagal memuat data absensi. Silakan coba lagi.</p>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>';
                        }
                        
                        const actionsElement = document.getElementById('attendanceActions');
                        const summaryElement = document.getElementById('attendanceSummary');
                        
                        if (actionsElement) actionsElement.style.display = 'none';
                        if (summaryElement) summaryElement.style.display = 'none';
                    });
            } catch (error) {
                console.error('Error in loadAttendanceData:', error);
            }
        }

        function exportAttendance(format) {
            const month = document.getElementById('attendance-month').value;
            const classId = '{{ $classes->id }}';
            
            if (format === 'excel') {
                window.location.href = `/classes/${classId}/export-attendance-excel?month=${month}`;
            } else if (format === 'pdf') {
                window.location.href = `/classes/${classId}/export-attendance-pdf?month=${month}`;
            }
        }

        function exportLessonAttendance(format) {
            const subjectId = document.getElementById('subjectFilter').value;
            const month = document.getElementById('subjectMonthFilter').value;
            const classId = '{{ $classes->id }}';

            if (!subjectId) {
                Swal.fire({ icon: 'warning', title: 'Pilih Mata Pelajaran', text: 'Silakan pilih mata pelajaran terlebih dahulu.', confirmButtonColor: '#3085d6' });
                return;
            }
            if (!month) {
                Swal.fire({ icon: 'warning', title: 'Pilih Bulan', text: 'Silakan pilih bulan terlebih dahulu.', confirmButtonColor: '#3085d6' });
                return;
            }

            const endpoint = format === 'excel' ? '/lesson-attendances/export-excel' : '/lesson-attendances/print-pdf';
            window.open(`${endpoint}?class_id=${classId}&subject_id=${subjectId}&month=${month}`, '_blank');
        }

        function exportLessonAttendanceExcel() {
            exportLessonAttendance('excel');
        }

        function exportLessonAttendancePdf() {
            exportLessonAttendance('pdf');
        }

        function displayAttendanceCalendar(attendanceData, students, year, month) {
            const daysInMonth = new Date(year, month, 0).getDate();
            let tableHTML = '';
            
            students.forEach((student, index) => {
                tableHTML += '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + (student.nisn || '-') + '</td>' +
                    '<td>' +
                        (student.user_id ? 
                            '<a href="/profile?user_id=' + student.user_id + '" class="text-primary text-decoration-none fw-medium">' + student.name + '</a>' : 
                            student.name
                        ) +
                    '</td>';
                
                // Add attendance data for each day
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = year + '-' + month.toString().padStart(2, '0') + '-' + day.toString().padStart(2, '0');
                    const attendance = attendanceData.find(a => a.student_id === student.id && a.date === dateStr);
                    
                    if (attendance) {
                        let badgeClass = '';
                        let statusText = '';
                        
                        switch(attendance.check_in_status) {
                            case 'hadir':
                            case 'tepat':
                                badgeClass = 'bg-success';
                                statusText = 'H';
                                break;
                            case 'terlambat':
                                badgeClass = 'bg-warning';
                                statusText = 'T';
                                break;
                            case 'izin':
                                badgeClass = 'bg-info';
                                statusText = 'I';
                                break;
                            case 'sakit':
                                badgeClass = 'bg-secondary';
                                statusText = 'S';
                                break;
                            case 'alpha':
                            case 'alfa':
                                badgeClass = 'bg-danger';
                                statusText = 'A';
                                break;
                            default:
                                badgeClass = 'bg-light';
                                statusText = '-';
                        }
                        
                        tableHTML += '<td class="text-center">' +
                            '<span class="badge ' + badgeClass + '">' + statusText + '</span>' +
                            '</td>';
                    } else {
                        tableHTML += '<td class="text-center">' +
                            '<span class="badge bg-light">-</span>' +
                            '</td>';
                    }
                }
                
                tableHTML += '</tr>';
            });
            
            document.getElementById('attendanceBody').innerHTML = tableHTML;
        }

        function updateAttendanceSummary(summary) {
            if (summary) {
                try {
                    // Update summary counts with safer selectors
                    const hadirElement = document.querySelector('#attendanceSummary .text-success h5');
                    if (hadirElement) hadirElement.textContent = summary.hadir || 0;
                    
                    const izinElement = document.querySelector('#attendanceSummary .text-warning h5');
                    if (izinElement) izinElement.textContent = summary.izin || 0;
                    
                    // Try multiple selectors for sakit (could be text-secondary or text-light text-dark)
                    const sakitElement = document.querySelector('#attendanceSummary .text-secondary h5') || 
                                    document.querySelector('#attendanceSummary .text-light.text-dark h5');
                    if (sakitElement) sakitElement.textContent = summary.sakit || 0;
                    
                    const alphaElement = document.querySelector('#attendanceSummary .text-danger h5');
                    if (alphaElement) alphaElement.textContent = summary.alpha || 0;
                } catch (error) {
                    console.error('Error updating attendance summary:', error);
                }
            }
        }

        function updateAttendanceHeader(year, month) {
            try {
                const daysInMonth = new Date(year, month, 0).getDate();
                const headerElement = document.getElementById('attendanceHeader');
                
                // Get month name
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const monthName = monthNames[parseInt(month) - 1];
                
                if (headerElement) {
                    let headerHTML = '<tr>' +
                        '<th width="50" rowspan="2">No</th>' +
                        '<th rowspan="2">NISN</th>' +
                        '<th rowspan="2">Nama Siswa</th>' +
                        '<th colspan="' + daysInMonth + '" class="text-center">' + monthName + ' ' + year + '</th>' +
                        '</tr>' +
                        '<tr>';
                    
                    // Add day columns
                    for (let day = 1; day <= daysInMonth; day++) {
                        headerHTML += '<th class="text-center" style="min-width: 40px;">' + day + '</th>';
                    }
                    
                    headerHTML += '</tr>';
                    headerElement.innerHTML = headerHTML;
                }
            } catch (error) {
                console.error('Error updating attendance header:', error);
            }
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
        function updateSelectedState(checkbox) {
            const item = checkbox.closest('.student-item');
            if (checkbox.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.student-checkbox:checked').length;
            const badge = document.getElementById('selectedCountBadge');
            const btn = document.getElementById('btnAddSelected');
            if (badge) {
                badge.textContent = checked + ' {{ __("index.selected") }}';
            }
            if (btn) {
                btn.disabled = checked === 0;
            }
        }

        function selectAll() {
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.checked = true;
                updateSelectedState(checkbox);
            });
        }

        function deselectAll() {
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.checked = false;
                updateSelectedState(checkbox);
            });
        }

        // Search functionality for available students
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchAvailableStudent');
            const noResults = document.getElementById('noResultsMessage');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const studentItems = document.querySelectorAll('#availableStudentsList .student-item');
                    let visibleCount = 0;

                    studentItems.forEach(function(item) {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(value)) {
                            item.style.display = 'block';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    if (noResults) {
                        noResults.classList.toggle('d-none', visibleCount > 0);
                    }
                });
            }

            // Initialize checkbox selection state
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedState(this);
                });
                updateSelectedState(checkbox);
            });

            updateSelectedCount();
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
                    loadBtn.addEventListener('click', function() {
                        const subjectId = document.getElementById('subjectFilter').value;
                        const month = document.getElementById('subjectMonthFilter').value;
                        
                        if (!subjectId) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian',
                                text: 'Pilih mata pelajaran terlebih dahulu',
                                confirmButtonColor: '#3085d6'
                            });
                            return;
                        }
                        
                        if (!month) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian',
                                text: 'Pilih bulan terlebih dahulu',
                                confirmButtonColor: '#3085d6'
                            });
                            return;
                        }
                        
                        loadSubjectAttendanceData();
                    });
                }
                
                const markBtn = document.getElementById('markSubjectAttendance');
                if (markBtn) {
                    markBtn.addEventListener('click', markSubjectAttendance);
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
                
                // Auto-load data when subject or month changes
                const subjectFilter = document.getElementById('subjectFilter');
                const monthFilter = document.getElementById('subjectMonthFilter');
                
                if (subjectFilter) {
                    subjectFilter.addEventListener('change', function() {
                        if (this.value && monthFilter.value) {
                            console.log('Subject changed, auto-loading data...');
                            loadSubjectAttendanceData();
                        }
                    });
                }
                
                if (monthFilter) {
                    monthFilter.addEventListener('change', function() {
                        if (this.value && subjectFilter.value) {
                            console.log('Month changed, auto-loading data...');
                            loadSubjectAttendanceData();
                        }
                    });
                }
                
                // Auto-load data when attendance month changes
                const attendanceMonthFilter = document.getElementById('attendance-month');
                if (attendanceMonthFilter) {
                    attendanceMonthFilter.addEventListener('change', function() {
                        try {
                            console.log('Attendance month changed, auto-loading data...');
                            loadAttendanceData();
                        } catch (error) {
                            console.error('Error in attendance month change handler:', error);
                        }
                    });
                }
            } catch (error) {
                console.error('Error setting up event listeners:', error);
            }
        });

        function loadSubjects() {
            console.log('Loading subjects for class:', currentClassId);
            
            // Get subjects from schedule data for this class
            @php
                // Debug: Check schedules for this class
                $allSchedules = \App\Models\Schedule::where('class_id', $classes->id)->get();
                $debugInfo = [
                    'class_id' => $classes->id,
                    'total_schedules' => $allSchedules->count(),
                    'schedules' => $allSchedules->map(function($s) {
                        return [
                            'subject_name' => $s->subject->name ?? 'No Subject',
                            'academic_year' => $s->academic_year,
                            'semester' => $s->semester
                        ];
                    })->toArray()
                ];
                
                // Get the academic year that actually has schedule data
                $scheduleAcademicYear = \App\Models\Schedule::where('class_id', $classes->id)
                        ->distinct()
                        ->pluck('academic_year')
                        ->first() ?? \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
                        
                // Get subjects from schedules, filtered by active semester
                $activeSemester = \App\Models\Semester::where('is_active', true)->first();
                $academicYear = $activeSemester ? $activeSemester->academic_year : null;
                $semesterType = $activeSemester ? $activeSemester->semester_type : null;
                
                $schedulesQuery = \App\Models\Schedule::where('class_id', $classes->id);
                
                if ($academicYear && $semesterType) {
                    $schedulesQuery->where('academic_year', $academicYear)
                                ->where('semester', $semesterType);
                } else {
                    // Fallback to schedule academic year if no active semester
                    $schedulesQuery->where('academic_year', $scheduleAcademicYear);
                }
                
                $schedules = $schedulesQuery->with('subject')->get()->unique('subject_id');
                $fallbackSubjects = $schedules->pluck('subject')->unique('id')->values()->toArray();
            @endphp
            
            console.log('Debug info:', @json($debugInfo));
            console.log('Available subjects:', @json($fallbackSubjects));
            
            if (@json($fallbackSubjects) && @json($fallbackSubjects).length > 0) {
                const fallbackSubjects = @json($fallbackSubjects);
                console.log('Loading subjects into dropdown:', fallbackSubjects);
                const select = document.getElementById('subjectFilter');
                if (select) {
                    select.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                    
                    fallbackSubjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        select.appendChild(option);
                    });
                    
                    console.log('Subjects loaded successfully:', fallbackSubjects.length);
                    
                    // Auto-load data if month is already selected
                    const monthFilter = document.getElementById('subjectMonthFilter');
                    if (monthFilter && monthFilter.value) {
                        // Check if there's a subject already selected (from previous session)
                        const currentSubject = select.value;
                        if (currentSubject) {
                            loadSubjectAttendanceData();
                        }
                    }
                } else {
                    console.error('Subject filter dropdown not found');
                }
            } else {
                console.warn('No subjects found for this class');
                const select = document.getElementById('subjectFilter');
                if (select) {
                    select.innerHTML = '<option value="">Tidak ada mata pelajaran</option>';
                }
            }
        }

        function loadSubjectAttendanceData() {
            const subjectId = document.getElementById('subjectFilter').value;
            const month = document.getElementById('subjectMonthFilter').value;
            
            console.log('Loading subject attendance data:', { subjectId, month });
            
            if (!subjectId) {
                console.log('No subject selected, skipping load');
                return;
            }
            
            if (!month) {
                console.log('No month selected, skipping load');
                return;
            }
            
            // Show loading
            document.getElementById('subjectLoadingIndicator').style.display = 'block';
            document.getElementById('subjectAttendanceBody').innerHTML = '';
            
            // Parse month to get year and month
            const [year, monthNum] = month.split('-');
            const daysInMonth = new Date(year, monthNum, 0).getDate();
            
            fetch(`/lesson-attendances/get-attendance-calendar?class_id=${currentClassId}&subject_id=${subjectId}&year=${year}&month=${monthNum}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('subjectLoadingIndicator').style.display = 'none';
                    
                    if (data.success) {
                        displaySubjectAttendanceCalendar(data.data, data.students, year, monthNum);
                        updateSubjectAttendanceSummary(data.summary);
                        document.getElementById('subjectAttendanceActions').style.display = 'block';
                        document.getElementById('subjectAttendanceSummary').style.display = 'flex';
                    } else {
                        document.getElementById('subjectAttendanceBody').innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center py-5">
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

        function markSubjectAttendance() {
            const subjectId = document.getElementById('subjectFilter').value;
            const month = document.getElementById('subjectMonthFilter').value;
            
            if (!subjectId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Pilih mata pelajaran terlebih dahulu',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            if (!month) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Pilih bulan terlebih dahulu',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            // Open lesson attendance modal with pre-selected class and subject
            fetch(`/lesson-attendances/create?class_id=${currentClassId}&subject_id=${subjectId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('lesson-attendance-modal-label').textContent = data.title;
                    document.querySelector('#lesson-attendance-modal .modal-body').innerHTML = data.html;
                    
                    const modal = new bootstrap.Modal(document.getElementById('lesson-attendance-modal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat form.'
                });
            });
        }

        function displaySubjectAttendanceCalendar(attendanceData, students, year, month) {
            const tbody = document.getElementById('subjectAttendanceBody');
            const thead = document.querySelector('#subjectAttendanceTable thead tr');
            
            // Get unique dates from attendance data
            const uniqueDates = [...new Set(attendanceData.map(a => a.date))].sort();
            
            if (uniqueDates.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <div class="text-center">
                                <i class="mdi mdi-information display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak Ada Data</h5>
                                <p class="text-muted">Belum ada data absensi untuk mata pelajaran ini</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Update table header with dynamic dates
            let headerHtml = '<th width="50">No</th><th>NISN</th><th>Nama Siswa</th>';
            uniqueDates.forEach(date => {
                const day = new Date(date).getDate();
                const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                headerHtml += `<th class="text-center" style="min-width: 40px;" title="${formattedDate}">${day}</th>`;
            });
            thead.innerHTML = headerHtml;
            
            // Calculate total columns for empty state
            const totalColumns = uniqueDates.length + 3; // No + NISN + Nama Siswa + Dates
            
            // Clear and rebuild table body
            tbody.innerHTML = '';
            
            students.forEach((student, index) => {
                const row = document.createElement('tr');
                let html = `
                    <td>${index + 1}</td>
                    <td>${student.nisn ?? '-'}</td>
                    <td>
                        ${student.user_id ? 
                            `<a href="/profile?user_id=${student.user_id}" class="text-primary text-decoration-none fw-medium">
                                ${student.name}
                            </a>` : 
                            student.name
                        }
                    </td>
                `;
                
                // Add cells for each date that has data
                uniqueDates.forEach(date => {
                    const attendance = attendanceData.find(a =>
                        a.student_id === student.id &&
                        a.date === date
                    );

                    // Cek apakah ada siswa lain yang sudah absen untuk tanggal ini
                    const hasAttendanceForDate = attendanceData.some(a => a.date === date);

                    // Cek apakah tanggal ini sudah lewat atau hari ini
                    const today = new Date().toISOString().split('T')[0];
                    const isPastDate = date < today;
                    const isToday = date === today;

                    if (attendance) {
                        let statusBadge = '';
                        switch(attendance.check_in_status) {
                            case 'hadir':
                            case 'terlambat':
                                statusBadge = '<span class="badge bg-success">H</span>';
                                break;
                            case 'izin':
                                statusBadge = '<span class="badge bg-warning">I</span>';
                                break;
                            case 'sakit':
                                statusBadge = '<span class="badge bg-light text-dark">S</span>';
                                break;
                            default:
                                statusBadge = '<span class="badge bg-danger">A</span>';
                        }
                        html += `<td class="text-center">${statusBadge}</td>`;
                    } else {
                        // Jika tanggal sudah lewat dan ada siswa yang absen, tampilkan "A"
                        // Jika hari ini, tampilkan "-" meskipun ada siswa yang absen
                        if (isPastDate && hasAttendanceForDate) {
                            html += `<td class="text-center"><span class="badge bg-danger">A</span></td>`;
                        } else if (isToday) {
                            html += `<td class="text-center"><span class="text-muted">-</span></td>`;
                        } else if (hasAttendanceForDate) {
                            html += `<td class="text-center"><span class="badge bg-danger">A</span></td>`;
                        } else {
                            html += `<td class="text-center"><span class="text-muted">-</span></td>`;
                        }
                    }
                });
                
                row.innerHTML = html;
                tbody.appendChild(row);
            });
        }

        function updateSubjectAttendanceSummary(summary) {
            document.getElementById('subjectAttendanceHadir').textContent = summary.hadir || 0;
            document.getElementById('subjectAttendanceIzin').textContent = summary.izin || 0;
            document.getElementById('subjectAttendanceSakit').textContent = summary.sakit || 0;
            document.getElementById('subjectAttendanceAlpa').textContent = summary.alpha || 0;
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

        // Edit class from show page
        function editClassFromShow(id) {
            fetch(`/classes/${id}/edit?redirect_to=show`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('class-modal-label').textContent = data.title;
                    document.querySelector('#class-modal .modal-body').innerHTML = data.html;
                    
                    const modal = new bootstrap.Modal(document.getElementById('class-modal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat form.'
                });
            });
        }

        // Remove student from class
        function removeStudentFromClass(studentId, studentName) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Siswa "${studentName}" akan dihapus dari kelas ini!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to remove student from class
                    fetch(`/classes/remove-student/${studentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Siswa berhasil dihapus dari kelas.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            
                            // Remove the student row from table without refresh
                            const studentRow = document.querySelector(`button[onclick*="${studentId}"]`).closest('tr');
                            if (studentRow) {
                                studentRow.remove();
                                
                                // Check if there are no more students
                                const tbody = document.querySelector('#students-tbody');
                                if (tbody && tbody.children.length === 0) {
                                    tbody.innerHTML = `
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
                                    `;
                                }
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan saat menghapus siswa.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus siswa.'
                        });
                    });
                }
            });
        }
    </script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection