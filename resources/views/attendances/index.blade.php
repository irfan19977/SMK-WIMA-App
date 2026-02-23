@extends('layouts.master')
@section('title')
    {{ __('index.attendance_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.attendance_list') }}
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
                                <h4 class="card-title mb-1">{{ __('index.attendance_list') }}</h4>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="openAttendanceModal()">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_attendance') }}
                                </button>
                                <button class="btn btn-success" onclick="exportExcel()">
                                    <i class="mdi mdi-file-excel"></i> {{ __('index.export_excel') }}
                                </button>
                                <button class="btn btn-info" onclick="printPDF()">
                                    <i class="mdi mdi-file-pdf"></i> {{ __('index.print_pdf') }}
                                </button>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_student_nisn_class_date') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">{{ __('index.show') }}:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $attendances->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $attendances->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $attendances->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $attendances->perPage() == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('index.no') }}</th>
                                        <th>{{ __('index.nisn') }}</th>
                                        <th>{{ __('index.student_name') }}</th>
                                        <th>{{ __('index.class') }}</th>
                                        <th>{{ __('index.date') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.description') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendances as $item)
                                    <tr>
                                        <th scope="row">{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</th>
                                        <td>{{ $item->student_id ?? '-' }}</td>
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
                                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                        <td>
                                            @php
                                            $statusClass = 'bg-secondary';
                                            $statusText = $item->check_in_status ?? '-';
                                            
                                            switch($item->check_in_status) {
                                                case 'tepat':
                                                    $statusClass = 'bg-success';
                                                    $statusText = __('index.on_time');
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
                                            @if($item->check_out)
                                                <br>{{ __('index.check_out') }}: {{ \Carbon\Carbon::parse($item->check_out)->format('H:i') }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-soft-primary" onclick="editAttendance('{{ $item->attendance_id }}')">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('{{ $item->attendance_id }}', '{{ $item->student_name ?? '' }}')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('index.no_data_available') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($attendances->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    {{ __('index.showing') }} {{ $attendances->firstItem() }} {{ __('index.to') }} {{ $attendances->lastItem() }} {{ __('index.of') }} {{ $attendances->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            {{-- Previous Link --}}
                                            @if($attendances->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1">{{ __('index.previous') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $attendances->previousPageUrl() }}">{{ __('index.previous') }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $attendances->lastPage(); $i++)
                                                @if($i == $attendances->currentPage())
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">{{ $i }} <span class="sr-only">(current)</span></a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $attendances->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            {{-- Next Link --}}
                                            @if($attendances->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $attendances->nextPageUrl() }}">{{ __('index.next') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1">Next</a>
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

        <!-- Attendance Modal -->
        <div class="modal fade" id="attendance-modal" tabindex="-1" aria-labelledby="attendance-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendance-modal-label">Tambah Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
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
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const perPageSelect = document.getElementById('per-page-select');

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
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    });
                }

                // Search functionality
                let searchTimeout;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch();
                    }, 500);
                });

                searchButton.addEventListener('click', function() {
                    clearTimeout(searchTimeout);
                    performSearch();
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimeout);
                        performSearch();
                    }
                });

                function performSearch() {
                    const url = new URL(window.location.href);
                    const q = (searchInput.value || '').trim();
                    
                    if (q) url.searchParams.set('q', q);
                    else url.searchParams.delete('q');
                    
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }
            });

            // Open modal for create
            function openAttendanceModal() {
                fetch('/attendances/create', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('attendance-modal-label').textContent = data.title;
                        document.querySelector('#attendance-modal .modal-body').innerHTML = data.html;
                        
                        const modal = new bootstrap.Modal(document.getElementById('attendance-modal'));
                        modal.show();
                        
                        // Initialize NISN search after modal is shown
                        initializeNISNSearch(data.students, data.classes, false);
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

            // Edit attendance
            function editAttendance(id) {
                console.log('Edit attendance called with ID:', id);
                
                fetch(`/attendances/${id}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        console.log('Updating modal content...');
                        document.getElementById('attendance-modal-label').textContent = data.title;
                        document.querySelector('#attendance-modal .modal-body').innerHTML = data.html;
                        
                        console.log('Showing modal...');
                        const modal = new bootstrap.Modal(document.getElementById('attendance-modal'));
                        modal.show();
                        
                        // Initialize NISN search after modal is shown (edit mode)
                        console.log('Initializing NISN search...');
                        initializeNISNSearch(data.students, data.classes, true);
                    } else {
                        console.log('Server returned error:', data.message);
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
                        text: '{{ __("index.failed_to_load_form") }}: ' + error.message
                    });
                });
            }

            // Initialize NISN search functionality
            function initializeNISNSearch(students, classes, isEditMode = false) {
                const nisnInput = document.getElementById('nisn_search');
                const studentSelect = document.getElementById('student_id');
                const classSelect = document.getElementById('class_id');
                
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
                        
                        // Auto-fill student dropdown
                        studentSelect.value = student.id;
                        studentSelect.disabled = true;
                        studentSelect.setAttribute('data-disabled', 'true'); // Mark as disabled by NISN search
                        
                        // Auto-fill class dropdown
                        classSelect.value = student.classId;
                        classSelect.disabled = true;
                        classSelect.setAttribute('data-disabled', 'true'); // Mark as disabled by NISN search
                        
                        // Cek existing attendance untuk hari ini
                        const dateInput = document.getElementById('date');
                        const selectedDate = dateInput ? dateInput.value : new Date().toISOString().split('T')[0];
                        
                        fetch('/attendances/find-existing', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                nisn: nisn,
                                date: selectedDate
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.found) {
                                // Fill existing data
                                const attendance = data.attendance;
                                
                                // Fill and disable check-in data (read-only)
                                const checkInInput = document.getElementById('check_in');
                                const checkInStatusSelect = document.getElementById('check_in_status');
                                if (checkInInput) {
                                    if (attendance.check_in) {
                                        checkInInput.value = attendance.check_in;
                                        checkInInput.disabled = true;
                                        checkInInput.setAttribute('data-disabled', 'true');
                                    }
                                }
                                if (checkInStatusSelect) {
                                    if (attendance.check_in_status) {
                                        checkInStatusSelect.value = attendance.check_in_status;
                                        checkInStatusSelect.disabled = true;
                                        checkInStatusSelect.setAttribute('data-disabled', 'true');
                                    }
                                }
                                
                                // Update form text for check-in section
                                const checkInSection = document.getElementById('check-in-section');
                                if (checkInSection) {
                                    const formTexts = checkInSection.querySelectorAll('.form-text');
                                    formTexts.forEach(text => {
                                        if (attendance.check_in) {
                                            text.textContent = '{{ __("index.check_in_already_filled_cannot_be_changed") }}';
                                            text.classList.add('text-warning');
                                        }
                                    });
                                }
                                
                                // Fill check-out data
                                const checkOutInput = document.getElementById('check_out');
                                const checkOutStatusSelect = document.getElementById('check_out_status');
                                if (checkOutInput && attendance.check_out) {
                                    checkOutInput.value = attendance.check_out;
                                }
                                if (checkOutStatusSelect && attendance.check_out_status) {
                                    checkOutStatusSelect.value = attendance.check_out_status;
                                }
                                
                                // Show notification that existing data was found
                                Swal.fire({
                                    icon: 'info',
                                    title: '{{ __("index.attendance_data_found") }}',
                                    html: `
                                        <div style="text-align: left;">
                                            <strong>{{ __("index.student") }}:</strong> ${attendance.student_name}<br>
                                            <strong>{{ __("index.class") }}:</strong> ${attendance.class_name}<br>
                                            <strong>{{ __("index.date") }}:</strong> ${attendance.date}<br>
                                            <strong>Check-in:</strong> ${attendance.check_in || '{{ __("index.not_yet") }}'} (${attendance.check_in_status || '-'})<br>
                                            <strong>Check-out:</strong> ${attendance.check_out || '{{ __("index.not_yet") }}'} (${attendance.check_out_status || '-'})<br><br>
                                            <div class="alert alert-warning" style="padding: 10px; margin: 10px 0; border-radius: 5px;">
                                                <i class="mdi mdi-alert"></i> <strong>{{ __("index.attention") }}:</strong> {{ __("index.check_in_already_filled_cannot_be_changed_detail") }}
                                            </div>
                                        </div>
                                    `,
                                    confirmButtonText: '{{ __("index.continue_edit_check_out") }}',
                                    confirmButtonColor: '#3085d6'
                                });
                                
                                // Change form to update mode
                                const form = document.getElementById('attendance-form');
                                if (form) {
                                    form.action = `/attendances/${attendance.id}`;
                                    const methodInput = form.querySelector('input[name="_method"]');
                                    if (methodInput) {
                                        methodInput.value = 'PUT';
                                    } else {
                                        const methodField = document.createElement('input');
                                        methodField.type = 'hidden';
                                        methodField.name = '_method';
                                        methodField.value = 'PUT';
                                        form.appendChild(methodField);
                                    }
                                    
                                    // Update button text
                                    const submitBtn = form.querySelector('button[type="submit"]');
                                    if (submitBtn) {
                                        submitBtn.innerHTML = '<i class="mdi mdi-content-save"></i> Update Absensi';
                                    }
                                    
                                    // Update modal title
                                    const modalLabel = document.getElementById('attendance-modal-label');
                                    if (modalLabel) {
                                        modalLabel.textContent = '{{ __("index.edit_attendance") }}';
                                    }
                                }
                            } else {
                                // No existing data, show normal success notification
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("index.student_found") }}',
                                    text: student.name + ' - ' + student.className,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error checking existing attendance:', error);
                            // Show normal success notification even if check fails
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("index.student_found") }}',
                                text: student.name + ' - ' + student.className,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        });
                        
                    } else if (nisn) {
                        Swal.fire({
                            icon: 'warning',
                            title: '{{ __("index.nisn_not_found") }}',
                            text: '{{ __("index.nisn") }} ' + nisn + ' {{ __("index.not_registered_in_system") }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
                
                // Handle form submission with AJAX
                const form = document.getElementById('attendance-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        // Enable disabled fields temporarily for submission
                        const disabledFields = form.querySelectorAll('[data-disabled="true"]');
                        disabledFields.forEach(field => {
                            field.disabled = false;
                        });
                        
                        const formData = new FormData(this);
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        
                        // Show loading
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> {{ __("index.saving") }}...';
                        
                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('attendance-modal'));
                                modal.hide();
                                
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || '{{ __("index.data_saved_successfully") }}.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                
                                // Reload page
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                // Show error message
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message || 'Terjadi kesalahan.'
                                });
                                
                                // Re-disable fields
                                disabledFields.forEach(field => {
                                    if (field.getAttribute('data-disabled') === 'true') {
                                        field.disabled = true;
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            
                            // Handle validation errors
                            if (error.errors) {
                                let errorMessage = '';
                                for (let field in error.errors) {
                                    errorMessage += error.errors[field].join('\n') + '\n';
                                }
                                
                                Swal.fire({
                                    icon: 'warning',
                                    title: '{{ __("index.validation_failed") }}!',
                                    text: errorMessage.trim(),
                                    confirmButtonColor: '#3085d6'
                                });
                                
                                // Highlight error fields
                                for (let field in error.errors) {
                                    const input = document.getElementById(field);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        input.addEventListener('input', function() {
                                            this.classList.remove('is-invalid');
                                        }, { once: true });
                                    }
                                }
                            } else {
                                // Handle other errors
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: error.message || '{{ __("error_occurred_while_saving_data") }}'
                                });
                            }
                            
                            // Re-disable fields
                            disabledFields.forEach(field => {
                                if (field.getAttribute('data-disabled') === 'true') {
                                    field.disabled = true;
                                }
                            });
                        })
                        .finally(() => {
                            // Reset button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                    });
                }
                
                // Auto-search on input (only for create mode)
                if (nisnInput && !isEditMode) {
                    let searchTimeout;
                    nisnInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            searchByNISN();
                        }, 800); // Auto-search after 800ms of typing
                    });
                    
                    // Search on Enter key (optional)
                    nisnInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            clearTimeout(searchTimeout);
                            searchByNISN();
                        }
                    });
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

            // Delete confirmation script with SweetAlert2
            function confirmDelete(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: `{{ __("index.attendance_will_be_deleted_permanently") }}"${name}"{{ __("index.attendance_will_be_deleted_permanently_end") }}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("index.yes_delete") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX delete request
                        fetch(`/attendances/${id}`, {
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
                                    title: '{{ __("index.success") }}!',
                                    text: data.message || '{{ __("index.data_deleted_successfully") }}.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                
                                // Reload page
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
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
                                text: '{{ __("error_occurred_while_deleting_data") }}'
                            });
                        });
                    }
                });
            }
        </script>
        
        <!-- Hidden delete forms for each item -->
        @foreach($attendances as $item)
            <form id="deleteForm-{{ $item->attendance_id }}" action="/attendances/{{ $item->attendance_id }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
