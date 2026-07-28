@extends('layouts.master')

@section('title')
    Dashboard Siswa
@endsection

@section('css')
    <!-- apexcharts css -->
    <link href="{{ URL::asset('build/libs/apexcharts/apexcharts.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Dashboard Siswa
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
<div class="row">
    <!-- Student Info Card -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column justify-content-center h-100 text-center">
                <div class="mx-auto mb-3">
                    <div class="rounded-circle border border-3 border-light p-1 d-inline-block">
                        <img src="{{ $student->user && $student->user->photo_path ? asset('storage/' . $student->user->photo_path) : URL::asset('build/images/users/avatar-2.jpg') }}" alt="" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                    </div>
                </div>
                <h5 class="mb-1">{{ $student->name }}</h5>
                <p class="text-muted mb-2">NISN: {{ $student->nisn }}</p>
                <p class="text-muted mb-3">
                    @if($studentClass)
                        {{ $studentClass->name }}<br>
                        {{ $studentClass->major }}<br>
                        {{ $activeSemester?->display_name ?? 'Semester tidak aktif' }}
                    @else
                        Belum memiliki kelas
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Attendance Stats -->
    <div class="col-lg-8">
        <div class="card h-100">
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
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header border-0 align-items-center d-flex pb-0">
                <h4 class="card-title mb-0 flex-grow-1">Statistik Kehadiran</h4>
                <div>
                    <button type="button" class="btn btn-soft-secondary btn-sm filter-chart" data-period="1m">1 Bulan</button>
                    <button type="button" class="btn btn-soft-primary btn-sm filter-chart" data-period="6m">6 Bulan</button>
                    <button type="button" class="btn btn-soft-secondary btn-sm filter-chart" data-period="1y">1 Tahun</button>
                </div>
            </div>
            <div class="card-body">
                <div id="column-chart" class="apex-charts"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header border-0 align-items-center d-flex pb-0">
                <h4 class="card-title mb-0 flex-grow-1">Persentase Kehadiran</h4>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div id="donut-chart" class="apex-charts"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 mb-4">
    <!-- Today's Attendance -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Kehadiran Hari Ini</h4>
                    <a href="{{ route('student.attendance') }}" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-eye"></i> Lihat Detail
                    </a>
                </div>
                @php
                    $today = strtolower(\Carbon\Carbon::now()->locale('id')->isoFormat('dddd'));
                    $now = \Carbon\Carbon::now();
                @endphp
                @if($studentClass)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Absensi Masuk -->
                                <tr>
                                    <td>{{ optional($todayAttendance)->check_in ?? '-' }}</td>
                                    <td><strong>Absensi Masuk</strong></td>
                                    <td>
                                        @if($todayAttendance && $todayAttendance->check_in_status)
                                            @if($todayAttendance->check_in_status == 'tepat')
                                                <span class="badge bg-success">{{ $todayAttendance->check_in_status }}</span>
                                            @elseif($todayAttendance->check_in_status == 'terlambat')
                                                <span class="badge bg-warning">{{ $todayAttendance->check_in_status }}</span>
                                            @elseif($todayAttendance->check_in_status == 'izin')
                                                <span class="badge bg-info">{{ $todayAttendance->check_in_status }}</span>
                                            @elseif($todayAttendance->check_in_status == 'sakit')
                                                <span class="badge bg-primary">{{ $todayAttendance->check_in_status }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $todayAttendance->check_in_status }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum absen</span>
                                        @endif
                                    </td>
                                </tr>

                                @if(isset($orderedSchedules[$today]) && count($orderedSchedules[$today]) > 0)
                                    @foreach($orderedSchedules[$today] as $schedule)
                                        @php
                                            $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                            if ($todayAttendance && $todayAttendance->check_in_status) {
                                                $subjectStatus = $todayAttendance->check_in_status;
                                            } elseif ($now->greaterThan($endTime)) {
                                                $subjectStatus = 'alpha';
                                            } else {
                                                $subjectStatus = 'belum_absen';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                            <td>{{ $schedule->subject->name }}</td>
                                            <td>
                                                @if($subjectStatus == 'tepat')
                                                    <span class="badge bg-success">tepat</span>
                                                @elseif($subjectStatus == 'terlambat')
                                                    <span class="badge bg-warning">terlambat</span>
                                                @elseif($subjectStatus == 'izin')
                                                    <span class="badge bg-info">izin</span>
                                                @elseif($subjectStatus == 'sakit')
                                                    <span class="badge bg-primary">sakit</span>
                                                @elseif($subjectStatus == 'alpha')
                                                    <span class="badge bg-danger">alpha</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum absen</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                <!-- Absensi Pulang -->
                                <tr>
                                    <td>{{ optional($todayAttendance)->check_out ?? '-' }}</td>
                                    <td><strong>Absensi Pulang</strong></td>
                                    <td>
                                        @if($todayAttendance && $todayAttendance->check_out_status)
                                            @if($todayAttendance->check_out_status == 'tepat')
                                                <span class="badge bg-success">{{ $todayAttendance->check_out_status }}</span>
                                            @elseif($todayAttendance->check_out_status == 'lebih_awal')
                                                <span class="badge bg-warning">{{ $todayAttendance->check_out_status }}</span>
                                            @elseif($todayAttendance->check_out_status == 'izin')
                                                <span class="badge bg-info">{{ $todayAttendance->check_out_status }}</span>
                                            @elseif($todayAttendance->check_out_status == 'sakit')
                                                <span class="badge bg-primary">{{ $todayAttendance->check_out_status }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $todayAttendance->check_out_status }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum absen</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-calendar-alert display-4 text-muted"></i>
                        <p class="text-muted mt-3">Siswa belum memiliki kelas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Schedule -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Jadwal Pelajaran Hari Ini</h4>
                    <div class="d-flex align-items-center">
                        @if($activeSemester)
                            <span class="badge bg-info me-2">{{ $activeSemester->display_name }}</span>
                        @endif
                        <a href="{{ route('student.schedule') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                @php
                    $today = strtolower(\Carbon\Carbon::now()->locale('id')->isoFormat('dddd'));
                @endphp
                @if($studentClass && isset($orderedSchedules[$today]) && count($orderedSchedules[$today]) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderedSchedules[$today] as $index => $schedule)
                                    <tr>
                                        @if($index == 0)
                                            <td rowspan="{{ count($orderedSchedules[$today]) }}" class="text-center align-middle">
                                                <strong>{{ ucfirst($today) }}</strong>
                                            </td>
                                        @endif
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                        <td>{{ $schedule->subject->name }}</td>
                                        <td>{{ $schedule->teacher->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-calendar-alert display-4 text-muted"></i>
                        <p class="text-muted mt-3">
                            @if(!$studentClass)
                                Siswa belum memiliki kelas
                            @else
                                Tidak ada jadwal pelajaran hari ini
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Pass data to JavaScript -->
    <script>
        window.chartData = @json($chartData);
        window.donutData = @json($donutData);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Column Chart
            const columnChartOptions = {
                chart: {
                    id: 'student-column-chart',
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Hadir Tepat Waktu',
                    data: window.chartData.onTimeCount
                }, {
                    name: 'Terlambat',
                    data: window.chartData.lateCount
                }],
                colors: ['#34c38f', '#f46a6a'],
                xaxis: {
                    categories: window.chartData.months,
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Kehadiran'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " kali"
                        }
                    }
                }
            };

            const columnChart = new ApexCharts(document.querySelector("#column-chart"), columnChartOptions);
            columnChart.render();

            // Donut Chart
            const donutChartOptions = {
                series: window.donutData.data,
                labels: window.donutData.labels,
                chart: {
                    id: 'student-donut-chart',
                    type: 'donut',
                    height: 350
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontFamily: 'IBM Plex Sans, sans-serif',
                                    fontWeight: 600,
                                    color: undefined
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    fontFamily: 'IBM Plex Sans, sans-serif',
                                    fontWeight: 500,
                                    color: undefined,
                                    formatter: function(val) {
                                        return val + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#373d3f',
                                    fontSize: '14px',
                                    fontFamily: 'IBM Plex Sans, sans-serif',
                                    fontWeight: 600,
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce(function(a, b) {
                                            return a + b;
                                        }, 0) + '%';
                                    }
                                }
                            }
                        }
                    }
                },
                colors: ['#34c38f', '#f46a6a', '#f1b44c', '#6c757d', '#343a40'],
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + '%';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    fontFamily: 'IBM Plex Sans, sans-serif',
                    markers: {
                        width: 10,
                        height: 10
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ' - ' + opts.w.globals.series[opts.seriesIndex] + '%';
                    }
                }
            };

            const donutChart = new ApexCharts(document.querySelector("#donut-chart"), donutChartOptions);
            donutChart.render();

            // Filter chart buttons
            const filterButtons = document.querySelectorAll('.filter-chart');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const period = this.getAttribute('data-period');

                    filterButtons.forEach(btn => {
                        btn.classList.remove('btn-soft-primary');
                        btn.classList.add('btn-soft-secondary');
                    });
                    this.classList.remove('btn-soft-secondary');
                    this.classList.add('btn-soft-primary');

                    loadChartData(period);
                });
            });

            function loadChartData(period) {
                fetch(`/dashboard/student-chart-data?period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        columnChart.updateOptions({
                            xaxis: {
                                categories: data.months
                            },
                            series: [{
                                name: 'Hadir Tepat Waktu',
                                data: data.onTimeCount
                            }, {
                                name: 'Terlambat',
                                data: data.lateCount
                            }]
                        });

                        if (data.donut) {
                            donutChart.updateOptions({
                                labels: data.donut.labels,
                                series: data.donut.data
                            });
                        }
                    });
            }
        });
    </script>
@endsection
