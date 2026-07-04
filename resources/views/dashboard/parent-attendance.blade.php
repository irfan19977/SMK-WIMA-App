@extends('layouts.master')

@section('title')
    Kehadiran Anak
@endsection

@section('page-title')
    Kehadiran Anak
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
<div class="row">
    <!-- Student Info Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <img src="{{ asset('build/images/users/user-4.jpg') }}" alt="" class="avatar-img rounded-circle">
                    </div>
                    <h5 class="mb-1">{{ $student->name }}</h5>
                    <p class="text-muted mb-2">NISN: {{ $student->nisn }}</p>
                    <p class="text-muted mb-3">
                        @if($studentClass)
                            {{ $studentClass->name }}<br>
                            {{ $studentClass->major }}
                        @else
                            Belum memiliki kelas
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Stats -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Statistik Kehadiran</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-primary mb-1">{{ $attendanceStats['total'] }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                            <h3 class="text-success mb-1">{{ $attendanceStats['hadir'] }}</h3>
                            <p class="text-muted mb-0">Hadir</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                            <h3 class="text-warning mb-1">{{ $attendanceStats['terlambat'] }}</h3>
                            <p class="text-muted mb-0">Terlambat</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <h3 class="text-info mb-1">{{ $attendanceStats['present_percentage'] }}%</h3>
                            <p class="text-muted mb-0">Kehadiran</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="text-center p-2 bg-light rounded">
                            <span class="text-muted">Izin: </span>
                            <strong>{{ $attendanceStats['izin'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-2 bg-light rounded">
                            <span class="text-muted">Sakit: </span>
                            <strong>{{ $attendanceStats['sakit'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-2 bg-light rounded">
                            <span class="text-muted">Alpha: </span>
                            <strong>{{ $attendanceStats['alpha'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Attendance List -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Riwayat Kehadiran</h4>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('parent.attendance') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua</option>
                                <option value="tepat" {{ request('status') == 'tepat' ? 'selected' : '' }}>Tepat Waktu</option>
                                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i> Filter
                                </button>
                                <a href="{{ route('parent.attendance') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Attendance Table -->
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status Check In</th>
                                    <th>Status Check Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->check_in ?? '-' }}</td>
                                        <td>{{ $attendance->check_out ?? '-' }}</td>
                                        <td>
                                            @if($attendance->check_in_status == 'tepat')
                                                <span class="badge bg-success">{{ $attendance->check_in_status }}</span>
                                            @elseif($attendance->check_in_status == 'terlambat')
                                                <span class="badge bg-warning">{{ $attendance->check_in_status }}</span>
                                            @elseif($attendance->check_in_status == 'izin')
                                                <span class="badge bg-info">{{ $attendance->check_in_status }}</span>
                                            @elseif($attendance->check_in_status == 'sakit')
                                                <span class="badge bg-primary">{{ $attendance->check_in_status }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $attendance->check_in_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_out_status)
                                                @if($attendance->check_out_status == 'tepat')
                                                    <span class="badge bg-success">{{ $attendance->check_out_status }}</span>
                                                @elseif($attendance->check_out_status == 'terlambat')
                                                    <span class="badge bg-warning">{{ $attendance->check_out_status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $attendance->check_out_status }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $attendances->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-calendar-alert display-4 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada data kehadiran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
