@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('css')
    <!-- jsvectormap css -->
    <link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    <span data-translate="page_title">{{ __('index.page_title') }}</span>
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="mdi mdi-account-group"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2" data-translate="total_students">{{ __('index.total_students') }}</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $totalStudents }} <span class="text-muted font-size-16" data-translate="students">{{ __('index.students') }}</span>
                                </h3>
                                <p class="text-muted mb-0 text-truncate"><span
                                        class="badge bg-subtle-success text-success font-size-12 fw-normal me-1"><i
                                            class="mdi mdi-arrow-top-right"></i> 5.2% <span data-translate="increase">{{ __('index.increase') }}</span></span> <span data-translate="vs_last_semester">{{ __('index.vs_last_semester') }}</span></p>
                            </div>
                            <div class="flex-shrink-0 align-self-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn-icon border rounded-circle" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill text-muted font-size-16"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-translate="yearly">{{ __('index.yearly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="monthly">{{ __('index.monthly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="weekly">{{ __('index.weekly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="today">{{ __('index.today') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="mdi mdi-book-open-variant"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2" data-translate="active_classes">{{ __('index.active_classes') }}</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $activeClasses }} <span class="text-muted font-size-16" data-translate="classes">{{ __('index.classes') }}</span>
                                </h3>
                                <p class="text-muted mb-0 text-truncate"><span
                                        class="badge bg-subtle-success text-success font-size-12 fw-normal me-1"><i
                                            class="mdi mdi-arrow-top-right"></i> +8 <span data-translate="classes">{{ __('index.classes') }}</span></span> <span data-translate="vs_last_semester">{{ __('index.vs_last_semester') }}</span></p>
                            </div>
                            <div class="flex-shrink-0 align-self-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn-icon border rounded-circle" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill text-muted font-size-16"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-translate="yearly">{{ __('index.yearly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="monthly">{{ __('index.monthly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="weekly">{{ __('index.weekly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="today">{{ __('index.today') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="mdi mdi-check-circle"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2" data-translate="todays_attendance">{{ __('index.todays_attendance') }}</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $todayAttendance }}<span class="text-muted font-size-16">%</span>
                                </h3>
                                <p class="text-muted mb-0 text-truncate"><span
                                        class="badge bg-subtle-success text-success font-size-12 fw-normal me-1"><i
                                            class="mdi mdi-arrow-top-right"></i> 2.3% <span data-translate="increase">{{ __('index.increase') }}</span></span> <span data-translate="vs_last_week">{{ __('index.vs_last_week') }}</span></p>
                            </div>
                            <div class="flex-shrink-0 align-self-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn-icon border rounded-circle" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill text-muted font-size-16"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-translate="yearly">{{ __('index.yearly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="monthly">{{ __('index.monthly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="weekly">{{ __('index.weekly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="today">{{ __('index.today') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="mdi mdi-calendar"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2" data-translate="subjects">{{ __('index.subjects') }}</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $totalSubjects }} <span
                                        class="text-muted font-size-16" data-translate="mapel">{{ __('index.mapel') }}</span></h3>
                                <p class="text-muted mb-0 text-truncate"><span
                                        class="badge bg-subtle-success text-success font-size-12 fw-normal me-1"><i
                                            class="mdi mdi-arrow-top-right"></i> 6 <span data-translate="mapel">{{ __('index.mapel') }}</span></span> <span data-translate="for_today">{{ __('index.for_today') }}</span></p>
                            </div>
                            <div class="flex-shrink-0 align-self-start">
                                <div class="dropdown">
                                    <a class="dropdown-toggle btn-icon border rounded-circle" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill text-muted font-size-16"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-translate="yearly">{{ __('index.yearly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="monthly">{{ __('index.monthly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="weekly">{{ __('index.weekly') }}</a>
                                        <a class="dropdown-item" href="#" data-translate="today">{{ __('index.today') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ROW -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 align-items-center d-flex pb-0">
                        <h4 class="card-title mb-0 flex-grow-1" data-translate="attendance_statistics">{{ __('index.attendance_statistics') }}</h4>
                        <div>
                            <button type="button" class="btn btn-soft-secondary btn-sm" id="filter-all" data-period="all" data-translate="all">{{ __('index.all') }}
                            </button>
                            <button type="button" class="btn btn-soft-secondary btn-sm" id="filter-1m" data-period="1m" data-translate="one_month">{{ __('index.one_month') }}
                            </button>
                            <button type="button" class="btn btn-soft-secondary btn-sm" id="filter-6m" data-period="6m" data-translate="six_months">{{ __('index.six_months') }}
                            </button>
                            <button type="button" class="btn btn-soft-primary btn-sm" id="filter-1y" data-period="1y" data-translate="one_year">{{ __('index.one_year') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-xl-8 audiences-border">
                                <div id="column-chart" class="apex-charts"></div>
                            </div>
                            <div class="col-xl-4">
                                <div id="donut-chart" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ROW -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 align-items-center d-flex pb-0">
                        <h4 class="card-title mb-0 flex-grow-1" data-translate="late_students_list">{{ __('index.late_students_list') }}</h4>
                        <div>
                            <div class="dropdown">
                                <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="fw-semibold" data-translate="sort_by">{{ __('index.sort_by') }}:</span>
                                    <span class="text-muted" data-translate="yearly">{{ __('index.yearly') }}<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" data-translate="yearly">{{ __('index.yearly') }}</a>
                                    <a class="dropdown-item" href="#" data-translate="monthly">{{ __('index.monthly') }}</a>
                                    <a class="dropdown-item" href="#" data-translate="weekly">{{ __('index.weekly') }}</a>
                                    <a class="dropdown-item" href="#" data-translate="today">{{ __('index.today') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th data-translate="nis">{{ __('index.nis') }}</th>
                                        <th data-translate="student_name">{{ __('index.student_name') }}</th>
                                        <th data-translate="class">{{ __('index.class') }}</th>
                                        <th data-translate="date">{{ __('index.date') }}</th>
                                        <th data-translate="time">{{ __('index.time') }}</th>
                                        <th data-translate="description">{{ __('index.description') }}</th>
                                        <th data-translate="status">{{ __('index.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($lateStudents) && count($lateStudents) > 0)
                                        @foreach($lateStudents as $student)
                                    <tr>
                                        <td><a href="javascript: void(0);" class="text-body">#{{ $student->nis }}</a> </td>
                                        <td><img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}"
                                                class="avatar-xs rounded-circle me-2" alt="..."> {{ $student->name }}</td>
                                        <td>
                                            <p class="mb-0">{{ $student->class }}</p>
                                        </td>
                                        <td>
                                            {{ date('d M, Y', strtotime($student->date)) }}
                                        </td>
                                        <td>
                                            {{ date('h:i A', strtotime($student->time)) }}
                                        </td>
                                        <td>
                                            <i class="mdi mdi-clock me-1"></i> {{ $student->late_duration }} <span data-translate="minutes">{{ __('index.minutes') }}</span>
                                        </td>
                                        <td>
                                            @if($student->late_duration <= 15)
                                                <span class="badge rounded badge-soft-warning font-size-12" data-translate="late">{{ __('index.late') }}</span>
                                            @else
                                                <span class="badge rounded badge-soft-danger font-size-12" data-translate="very_late">{{ __('index.very_late') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <p class="text-muted mb-0" data-translate="no_late_students">{{ __('index.no_late_students') }}</p>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END ROW -->
    @endsection
    @section('scripts')
        <!-- apexcharts -->
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- Vector map-->
        <script src="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>

        <!-- Pass data to JavaScript -->
        <script>
            window.lateStatistics = @json($lateStatistics);
            window.donutStatistics = @json($donutStatistics);
            
            document.addEventListener('DOMContentLoaded', function() {
                const filterButtons = document.querySelectorAll('[data-period]');
                const columnChart = ApexCharts.getChartByID('column-chart');
                
                filterButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const period = this.getAttribute('data-period');
                        
                        // Update button states
                        filterButtons.forEach(btn => {
                            btn.classList.remove('btn-soft-primary');
                            btn.classList.add('btn-soft-secondary');
                        });
                        this.classList.remove('btn-soft-secondary');
                        this.classList.add('btn-soft-primary');
                        
                        // Load new data based on period
                        loadChartData(period);
                    });
                });
                
                function loadChartData(period) {
                    fetch(`/dashboard/chart-data?period=${period}`)
                        .then(response => response.json())
                        .then(data => {
                            if (columnChart) {
                                columnChart.updateOptions({
                                    xaxis: {
                                        categories: data.months
                                    },
                                    series: [{
                                        name: '{{ __('index.on_time_students') }}',
                                        data: data.onTimeCount
                                    }, {
                                        name: '{{ __('index.late_students') }}',
                                        data: data.lateCount
                                    }]
                                });
                            }
                        })
                        .catch(error => console.error('Error loading chart data:', error));
                }
            });
        </script>

        <script src="{{ URL::asset('js/dashboard.init.js') }}"></script>
        
        <!-- Load data from controller to JavaScript -->
        <script>
        console.log('Late Statistics:', {{ json_encode($lateStatistics) }});
        console.log('Donut Statistics:', {{ json_encode($donutStatistics) }});
        window.lateStatistics = {{ json_encode($lateStatistics) }};
        window.donutStatistics = {{ json_encode($donutStatistics) }};
        </script>
        
        <!-- Load translations for JavaScript -->
        <script>
        window.translations = {
            on_time_students: "{{ __('index.on_time_students') }}",
            late_students: "{{ __('index.late_students') }}",
            on_time: "{{ __('index.on_time') }}",
            late: "{{ __('index.late') }}",
            very_late: "{{ __('index.very_late') }}",
            others: "{{ __('index.others') }}",
            total_attendance: "{{ __('index.total_attendance') }}",
            minutes: "{{ __('index.minutes') }}",
            no_late_students: "{{ __('index.no_late_students') }}",
            for_today: "{{ __('index.for_today') }}"
        };
        </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
