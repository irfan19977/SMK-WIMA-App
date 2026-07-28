@extends('layouts.master')

@section('title')
    Absensi Harian Pelajaran
@endsection

@section('page-title')
    Absensi Harian Pelajaran
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h4 class="card-title mb-0">Kehadiran Mata Pelajaran per Hari</h4>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                </div>

                <p class="text-muted mb-3">
                    Kelas: <strong>{{ $studentClass->name }}</strong> &nbsp;|&nbsp;
                    Semester: <strong>{{ $activeSemester->display_name }}</strong>
                </p>

                {{-- Filter Tanggal --}}
                <form method="GET" action="{{ route('student.lesson-attendance') }}" class="row g-2 align-items-end mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Pilih Minggu (tanggal dalam minggu)</label>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-filter"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-auto">
                        <a href="{{ route('student.lesson-attendance') }}" class="btn btn-secondary">
                            <i class="mdi mdi-refresh"></i> Reset
                        </a>
                    </div>
                </form>

                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <span>
                        <i class="mdi mdi-calendar-week me-1"></i>
                        Periode: <strong>{{ \Carbon\Carbon::parse($weekStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($weekEnd)->format('d M Y') }}</strong>
                    </span>
                    <span class="text-muted">{{ $student->name }} ({{ $student->nisn }})</span>
                </div>

                {{-- Tabel Absensi --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 12%;">Hari</th>
                                <th style="width: 10%;">Tanggal</th>
                                <th style="width: 14%;">Jam</th>
                                <th style="width: 22%;">Mata Pelajaran</th>
                                <th style="width: 22%;">Guru</th>
                                <th style="width: 20%;">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($weeklyAttendance as $day)
                                @if(count($day['rows']) > 0)
                                    @foreach($day['rows'] as $index => $row)
                                        <tr>
                                            @if($index == 0)
                                                <td rowspan="{{ count($day['rows']) }}" class="text-center align-middle fw-bold bg-light">
                                                    {{ $day['day_label'] }}
                                                </td>
                                                <td rowspan="{{ count($day['rows']) }}" class="text-center align-middle">
                                                    {{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}
                                                </td>
                                            @endif
                                            <td>{{ $row['time'] }}</td>
                                            <td>{{ $row['subject'] }}</td>
                                            <td>{{ $row['teacher'] }}</td>
                                            <td>
                                                @if($row['status'] == 'hadir' || $row['status'] == 'tepat')
                                                    <span class="badge bg-success">Hadir</span>
                                                @elseif($row['status'] == 'terlambat')
                                                    <span class="badge bg-warning">Terlambat</span>
                                                @elseif($row['status'] == 'izin')
                                                    <span class="badge bg-info">Izin</span>
                                                @elseif($row['status'] == 'sakit')
                                                    <span class="badge bg-primary">Sakit</span>
                                                @elseif($row['status'] == 'alpha')
                                                    <span class="badge bg-danger">Alpha</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum ada data</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center fw-bold bg-light">{{ $day['day_label'] }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}</td>
                                        <td colspan="4" class="text-center text-muted">Tidak ada jadwal pelajaran</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data minggu ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-3 flex-wrap mt-3">
                    <span class="badge bg-success">Hadir</span>
                    <span class="badge bg-warning">Terlambat</span>
                    <span class="badge bg-info">Izin</span>
                    <span class="badge bg-primary">Sakit</span>
                    <span class="badge bg-danger">Alpha</span>
                    <span class="badge bg-secondary">Belum ada data</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
