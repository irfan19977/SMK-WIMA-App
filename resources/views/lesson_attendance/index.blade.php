@extends('layouts.master')
@section('title')
    {{ __('index.lesson_attendance_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.lesson_attendance_list') }}
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title">{{ __('index.lesson_attendance_list') }}</h4>
                            <p class="card-title-desc">{{ __('index.manage_lesson_attendance_data') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#lesson-attendance-modal" onclick="openLessonAttendanceModal()">
                                <i class="mdi mdi-plus me-1"></i> {{ __('index.add_lesson_attendance') }}
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportExcel()">
                                <i class="mdi mdi-file-excel me-1"></i> {{ __('index.export_excel') }}
                            </button>
                            <button type="button" class="btn btn-info" onclick="printPDF()">
                                <i class="mdi mdi-file-pdf me-1"></i> {{ __('index.print_pdf') }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{{ __('index.search_student_name_nisn_email') }}" id="search-input" value="{{ request('q') }}">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <label class="mb-0">{{ __('index.show') }}:</label>
                                <select class="form-select w-auto" id="per-page-select">
                                    <option value="10" {{ $lessonAttendances->perPage() == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $lessonAttendances->perPage() == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $lessonAttendances->perPage() == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $lessonAttendances->perPage() == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div id="loading-spinner" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <table class="table table-striped mb-0" id="lesson-attendance-table">
                            <thead>
                                <tr>
                                    <th>{{ __('index.no') }}</th>
                                    <th>{{ __('index.nisn') }}</th>
                                    <th>{{ __('index.student_name') }}</th>
                                    <th>{{ __('index.class') }}</th>
                                    <th>{{ __('index.subject') }}</th>
                                    <th>{{ __('index.date') }}</th>
                                    <th>{{ __('index.status') }}</th>
                                    <th>{{ __('index.description') }}</th>
                                    <th>{{ __('index.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="lesson-attendance-tbody">
                                @forelse ($lessonAttendances as $item)
                                <tr>
                                    <th scope="row">{{ ($lessonAttendances->currentPage() - 1) * $lessonAttendances->perPage() + $loop->iteration }}</th>
                                    <td>{{ $item->student_nisn ?? '-' }}</td>
                                    <td>
                                        @if($item->user_id)
                                            <a href="{{ route('profile.show') }}?user_id={{ $item->user_id }}" class="text-primary text-decoration-none fw-medium">
                                                <strong>{{ $item->student_name ?? '-' }}</strong>
                                            </a>
                                        @else
                                            <strong>{{ $item->student_name ?? '-' }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->class_id)
                                            <a href="{{ route('classes.show', $item->class_id) }}" class="text-decoration-none">
                                                <span class="badge rounded-pill bg-primary font-size-12">{{ $item->class_name ?? '-' }}</span>
                                            </a>
                                        @else
                                            <span class="badge rounded-pill bg-primary font-size-12">{{ $item->class_name ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-info font-size-12">{{ $item->subject_name ?? '-' }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td>
                                        @php
                                        $statusClass = 'bg-secondary';
                                        $statusText = $item->status ?? '-';
                                        
                                        switch($item->status) {
                                            case 'hadir':
                                                $statusClass = 'bg-success';
                                                $statusText = __('index.present');
                                                break;
                                            case 'terlambat':
                                                $statusClass = 'bg-danger';
                                                $statusText = __('index.late');
                                                break;
                                            case 'izin':
                                                $statusClass = 'bg-light text-dark';
                                                $statusText = __('index.permission');
                                                break;
                                            case 'sakit':
                                                $statusClass = 'bg-light text-dark';
                                                $statusText = __('index.sick');
                                                break;
                                            case 'alpha':
                                                $statusClass = 'bg-danger';
                                                $statusText = __('index.absent');
                                                break;
                                        }
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }} font-size-12">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ __('index.check_in') }}: {{ $item->check_in ? \Carbon\Carbon::parse($item->check_in)->format('H:i') : '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-soft-primary" onclick="editLessonAttendance('{{ $item->id }}')">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->student_name ?? '' }}')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('index.no_data_available') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($lessonAttendances->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted" id="pagination-info">
                                Menampilkan {{ $lessonAttendances->firstItem() }} {{ __('index.to') }} {{ $lessonAttendances->lastItem() }} {{ __('index.of') }} {{ $lessonAttendances->total() }} {{ __('index.data') }}
                            </div>
                            <div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" id="pagination-links">
                                        {{-- Previous Link --}}
                                        @if($lessonAttendances->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="1" tabindex="-1">{{ __('index.previous') }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $lessonAttendances->currentPage() - 1 }}">{{ __('index.previous') }}</a>
                                            </li>
                                        @endif

                                        {{-- Page Numbers --}}
                                        @for($i = 1; $i <= $lessonAttendances->lastPage(); $i++)
                                            @if($i == $lessonAttendances->currentPage())
                                                <li class="page-item active">
                                                    <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }} <span class="sr-only">(current)</span></a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor

                                        {{-- Next Link --}}
                                        @if($lessonAttendances->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $lessonAttendances->currentPage() + 1 }}">{{ __('index.next') }}</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="{{ $lessonAttendances->lastPage() }}" tabindex="-1">{{ __('index.next') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- Lesson Attendance Modal -->
    <div class="modal fade" id="lesson-attendance-modal" tabindex="-1" aria-labelledby="lesson-attendance-modal-label" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lesson-attendance-modal-label">{{ __('index.add_lesson_attendance') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="lesson-attendance-modal-body">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        let currentPage = 1;
        let currentSearch = '{{ request('q', '') }}';
        let currentPerPage = {{ $lessonAttendances->perPage() }};

        // Debug: Check if script is loading
        console.log('Lesson attendance script loaded');
        
        // Open lesson attendance modal
        function openLessonAttendanceModal() {
            console.log('openLessonAttendanceModal called');
            fetch('/lesson-attendances/create', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('lesson-attendance-modal-body').innerHTML = data.html;
                    document.getElementById('lesson-attendance-modal-label').textContent = data.title;
                    
                    const modal = new bootstrap.Modal(document.getElementById('lesson-attendance-modal'));
                    modal.show();
                    
                    // Initialize form after modal is shown
                    setTimeout(() => {
                        if (typeof initializeLessonAttendanceForm === 'function') {
                            initializeLessonAttendanceForm();
                        }
                        // Initialize NISN search if data is available
                        if (data.students && data.classes) {
                            initializeNISNSearch(data.students, data.classes, false);
                        }
                    }, 300);
                    
                    // Add event listener for modal hidden to clean up backdrop
                    const modalElement = document.getElementById('lesson-attendance-modal');
                    modalElement.addEventListener('hidden.bs.modal', function () {
                        // Remove any remaining backdrops
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("index.error") }}!',
                        text: data.message || '{{ __("index.failed_to_load_form") }}'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("index.error") }}!',
                    text: '{{ __("index.failed_to_load_form") }}: ' + error.message
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const perPageSelect = document.getElementById('per-page-select');
            const loadingSpinner = document.getElementById('loading-spinner');
            const lessonAttendanceTable = document.getElementById('lesson-attendance-table');
            const lessonAttendanceTbody = document.getElementById('lesson-attendance-tbody');
            const paginationLinks = document.getElementById('pagination-links');
            const paginationInfo = document.getElementById('pagination-info');

            // Set initial search value
            if (searchInput && currentSearch) {
                searchInput.value = currentSearch;
            }

            // Auto-focus search input
            if (searchInput) {
                searchInput.focus();
                
                // Move cursor to end if there's existing value
                if (searchInput.value) {
                    searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                }
            }

            // Per page change
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    currentPerPage = this.value;
                    currentPage = 1;
                    performAjaxSearch();
                });
            }

            // Pagination click handler
            if (paginationLinks) {
                paginationLinks.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageLink = e.target.closest('a[data-page]');
                    if (pageLink && !pageLink.parentElement.classList.contains('disabled')) {
                        currentPage = parseInt(pageLink.dataset.page);
                        performAjaxSearch();
                    }
                });
            }

            // Search functionality
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value.trim();
                    currentPage = 1;
                    performAjaxSearch();
                }, 500);
            });

            searchButton.addEventListener('click', function() {
                clearTimeout(searchTimeout);
                currentSearch = searchInput.value.trim();
                currentPage = 1;
                performAjaxSearch();
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    currentSearch = this.value.trim();
                    currentPage = 1;
                    performAjaxSearch();
                }
            });
        });

        // Global function for AJAX search
        function performAjaxSearch() {
            const loadingSpinner = document.getElementById('loading-spinner');
            const lessonAttendanceTable = document.getElementById('lesson-attendance-table');
            const lessonAttendanceTbody = document.getElementById('lesson-attendance-tbody');
            const paginationLinks = document.getElementById('pagination-links');
            const paginationInfo = document.getElementById('pagination-info');
            
            showLoading();
            
            const params = new URLSearchParams();
            if (currentSearch) params.append('q', currentSearch);
            params.append('page', currentPage);
            params.append('per_page', currentPerPage);

            fetch(`/lesson-attendances?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    updateTable(data.data);
                    updatePagination(data.pagination);
                } else {
                    console.error('Search failed:', data);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
            });

            function showLoading() {
                if (loadingSpinner) loadingSpinner.style.display = 'block';
                if (lessonAttendanceTable) lessonAttendanceTable.style.opacity = '0.5';
            }

            function hideLoading() {
                if (loadingSpinner) loadingSpinner.style.display = 'none';
                if (lessonAttendanceTable) lessonAttendanceTable.style.opacity = '1';
            }

            function updateTable(attendanceData) {
                if (!lessonAttendanceTbody) return;
                
                if (attendanceData.length === 0) {
                    lessonAttendanceTbody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data absensi mata pelajaran</td></tr>';
                    return;
                }

                let html = '';
                attendanceData.forEach((item, index) => {
                    const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                    html += `
                        <tr>
                            <th scope="row">${rowNumber}</th>
                            <td>${item.student_nisn || '-'}</td>
                            <td>
                                ${item.user_id ? 
                                    `<a href="/profile?user_id=${item.user_id}" class="text-primary text-decoration-none fw-medium">
                                        <strong>${item.student_name || '-'}</strong>
                                    </a>` : 
                                    `<strong>${item.student_name || '-'}</strong>`}
                            </td>
                            <td>
                                ${item.class_id ? 
                                    `<a href="/classes/${item.class_id}" class="text-decoration-none">
                                        <span class="badge rounded-pill bg-primary font-size-12">${item.class_name || '-'}</span>
                                    </a>` : 
                                    `<span class="badge rounded-pill bg-primary font-size-12">${item.class_name || '-'}</span>`}
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-info font-size-12">${item.subject_name || '-'}</span>
                            </td>
                            <td>${item.date}</td>
                            <td>
                                <span class="badge rounded-pill ${item.status_class} font-size-12">
                                    ${item.status_text}
                                </span>
                            </td>
                            <td>
                                Check-in: ${item.check_in}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-soft-primary" onclick="editLessonAttendance('${item.id}')">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('${item.id}', '${item.student_name.replace(/'/g, "\\'")}')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                lessonAttendanceTbody.innerHTML = html;
            }

            function updatePagination(pagination) {
                if (!paginationInfo || !paginationLinks) return;
                
                // Update pagination info
                const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
                const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                paginationInfo.textContent = `Menampilkan ${startItem} sampai ${endItem} dari ${pagination.total} data`;

                // Update pagination links
                let html = '';
                
                // Previous link
                if (pagination.current_page === 1) {
                    html += '<li class="page-item disabled"><a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a></li>';
                } else {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a></li>`;
                }

                // Page numbers
                for (let i = 1; i <= pagination.last_page; i++) {
                    if (i === pagination.current_page) {
                        html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i} <span class="sr-only">(current)</span></a></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                }

                // Next link
                if (pagination.current_page === pagination.last_page) {
                    html += `<li class="page-item disabled"><a class="page-link" href="#" data-page="${pagination.last_page}" tabindex="-1">Next</a></li>`;
                } else {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a></li>`;
                }

                paginationLinks.innerHTML = html;
            }
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

        // Edit lesson attendance
        function editLessonAttendance(id) {
            fetch(`/lesson-attendances/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('lesson-attendance-modal-body').innerHTML = data.html;
                    document.getElementById('lesson-attendance-modal-label').textContent = data.title;
                    
                    const modal = new bootstrap.Modal(document.getElementById('lesson-attendance-modal'));
                    modal.show();
                    
                    // Initialize form after modal is shown
                    setTimeout(() => {
                        if (typeof initializeLessonAttendanceForm === 'function') {
                            initializeLessonAttendanceForm();
                        }
                        // Initialize NISN search if data is available
                        if (data.students && data.classes) {
                            initializeNISNSearch(data.students, data.classes, true);
                        }
                    }, 300);
                    
                    // Add event listener for modal hidden to clean up backdrop
                    const modalElement = document.getElementById('lesson-attendance-modal');
                    modalElement.addEventListener('hidden.bs.modal', function () {
                        // Remove any remaining backdrops
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("index.error") }}!',
                        text: data.message || '{{ __("index.failed_to_load_form") }}'
                    });
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

        // Delete confirmation
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Absensi mata pelajaran \"" + name + "\" akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX delete request
                    fetch(`/lesson-attendances/${id}`, {
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
                                text: data.message || 'Data berhasil dihapus.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            
                            // Refresh the table
                            performAjaxSearch();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    });
                }
            });
        }

        // Print PDF
        function printPDF() {
            const url = new URL(window.location.href);
            url.searchParams.set('print', 'pdf');
            window.open(url.toString(), '_blank');
        }
        
        // Handle form submission for lesson attendance
        function initializeLessonAttendanceForm() {
            const form = document.getElementById('lesson-attendance-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form values
                    const nisn = document.getElementById('nisn_search').value.trim();
                    const studentName = document.getElementById('student_name').value.trim();
                    const classId = document.getElementById('class_id').value;
                    const subjectId = document.getElementById('subject_id').value;
                    const date = document.getElementById('date').value;
                    const checkIn = document.getElementById('check_in').value;
                    const checkInStatus = document.getElementById('check_in_status').value;
                    
                    // Validation
                    if (!nisn) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Harap isi NISN terlebih dahulu.'
                        });
                        return;
                    }
                    
                    if (!studentName) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'NISN tidak ditemukan dalam sistem.'
                        });
                        return;
                    }
                    
                    if (!classId || !subjectId || !date) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Harap lengkapi semua field yang wajib diisi.'
                        });
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    // Show loading
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> {{ __("index.saving") }}...';
                    
                    // Create FormData with the new structure
                    const formData = new FormData();
                    formData.append('class_id', classId);
                    formData.append('subject_id', subjectId);
                    formData.append('date', date);
                    formData.append('check_in', checkIn);
                    formData.append('check_in_status', checkInStatus);
                    formData.append('nisn', nisn);
                    formData.append('student_name', studentName);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw data;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close modal
                            const modalElement = document.getElementById('lesson-attendance-modal');
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                            
                            // Remove backdrop manually if it persists
                            setTimeout(() => {
                                const backdrops = document.querySelectorAll('.modal-backdrop');
                                backdrops.forEach(backdrop => backdrop.remove());
                                document.body.classList.remove('modal-open');
                            }, 200);
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || '{{ __("index.data_saved_successfully") }}.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            
                            // Refresh the table
                            performAjaxSearch();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Terjadi kesalahan saat menyimpan data.'
                        });
                    })
                    .finally(() => {
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }
        }
        
        // Initialize NISN search functionality (copied from attendance modal)
        function initializeNISNSearch(students, classes, isEditMode = false) {
            const nisnInput = document.getElementById('nisn_search');
            const studentNameInput = document.getElementById('student_name');
            const classSelect = document.getElementById('class_id');
            const subjectSelect = document.getElementById('subject_id');
            
            if (!nisnInput) return; // Only proceed if NISN search exists in the form
            
            // Create NISN to student data mapping
            const nisnStudentMap = {};
            students.forEach(student => {
                if (student.nisn) {
                    nisnStudentMap[student.nisn] = {
                        id: student.id,
                        name: student.name,
                        classId: student.classes && student.classes.length > 0 ? student.classes[0].id : '',
                        className: student.classes && student.classes.length > 0 ? student.classes[0].name : ''
                    };
                }
            });
            
            function searchByNISN() {
                const nisn = nisnInput.value.trim();
                
                if (nisn && nisnStudentMap[nisn]) {
                    const student = nisnStudentMap[nisn];
                    
                    // Fill student name field
                    if (studentNameInput) {
                        studentNameInput.value = student.name;
                    }
                    
                    // Auto-fill class dropdown
                    if (classSelect) {
                        classSelect.value = student.classId;
                        classSelect.disabled = true;
                        classSelect.setAttribute('data-disabled', 'true'); // Mark as disabled by NISN search
                        
                        // Trigger change event to load subjects for this class
                        const event = new Event('change', { bubbles: true });
                        classSelect.dispatchEvent(event);
                    }
                    
                    // Get current subject for this class based on schedule
                    fetch('/lesson-attendances/get-current-subject-by-class', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            class_id: student.classId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            // Load subjects into dropdown first
                            fetch('/lesson-attendances/get-subjects-by-class', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    class_id: student.classId
                                })
                            })
                            .then(response => response.json())
                            .then(subjectsData => {
                                if (subjectsData.success && subjectsData.data.length > 0) {
                                    if (subjectSelect) {
                                        subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                                        subjectsData.data.forEach(subject => {
                                            const option = document.createElement('option');
                                            option.value = subject.id;
                                            option.textContent = subject.name;
                                            subjectSelect.appendChild(option);
                                        });
                                        subjectSelect.disabled = false;
                                    }
                                    
                                    // Auto-select current subject
                                    setTimeout(() => {
                                        if (subjectSelect && data.data.id) {
                                            subjectSelect.value = data.data.id;
                                        }
                                    }, 100);
                                    
                                    // Show detailed success notification
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Siswa Ditemukan!',
                                        html: `
                                            <div style="text-align: left;">
                                                <strong>Nama:</strong> ${student.name}<br>
                                                <strong>NISN:</strong> ${nisn}<br>
                                                <strong>Kelas:</strong> ${student.className}<br>
                                                <strong>Jadwal Saat Ini:</strong> ${data.data.name}<br>
                                                <small style="color: #6c757d;">
                                                    ${data.data.current_day}, ${data.data.current_time} (Jadwal: ${data.data.schedule_start} - ${data.data.schedule_end})
                                                </small>
                                            </div>
                                        `,
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#3085d6'
                                    });
                                } else {
                                    // Show student info but no subjects
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Siswa Ditemukan',
                                        html: `
                                            <div style="text-align: left;">
                                                <strong>Nama:</strong> ${student.name}<br>
                                                <strong>NISN:</strong> ${nisn}<br>
                                                <strong>Kelas:</strong> ${student.className}<br><br>
                                                <div class="alert alert-warning" style="padding: 10px; margin: 10px 0; border-radius: 5px;">
                                                    <i class="mdi mdi-alert"></i> <strong>Perhatian:</strong> Tidak ada mata pelajaran aktif untuk kelas ini.
                                                </div>
                                            </div>
                                        `,
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error loading subjects:', error);
                                // Show basic student info even if subjects fail to load
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Siswa Ditemukan!',
                                    html: `
                                        <div style="text-align: left;">
                                            <strong>Nama:</strong> ${student.name}<br>
                                            <strong>NISN:</strong> ${nisn}<br>
                                            <strong>Kelas:</strong> ${student.className}
                                        </div>
                                    `,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3085d6'
                                });
                            });
                        } else {
                            // Show student info but no current schedule
                            Swal.fire({
                                icon: 'warning',
                                title: 'Siswa Ditemukan',
                                html: `
                                    <div style="text-align: left;">
                                        <strong>Nama:</strong> ${student.name}<br>
                                        <strong>NISN:</strong> ${nisn}<br>
                                        <strong>Kelas:</strong> ${student.className}<br><br>
                                        <div class="alert alert-info" style="padding: 10px; margin: 10px 0; border-radius: 5px;">
                                            <i class="mdi mdi-information"></i> <strong>Informasi:</strong> ${data.message}<br>
                                            <small>${data.data.current_day}, ${data.data.current_time}</small>
                                        </div>
                                    </div>
                                `,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error getting current subject:', error);
                        // Show basic student info even if current subject fails to load
                        Swal.fire({
                            icon: 'success',
                            title: 'Siswa Ditemukan!',
                            html: `
                                <div style="text-align: left;">
                                    <strong>Nama:</strong> ${student.name}<br>
                                    <strong>NISN:</strong> ${nisn}<br>
                                    <strong>Kelas:</strong> ${student.className}
                                </div>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                    
                } else if (nisn) {
                    // Clear student name if NISN not found
                    if (studentNameInput) {
                        studentNameInput.value = '';
                    }
                    
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __("index.nisn_not_found") }}',
                        text: '{{ __("index.nisn") }} ' + nisn + ' {{ __("index.not_registered_in_system") }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    // Clear student name if NISN field is empty
                    if (studentNameInput) {
                        studentNameInput.value = '';
                    }
                }
            }
            
            // Add search functionality
            nisnInput.addEventListener('input', function() {
                clearTimeout(nisnInput.searchTimeout);
                nisnInput.searchTimeout = setTimeout(searchByNISN, 500);
            });
            
            nisnInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(nisnInput.searchTimeout);
                    searchByNISN();
                }
            });
        }
        
        // Call this function after modal content is loaded - removed as it's now called in open/edit functions
    </script>
    
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
