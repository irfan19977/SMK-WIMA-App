@extends ('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-3 col-lg-6">
        <div class="card">
            <div class="card-body card-type-3">
                <div class="row">
                    <div class="col">
                        <h6 class="text-muted mb-0">Total Siswa Aktif</h6>
                        <span class="font-weight-bold mb-0" style="font-size:1.5rem;">{{ \App\Models\Student::whereHas('user', function($q) { $q->where('status', 1); })->count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="card-circle l-bg-orange text-white">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i></span>
                    <span class="text-nowrap">Aktif saat ini</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card">
            <div class="card-body card-type-3">
                <div class="row">
                    <div class="col">
                        <h6 class="text-muted mb-0">Total Guru Aktif</h6>
                        <span class="font-weight-bold mb-0" style="font-size:1.5rem;">{{ \App\Models\Teacher::whereHas('user', function($q) { $q->where('status', 1); })->count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="card-circle l-bg-cyan text-white">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i></span>
                    <span class="text-nowrap">Aktif saat ini</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card">
            <div class="card-body card-type-3">
                <div class="row">
                    <div class="col">
                        <h6 class="text-muted mb-0">Total Kelas</h6>
                        <span class="font-weight-bold mb-0" style="font-size:1.5rem;">{{ \App\Models\Classes::count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="card-circle l-bg-green text-white">
                            <i class="fas fa-door-open"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i></span>
                    <span class="text-nowrap">Total kelas terdaftar</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card">
            <div class="card-body card-type-3">
                <div class="row">
                    <div class="col">
                        <h6 class="text-muted mb-0">Total Mapel</h6>
                        <span class="font-weight-bold mb-0" style="font-size:1.5rem;">{{ \App\Models\Subject::count() }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="card-circle l-bg-purple text-white">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i></span>
                    <span class="text-nowrap">Total mapel terdaftar</span>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Grafik Kehadiran Siswa</h4>
                <div class="card-header-action">
                    <select id="attendanceRange" class="form-control">
                        <option value="daily" {{ request('range', 'daily') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="100"></canvas>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        <div id="attendanceLegend" class="chart-legend"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    use App\Models\Attendance;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    $statuses = ['terlambat', 'tepat', 'izin', 'alpha'];
    $range = request('range', 'daily');

    function getAttendanceData($range, $statuses) {
        $labels = [];
        $datasets = [];
        $now = Carbon::now();

        $chartColors = [
            'terlambat' => 'rgba(255, 99, 132, 0.8)',
            'tepat' => 'rgba(54, 162, 235, 0.8)',
            'izin' => 'rgba(255, 206, 86, 0.8)',
            'alpha' => 'rgba(75, 192, 192, 0.8)',
        ];

        if ($range == 'daily') {
            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i)->format('Y-m-d');
                $labels[] = $now->copy()->subDays($i)->format('d M');
            }
            foreach ($statuses as $status) {
                $data = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i)->format('Y-m-d');
                    $data[] = Attendance::whereDate('created_at', $date)
                        ->where('check_in_status', $status)
                        ->count();
                }
                $datasets[] = [
                    'label' => ucfirst($status),
                    'data' => $data,
                    'borderColor' => $chartColors[$status],
                    'backgroundColor' => $chartColors[$status],
                    'pointBackgroundColor' => $chartColors[$status],
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => $chartColors[$status],
                ];
            }
        } elseif ($range == 'weekly') {
            for ($i = 7; $i >= 0; $i--) {
                $start = $now->copy()->subWeeks($i)->startOfWeek();
                $end = $start->copy()->endOfWeek();
                $labels[] = $start->format('d M') . ' - ' . $end->format('d M');
            }
            foreach ($statuses as $status) {
                $data = [];
                for ($i = 7; $i >= 0; $i--) {
                    $start = $now->copy()->subWeeks($i)->startOfWeek();
                    $end = $start->copy()->endOfWeek();
                    $data[] = Attendance::whereBetween('created_at', [$start, $end])
                        ->where('check_in_status', $status)
                        ->count();
                }
                $datasets[] = [
                    'label' => ucfirst($status),
                    'data' => $data,
                    'borderColor' => $chartColors[$status],
                    'backgroundColor' => $chartColors[$status],
                    'pointBackgroundColor' => $chartColors[$status],
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => $chartColors[$status],
                ];
            }
        } elseif ($range == 'monthly') {
            for ($i = 11; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $labels[] = $date->format('M Y');
            }
            foreach ($statuses as $status) {
                $data = [];
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $data[] = Attendance::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('check_in_status', $status)
                        ->count();
                }
                $datasets[] = [
                    'label' => ucfirst($status),
                    'data' => $data,
                    'borderColor' => $chartColors[$status],
                    'backgroundColor' => $chartColors[$status],
                    'pointBackgroundColor' => $chartColors[$status],
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => $chartColors[$status],
                ];
            }
        } else {
            for ($i = 4; $i >= 0; $i--) {
                $year = $now->copy()->subYears($i)->year;
                $labels[] = $year;
            }
            foreach ($statuses as $status) {
                $data = [];
                for ($i = 4; $i >= 0; $i--) {
                    $year = $now->copy()->subYears($i)->year;
                    $data[] = Attendance::whereYear('created_at', $year)
                        ->where('check_in_status', $status)
                        ->count();
                }
                $datasets[] = [
                    'label' => ucfirst($status),
                    'data' => $data,
                    'borderColor' => $chartColors[$status],
                    'backgroundColor' => $chartColors[$status],
                    'pointBackgroundColor' => $chartColors[$status],
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => $chartColors[$status],
                ];
            }
        }

        return ['labels' => $labels, 'datasets' => $datasets];
    }

    $attendanceData = getAttendanceData($range, $statuses);
@endphp


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Siswa Terlambat - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Jam Masuk</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lateStudents as $index => $student)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->class_name }}</td>
                                <td>
                                    <span class="badge badge-warning">
                                        {{ \Carbon\Carbon::parse($student->check_in)->format('H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="badge badge-danger">Terlambat</div>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h2>Tidak ada siswa yang terlambat</h2>
                                        <p class="lead">Pada tanggal {{ \Carbon\Carbon::parse($date)->format('d F Y') }} tidak ada siswa yang terlambat.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($lateStudents->count() > 0)
            <div class="card-footer text-right">
                <div class="d-inline-block">
                    <small class="text-muted">
                        Total siswa terlambat: <strong>{{ $lateStudents->count() }}</strong>
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let attendanceChart;
        const chartColors = {
            terlambat: 'rgba(255, 99, 132, 0.8)',
            tepat: 'rgba(54, 162, 235, 0.8)',
            izin: 'rgba(255, 206, 86, 0.8)',
            alpha: 'rgba(75, 192, 192, 0.8)'
        };

        function renderAttendanceChart(labels, datasets) {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            if (attendanceChart) attendanceChart.destroy();
            attendanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets.map(ds => ({
                        label: ds.label,
                        data: ds.data,
                        fill: false,
                        borderColor: ds.borderColor,
                        backgroundColor: ds.backgroundColor,
                        pointBackgroundColor: ds.pointBackgroundColor,
                        pointBorderColor: ds.pointBorderColor,
                        pointHoverBackgroundColor: ds.pointHoverBackgroundColor,
                        pointHoverBorderColor: ds.pointHoverBorderColor,
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.4,
                    }))
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false // We'll use a custom legend
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            precision: 0,
                            grid: {
                                color: '#f1f1f1'
                            }
                        }
                    }
                }
            });
            // Custom legend
            const legendContainer = document.getElementById('attendanceLegend');
            legendContainer.innerHTML = attendanceChart.data.datasets.map(ds => `
                <span style="display:inline-flex;align-items:center;margin-right:20px;">
                    <span style="display:inline-block;width:16px;height:16px;background:${ds.borderColor};border-radius:50%;margin-right:8px;"></span>
                    <span>${ds.label}</span>
                </span>
            `).join('');
        }

        // Initial render
        renderAttendanceChart(@json($attendanceData['labels']), @json($attendanceData['datasets']));

        document.getElementById('attendanceRange').addEventListener('change', function() {
            const range = this.value;
            window.location.href = '?range=' + range;
        });
    </script>
    <style>
        .chart-legend span {
            font-size: 15px;
            font-weight: 500;
        }
    </style>
@endpush