@extends('layouts.master')
@section('title')
    {{ __('index.schedule_management') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.schedule_management') }}
@endsection
@section('body')
    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-1">{{ __('index.schedule_list') }}</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" onclick="openScheduleModal()">
                                <i class="mdi mdi-plus"></i> {{ __('index.add_schedule') }}
                            </button>
                            <button class="btn btn-success" onclick="exportExcel()">
                                <i class="mdi mdi-file-excel"></i> {{ __('index.export_excel') }}
                            </button>
                            <button class="btn btn-info" onclick="printPDF()">
                                <i class="mdi mdi-file-pdf"></i> {{ __('index.print_pdf') }}
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="class-filter" class="form-label">{{ __('index.class') }} ({{ __('index.active') }})</label>
                            <select class="form-select" id="class-filter" name="class_id">
                                <option value="">{{ __('index.select_active_class') }}</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClass && $selectedClass->id == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($classes->isEmpty())
                                <small class="text-muted">{{ __('index.no_active_class_available') }}</small>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for="academic-year-filter" class="form-label">{{ __('index.academic_year') }}</label>
                            <select class="form-select" id="academic-year-filter" name="academic_year">
                                <option value="">{{ __('index.select_academic_year') }}</option>
                                @php
                                    $academicYears = \App\Helpers\AcademicYearHelper::generateAcademicYears(1, 3);
                                @endphp
                                @foreach($academicYears as $year)
                                    <option value="{{ $year }}" {{ $selectedAcademicYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="semester-filter" class="form-label">{{ __('index.semester') }}</label>
                            <select class="form-select" id="semester-filter" name="semester">
                                <option value="">{{ __('index.select_semester') }}</option>
                                <option value="Ganjil" {{ $selectedSemester == 'Ganjil' ? 'selected' : '' }}>{{ __('index.odd_semester') }}</option>
                                <option value="Genap" {{ $selectedSemester == 'Genap' ? 'selected' : '' }}>{{ __('index.even_semester') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search-input" class="form-label">{{ __('index.search') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-input" placeholder="{{ __('index.search_teacher_subject_day') }}" value="{{ request('q') }}">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Per Page Selector -->
                    <div class="row mb-3">
                        <div class="col-md-6 offset-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <label class="mb-0">{{ __('index.show') }}:</label>
                                <select class="form-select w-auto" id="per-page-select">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">{{ __('index.no') }}</th>
                                    <th width="10%">{{ __('index.day') }}</th>
                                    <th width="15%">{{ __('index.time') }}</th>
                                    <th width="15%">{{ __('index.subject') }}</th>
                                    <th width="15%">{{ __('index.teacher') }}</th>
                                    <th width="15%">{{ __('index.class') }}</th>
                                    <th width="10%">{{ __('index.semester') }}</th>
                                    <th width="15%" class="text-center">{{ __('index.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="schedules-tbody">
                                @forelse($schedules as $index => $schedule)
                                    <tr>
                                        <td>{{ ($schedules->currentPage() - 1) * $schedules->perPage() + $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $schedule->day }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="#" class="text-dark fw-medium">
                                                {{ $schedule->subject->name ?? '-' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title bg-soft-primary rounded-circle">
                                                        {{ substr($schedule->teacher->name ?? '?', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-dark fw-medium">{{ $schedule->teacher->name ?? '-' }}</div>
                                                    <small class="text-muted">{{ $schedule->teacher->nip ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $schedule->classRoom->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $schedule->semester == 'Ganjil' ? 'warning' : 'success' }}">
                                                {{ $schedule->semester ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-soft-primary" onclick="editSchedule('{{ $schedule->id }}')">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger" onclick="deleteSchedule('{{ $schedule->id }}', '{{ $schedule->subject->name ?? '' }}')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-calendar-blank font-size-48 d-block mb-2"></i>
                                                @if($classes->isEmpty())
                                                    <p>{{ __('index.no_active_class_available') }}</p>
                                                    <p class="small">{{ __('index.create_active_class_first') }}</p>
                                                @else
                                                    <p>{{ __('index.no_schedule_data_available') }}</p>
                                                    <p class="small">{{ __('index.select_active_class_to_view') }}</p>
                                                    <button type="button" class="btn btn-primary" onclick="openScheduleModal()">
                                                        <i class="mdi mdi-plus me-1"></i> {{ __('index.add_schedule') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($schedules, 'hasPages') && $schedules->hasPages())
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <nav aria-label="Page navigation">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted">
                                            {{ __('index.showing') }} {{ $schedules->firstItem() ?? 0 }} {{ __('index.to') }} {{ $schedules->lastItem() ?? 0 }} {{ __('index.of') }} {{ $schedules->total() }} {{ __('index.data') }}
                                        </div>
                                        <ul class="pagination mb-0">
                                            <!-- Previous -->
                                            <li class="page-item {{ $schedules->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $schedules->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $schedules->onFirstPage() }}">
                                                    <i class="mdi mdi-chevron-left"></i> {{ __('index.previous') }}
                                                </a>
                                            </li>

                                            <!-- Page Numbers -->
                                            @php
                                                $startPage = max(1, $schedules->currentPage() - 2);
                                                $endPage = min($schedules->lastPage(), $schedules->currentPage() + 2);
                                            @endphp

                                            @if($startPage > 1)
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $schedules->url(1) }}">1</a>
                                                </li>
                                                @if($startPage > 2)
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                @endif
                                            @endif

                                            @for($page = $startPage; $page <= $endPage; $page++)
                                                <li class="page-item {{ $schedules->currentPage() == $page ? 'active' : '' }}">
                                                    <a class="page-link" href="{{ $schedules->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endfor

                                            @if($endPage < $schedules->lastPage())
                                                @if($endPage < $schedules->lastPage() - 1)
                                                    <li class="page-item disabled">
                                                        <span class="page-link">...</span>
                                                    </li>
                                                @endif
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $schedules->url($schedules->lastPage()) }}">{{ $schedules->lastPage() }}</a>
                                                </li>
                                            @endif

                                            <!-- Next -->
                                            <li class="page-item {{ $schedules->hasMorePages() ? '' : 'disabled' }}">
                                                <a class="page-link" href="{{ $schedules->nextPageUrl() }}" aria-disabled="{{ !$schedules->hasMorePages() }}">
                                                    {{ __('index.next') }} <i class="mdi mdi-chevron-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div class="modal fade" id="schedule-modal" tabindex="-1" aria-labelledby="schedule-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="schedule-modal-label">Modal title</h5>
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
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const perPageSelect = document.getElementById('per-page-select');
            const classFilter = document.getElementById('class-filter');
            const academicYearFilter = document.getElementById('academic-year-filter');
            const semesterFilter = document.getElementById('semester-filter');

            // Search functionality
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 300);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    applyFilters();
                }
            });
        }

        if (searchButton) {
            searchButton.addEventListener('click', function() {
                clearTimeout(searchTimeout);
                applyFilters();
            });
        }

            // Filter change handlers
            if (classFilter) {
                classFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }

            if (academicYearFilter) {
                academicYearFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }

            if (semesterFilter) {
                semesterFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }

            // Per page change
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    applyFilters();
                });
            }

            function applyFilters() {
                const url = new URL(window.location.href);
                
                // Update search
                if (searchInput) {
                    if (searchInput.value.trim()) {
                        url.searchParams.set('q', searchInput.value.trim());
                        // Simpan value ke sessionStorage untuk restore fokus
                        sessionStorage.setItem('scheduleSearchValue', searchInput.value.trim());
                        sessionStorage.setItem('scheduleSearchFocus', 'true');
                    } else {
                        url.searchParams.delete('q');
                        sessionStorage.removeItem('scheduleSearchValue');
                        sessionStorage.removeItem('scheduleSearchFocus');
                    }
                }

                // Update filters
                if (classFilter && classFilter.value) {
                    url.searchParams.set('class_id', classFilter.value);
                } else {
                    url.searchParams.delete('class_id');
                }

                if (academicYearFilter && academicYearFilter.value) {
                    url.searchParams.set('academic_year', academicYearFilter.value);
                } else {
                    url.searchParams.delete('academic_year');
                }

                if (semesterFilter && semesterFilter.value) {
                    url.searchParams.set('semester', semesterFilter.value);
                } else {
                    url.searchParams.delete('semester');
                }

                // Update per page
                if (perPageSelect) {
                    url.searchParams.set('per_page', perPageSelect.value);
                }

                // Reset page
                url.searchParams.delete('page');

                window.location.href = url.toString();
            }

            // Restore search focus dan value setelah page load
            function restoreSearchFocus() {
                const searchValue = sessionStorage.getItem('scheduleSearchValue');
                const shouldFocus = sessionStorage.getItem('scheduleSearchFocus');
                
                if (searchInput && searchValue && shouldFocus === 'true') {
                    searchInput.value = searchValue;
                    // Fokus ke input dan pindahkan cursor ke akhir
                    searchInput.focus();
                    searchInput.setSelectionRange(searchValue.length, searchValue.length);
                    
                    // Clear sessionStorage
                    sessionStorage.removeItem('scheduleSearchFocus');
                }
            }

            // Panggil restore focus setelah DOM loaded
            setTimeout(restoreSearchFocus, 100);
        });

        // Modal functions
        function openScheduleModal() {
            fetch('/schedules/create', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('schedule-modal-label').textContent = data.title;
                    document.querySelector('#schedule-modal .modal-body').innerHTML = data.html;
                    
                    const modal = new bootstrap.Modal(document.getElementById('schedule-modal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("index.error") }}!',
                    text: '{{ __("index.failed_to_load_form") }}'
                });
            });
        }

        function editSchedule(id) {
            fetch(`/schedules/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('schedule-modal-label').textContent = data.title;
                    document.querySelector('#schedule-modal .modal-body').innerHTML = data.html;
                    
                    const modal = new bootstrap.Modal(document.getElementById('schedule-modal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("index.error") }}!',
                    text: '{{ __("index.failed_to_load_form") }}'
                });
            });
        }

        // Delete schedule
        function deleteSchedule(id, name) {
            Swal.fire({
                title: '{{ __("index.are_you_sure") }}',
                text: "{{ __("index.schedule_will_be_deleted_part1") }}" + name + "{{ __("index.schedule_will_be_deleted_part2") }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("index.yes_delete") }}',
                cancelButtonText: '{{ __("index.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form for delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/schedules/${id}`;
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.getAttribute('content');
                        form.appendChild(csrfInput);
                    }
                    
                    // Add method override for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Export Excel
        function exportExcel() {
            const url = new URL(window.location.href);
            url.searchParams.set('export', 'excel');
            window.open(url.toString(), '_blank');
        }

        // Print PDF
        function printPDF() {
            const url = new URL(window.location.href);
            url.searchParams.set('print', 'pdf');
            window.open(url.toString(), '_blank');
        }
    </script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
