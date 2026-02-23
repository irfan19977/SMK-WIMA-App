@extends('layouts.master')
@section('title')
    {{ __('index.setting_schedule_title') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.setting_schedule_title') }}
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
                                <h4 class="card-title mb-1">{{ __('index.setting_schedule_list') }}</h4>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="openScheduleModal()">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_setting_schedule') }}
                                </button>
                                <button class="btn btn-success" onclick="exportExcel()">
                                    <i class="mdi mdi-file-excel"></i> {{ __('index.export_excel') }}
                                </button>
                                <button class="btn btn-info" onclick="printPDF()">
                                    <i class="mdi mdi-file-pdf"></i> {{ __('index.print_pdf') }}
                                </button>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_setting_schedule_placeholder') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">{{ __('index.show') }}:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $settings->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $settings->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $settings->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $settings->perPage() == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{ __('index.loading') }}...</span>
                                </div>
                            </div>
                            <table class="table table-hover table-centered mb-0" id="settings-table">
                                <thead>
                                    <tr>
                                        <th width="10%">{{ __('index.no') }}</th>
                                        <th width="20%">{{ __('index.day') }}</th>
                                        <th width="15%">{{ __('index.start_time') }}</th>
                                        <th width="15%">{{ __('index.end_time') }}</th>
                                        <th width="15%">{{ __('index.duration') }}</th>
                                        <th width="10%">{{ __('index.status') }}</th>
                                        <th width="15%" class="text-center">{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="settings-tbody">
                                    @forelse($settings as $index => $setting)
                                        <tr>
                                            <td>{{ ($settings->currentPage() - 1) * $settings->perPage() + $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ __('index.' . strtolower($setting->day)) }}</span>
                                            </td>
                                            <td>
                                                <a href="#" class="text-dark fw-medium">
                                                    {{ \Carbon\Carbon::parse($setting->start_time)->format('H:i') }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="text-dark fw-medium">
                                                    {{ \Carbon\Carbon::parse($setting->end_time)->format('H:i') }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    @php
                                                        $start = \Carbon\Carbon::parse($setting->start_time);
                                                        $end = \Carbon\Carbon::parse($setting->end_time);
                                                        $duration = $start->diffInHours($end);
                                                    @endphp
                                                    {{ $duration }} {{ __('index.hours') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($setting->deleted_at)
                                                    <span class="badge bg-danger">{{ __('index.deleted') }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ __('index.active') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if(!$setting->deleted_at)
                                                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="editSchedule('{{ $setting->id }}')" title="{{ __('index.edit') }}">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="deleteSchedule('{{ $setting->id }}', '{{ $setting->day }}')" title="{{ __('index.delete') }}">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-soft-success" onclick="restoreSchedule('{{ $setting->id }}', '{{ $setting->day }}')" title="{{ __('index.restore') }}">
                                                            <i class="mdi mdi-restore"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="forceDeleteSchedule('{{ $setting->id }}', '{{ $setting->day }}')" title="{{ __('index.delete_permanently') }}">
                                                            <i class="mdi mdi-delete-forever"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                       <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-calendar-blank font-size-48 d-block mb-2"></i>
                                                    <p>{{ __('index.no_data_available') }}</p>
                                                    <p class="small">{{ __('index.add_setting_schedule') }}</p>
                                                    <button type="button" class="btn btn-primary" onclick="openScheduleModal()">
                                                        <i class="mdi mdi-plus me-1"></i> {{ __('index.add_setting_schedule') }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($settings->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="pagination-info">
                                    {{ __('index.showing') }} {{ $settings->firstItem() }} {{ __('index.to') }} {{ $settings->lastItem() }} {{ __('index.of') }} {{ $settings->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination-links">
                                            {{-- Previous Link --}}
                                            @if($settings->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="1" tabindex="-1">{{ __('index.previous') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $settings->currentPage() - 1 }}">{{ __('index.previous') }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $settings->lastPage(); $i++)
                                                @if($i == $settings->currentPage())
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }} <span class="sr-only">({{ __('index.current') }})</span></a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            {{-- Next Link --}}
                                            @if($settings->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $settings->currentPage() + 1 }}">{{ __('index.next') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="{{ $settings->lastPage() }}" tabindex="-1">{{ __('index.next') }}</a>
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

        <!-- Setting Schedule Modal -->
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
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
        
        <script>
            let currentPage = 1;
            let currentSearch = '{{ request('q', '') }}';
            let currentPerPage = {{ $settings->perPage() }};

            // Day translation function
            function getDayTranslation(day) {
                const translations = {
                    'Senin': '{{ __("index.senin") }}',
                    'Selasa': '{{ __("index.selasa") }}',
                    'Rabu': '{{ __("index.rabu") }}',
                    'Kamis': '{{ __("index.kamis") }}',
                    'Jumat': '{{ __("index.jumat") }}',
                    'Sabtu': '{{ __("index.sabtu") }}',
                    'Minggu': '{{ __("index.minggu") }}'
                };
                return translations[day] || day;
            }

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const perPageSelect = document.getElementById('per-page-select');
                const loadingSpinner = document.getElementById('loading-spinner');
                const settingsTable = document.getElementById('settings-table');
                const settingsTbody = document.getElementById('settings-tbody');
                const paginationLinks = document.getElementById('pagination-links');
                const paginationInfo = document.getElementById('pagination-info');

                // Set initial search value
                if (searchInput && currentSearch) {
                    searchInput.value = currentSearch;
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

                // Bind action buttons
                bindActionButtons();
            });

            // Global function for AJAX search
            function performAjaxSearch() {
                const loadingSpinner = document.getElementById('loading-spinner');
                const settingsTable = document.getElementById('settings-table');
                const settingsTbody = document.getElementById('settings-tbody');
                const paginationLinks = document.getElementById('pagination-links');
                const paginationInfo = document.getElementById('pagination-info');
                
                showLoading();
                
                const params = new URLSearchParams();
                if (currentSearch) params.append('q', currentSearch);
                params.append('page', currentPage);
                params.append('per_page', currentPerPage);

                fetch(`{{ route('setting-schedule.index') }}?${params.toString()}`, {
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
                        updateTable(data.settings);
                        updatePagination(data.settings);
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
                    if (settingsTable) settingsTable.style.opacity = '0.5';
                }

                function hideLoading() {
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    if (settingsTable) settingsTable.style.opacity = '1';
                }

                function updateTable(settingsData) {
                    if (!settingsTbody) return;
                    
                    if (settingsData.data.length === 0) {
                        settingsTbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="mdi mdi-calendar-blank font-size-48 d-block mb-2"></i>
                                        <p>{{ __("index.no_data_available") }}</p>
                                        <p class="small">{{ __("index.add_setting_schedule") }}</p>
                                        <button type="button" class="btn btn-primary" onclick="openScheduleModal()">
                                            <i class="mdi mdi-plus me-1"></i> {{ __("index.add_setting_schedule") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    let html = '';
                    settingsData.data.forEach((setting, index) => {
                        const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                        const startTime = new Date(setting.start_time);
                        const endTime = new Date(setting.end_time);
                        const duration = Math.round((endTime - startTime) / (1000 * 60 * 60));
                        
                        html += `
                            <tr>
                                <td>${rowNumber}</td>
                                <td>
                                    <span class="badge bg-primary">${getDayTranslation(setting.day)}</span>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-medium">
                                        ${startTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-medium">
                                        ${endTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        ${duration} {{ __("index.hours") }}
                                    </span>
                                </td>
                                <td>
                                    ${setting.deleted_at ? 
                                        '<span class="badge bg-danger">{{ __("index.deleted") }}</span>' : 
                                        '<span class="badge bg-success">{{ __("index.active") }}</span>'
                                    }
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        ${!setting.deleted_at ? `
                                            <button type="button" class="btn btn-sm btn-soft-primary" onclick="editSchedule('${setting.id}')" title="{{ __('index.edit') }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" onclick="deleteSchedule('${setting.id}', '${setting.day}')" title="{{ __('index.delete') }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-soft-success" onclick="restoreSchedule('${setting.id}', '${setting.day}')" title="{{ __('index.restore') }}">
                                                <i class="mdi mdi-restore"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" onclick="forceDeleteSchedule('${setting.id}', '${setting.day}')" title="{{ __('index.delete_permanently') }}">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </button>
                                        `}
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    settingsTbody.innerHTML = html;
                }

                function updatePagination(settingsData) {
                    if (!paginationInfo || !paginationLinks) return;
                    
                    // Update pagination info
                    const startItem = (settingsData.current_page - 1) * settingsData.per_page + 1;
                    const endItem = Math.min(settingsData.current_page * settingsData.per_page, settingsData.total);
                    paginationInfo.textContent = `{{ __("index.showing") }} ${startItem} {{ __("index.to") }} ${endItem} {{ __("index.of") }} ${settingsData.total} {{ __("index.data") }}`;

                    // Update pagination links
                    let html = '';
                    
                    // Previous link
                    if (settingsData.current_page === 1) {
                        html += '<li class="page-item disabled"><a class="page-link" href="#" data-page="1" tabindex="-1">{{ __("index.previous") }}</a></li>';
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${settingsData.current_page - 1}">{{ __("index.previous") }}</a></li>`;
                    }

                    // Page numbers
                    for (let i = 1; i <= settingsData.last_page; i++) {
                        if (i === settingsData.current_page) {
                            html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i} <span class="sr-only">({{ __("index.current") }})</span></a></li>`;
                        } else {
                            html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                        }
                    }

                    // Next link
                    if (settingsData.current_page === settingsData.last_page) {
                        html += `<li class="page-item disabled"><a class="page-link" href="#" data-page="${settingsData.last_page}" tabindex="-1">{{ __("index.next") }}</a></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${settingsData.current_page + 1}">{{ __("index.next") }}</a></li>`;
                    }

                    paginationLinks.innerHTML = html;
                }
            }

            function bindActionButtons() {
                // Buttons are already bound via onclick attributes
            }

            // Delete Schedule
            function deleteSchedule(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: "{{ __("index.setting_schedule_will_be_deleted_part1") }}" + name + "{{ __("index.setting_schedule_will_be_deleted_part2") }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("index.yes_delete") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for deletion
                        fetch(`/setting-schedule/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success || data.message) {
                                Swal.fire('{{ __("index.deleted") }}!', data.message || 'Data berhasil dihapus', 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __("index.error") }}!', data.message || '{{ __("index.error_occurred") }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __("index.error") }}!', '{{ __("index.error_deleting_setting_schedule") }}', 'error');
                        });
                    }
                });
            }

            // Restore Schedule
            function restoreSchedule(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: "{{ __("index.setting_schedule_will_be_restored_part1") }}" + name + "{{ __("index.setting_schedule_will_be_restored_part2") }}",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __("index.yes_restore") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for restore
                        fetch(`/setting-schedule/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success || data.message) {
                                Swal.fire('{{ __("index.restored") }}!', data.message || 'Data berhasil dikembalikan', 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __("index.error") }}!', data.message || '{{ __("index.error_occurred") }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __("index.error") }}!', '{{ __("index.error_restoring_setting_schedule") }}', 'error');
                        });
                    }
                });
            }

            // Force Delete Schedule
            function forceDeleteSchedule(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: "{{ __("index.setting_schedule_will_be_deleted_permanently_part1") }}" + name + "{{ __("index.setting_schedule_will_be_deleted_permanently_part2") }}",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __("index.yes_delete_permanently") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for force delete
                        fetch(`/setting-schedule/${id}/force-delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success || data.message) {
                                Swal.fire('{{ __("index.deleted_permanently") }}!', data.message || 'Data berhasil dihapus permanen', 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __("index.error") }}!', data.message || '{{ __("index.error_occurred") }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __("index.error") }}!', '{{ __("index.error_force_deleting_setting_schedule") }}', 'error');
                        });
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

            // Modal functions
            function openScheduleModal() {
                fetch('{{ route("setting-schedule.create") }}', {
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
                        title: '{{ __("index.error") }}',
                        text: '{{ __("index.failed_to_load_form") }}'
                    });
                });
            }

            function editSchedule(id) {
                fetch(`/setting-schedule/${id}/edit`, {
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
                        title: '{{ __("index.error") }}',
                        text: '{{ __("index.failed_to_load_form") }}'
                    });
                });
            }
        </script>
@endsection
