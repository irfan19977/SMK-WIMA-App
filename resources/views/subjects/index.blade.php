@extends('layouts.master')
@section('title')
    {{ __('index.subjects_title') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.subjects_title') }}
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
                                <h4 class="card-title mb-1">{{ __('index.subject_list') }}</h4>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="openSubjectModal()">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_subject') }}
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
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_subject_placeholder') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">{{ __('index.show') }}:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $subjects->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $subjects->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $subjects->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $subjects->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                            <table class="table table-striped mb-0" id="subjects-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('index.no') }}.</th>
                                        <th>{{ __('index.code') }}</th>
                                        <th>{{ __('index.subject_name') }}</th>
                                        <th>{{ __('index.created_by') }}</th>
                                        <th>{{ __('index.created_at') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="subjects-tbody">
                                    @forelse($subjects as $index => $subject)
                                        <tr>
                                            <td>{{ ($subjects->currentPage() - 1) * $subjects->perPage() + $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $subject->code }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $subject->name }}</strong>
                                            </td>
                                            <td>
                                                @if($subject->creator)
                                                    {{ $subject->creator->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $subject->created_at->format('d M Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($subject->deleted_at)
                                                    <span class="badge bg-danger">{{ __('index.deleted') }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ __('index.active') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if(!$subject->deleted_at)
                                                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="editSubject('{{ $subject->id }}')" title="{{ __('index.edit') }}">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="deleteSubject('{{ $subject->id }}', '{{ $subject->name }}')" title="{{ __('index.delete') }}">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-soft-success" onclick="restoreSubject('{{ $subject->id }}', '{{ $subject->name }}')" title="{{ __('index.restore') }}">
                                                            <i class="mdi mdi-restore"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="forceDeleteSubject('{{ $subject->id }}', '{{ $subject->name }}')" title="{{ __('index.delete_permanently') }}">
                                                            <i class="mdi mdi-delete-forever"></i>
                                                        </button>
                                                    @endif
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
                        @if($subjects->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="pagination-info">
                                    {{ __('index.showing') }} {{ $subjects->firstItem() }} {{ __('index.to') }} {{ $subjects->lastItem() }} {{ __('index.of') }} {{ $subjects->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination-links">
                                            {{-- Previous Link --}}
                                            @if($subjects->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="1" tabindex="-1">{{ __('index.previous') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $subjects->currentPage() - 1 }}">{{ __('index.previous') }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $subjects->lastPage(); $i++)
                                                @if($i == $subjects->currentPage())
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
                                            @if($subjects->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $subjects->currentPage() + 1 }}">{{ __('index.next') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="{{ $subjects->lastPage() }}" tabindex="-1">{{ __('index.next') }}</a>
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

        <!-- Subject Modal -->
        <div class="modal fade" id="subject-modal" tabindex="-1" aria-labelledby="subject-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subject-modal-label">Modal title</h5>
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
            let currentPage = 1;
            let currentSearch = '{{ request('q', '') }}';
            let currentPerPage = {{ $subjects->perPage() }};

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const perPageSelect = document.getElementById('per-page-select');
                const loadingSpinner = document.getElementById('loading-spinner');
                const subjectsTable = document.getElementById('subjects-table');
                const subjectsTbody = document.getElementById('subjects-tbody');
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
                const subjectsTable = document.getElementById('subjects-table');
                const subjectsTbody = document.getElementById('subjects-tbody');
                const paginationLinks = document.getElementById('pagination-links');
                const paginationInfo = document.getElementById('pagination-info');
                
                showLoading();
                
                const params = new URLSearchParams();
                if (currentSearch) params.append('q', currentSearch);
                params.append('page', currentPage);
                params.append('per_page', currentPerPage);

                fetch(`{{ route('subjects.index') }}?${params.toString()}`, {
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
                        updateTable(data.subjects);
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
                    if (subjectsTable) subjectsTable.style.opacity = '0.5';
                }

                function hideLoading() {
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    if (subjectsTable) subjectsTable.style.opacity = '1';
                }

                function updateTable(subjectsData) {
                    if (!subjectsTbody) return;
                    
                    if (subjectsData.length === 0) {
                        subjectsTbody.innerHTML = `
                            <tr>
                                <td colspan="8" class="text-center">{{ __("index.no_data_available") }}</td>
                            </tr>
                        `;
                        return;
                    }

                    let html = '';
                    subjectsData.forEach((subject, index) => {
                        const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                        html += `
                            <tr>
                                <td>${rowNumber}</td>
                                <td>
                                    <span class="badge bg-light text-dark">${subject.code}</span>
                                </td>
                                <td>
                                    <strong>${subject.name}</strong>
                                </td>
                                <td>
                                    ${subject.creator ? subject.creator.name : '<span class="text-muted">-</span>'}
                                </td>
                                <td>
                                    <small class="text-muted">${new Date(subject.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</small>
                                </td>
                                <td>
                                    ${subject.deleted_at ? 
                                        '<span class="badge bg-danger">Dihapus</span>' : 
                                        '<span class="badge bg-success">Aktif</span>'}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        ${!subject.deleted_at ? `
                                            <button type="button" class="btn btn-sm btn-soft-primary" onclick="editSubject('${subject.id}')" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" onclick="deleteSubject('${subject.id}', '${subject.name.replace(/'/g, "\\'")}')" title="Hapus">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-soft-success" onclick="restoreSubject('${subject.id}', '${subject.name.replace(/'/g, "\\'")}')" title="Restore">
                                                <i class="mdi mdi-restore"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" onclick="forceDeleteSubject('${subject.id}', '${subject.name.replace(/'/g, "\\'")}')" title="Hapus Permanen">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </button>
                                        `}
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    subjectsTbody.innerHTML = html;
                }

                function updatePagination(pagination) {
                    if (!paginationInfo || !paginationLinks) return;
                    
                    // Update pagination info
                    const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
                    const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                    paginationInfo.textContent = `{{ __("index.showing") }} ${startItem} {{ __("index.to") }} ${endItem} {{ __("index.of") }} ${pagination.total} {{ __("index.data") }}`;

                    // Update pagination links
                    let html = '';
                    
                    // Previous link
                    if (pagination.current_page === 1) {
                        html += '<li class="page-item disabled"><a class="page-link" href="#" data-page="1" tabindex="-1">{{ __("index.previous") }}</a></li>';
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">{{ __("index.previous") }}</a></li>`;
                    }

                    // Page numbers
                    for (let i = 1; i <= pagination.last_page; i++) {
                        if (i === pagination.current_page) {
                            html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i} <span class="sr-only">({{ __("index.current") }})</span></a></li>`;
                        } else {
                            html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                        }
                    }

                    // Next link
                    if (pagination.current_page === pagination.last_page) {
                        html += `<li class="page-item disabled"><a class="page-link" href="#" data-page="${pagination.last_page}" tabindex="-1">{{ __("index.next") }}</a></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">{{ __("index.next") }}</a></li>`;
                    }

                    paginationLinks.innerHTML = html;
                }
            }

            function bindActionButtons() {
                // Buttons are already bound via onclick attributes
            }

            // Delete Subject
            function deleteSubject(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: "{{ __("index.subject_will_be_deleted_part1") }}" + name + "{{ __("index.subject_will_be_deleted_part2") }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("index.yes_delete") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for deletion
                        fetch(`/subjects/${id}`, {
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
                            if (data.success) {
                                Swal.fire('{{ __("index.deleted") }}!', data.message, 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __("index.error") }}!', data.message || '{{ __("index.error_occurred") }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __("index.error") }}!', '{{ __("index.error_deleting_subject") }}', 'error');
                        });
                    }
                });
            }

            // Restore Subject
            function restoreSubject(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: "{{ __("index.subject_will_be_restored_part1") }}" + name + "{{ __("index.subject_will_be_restored_part2") }}",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __("index.yes_restore") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for restore
                        fetch(`/subjects/${id}/restore`, {
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
                            if (data.success) {
                                Swal.fire('{{ __("index.restored") }}!', data.message, 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __("index.error") }}!', data.message || '{{ __("index.error_occurred") }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __("index.error") }}!', '{{ __("index.error_restoring_subject") }}', 'error');
                        });
                    }
                });
            }

            // Force Delete Subject
            function forceDeleteSubject(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: "{{ __("index.subject_will_be_deleted_permanently_part1") }}" + name + "{{ __("index.subject_will_be_deleted_permanently_part2") }}",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __("index.yes_delete_permanently") }}',
                    cancelButtonText: '{{ __("index.cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for force delete
                        fetch(`/subjects/${id}/force-delete`, {
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
                            if (data.success) {
                                Swal.fire('{{ __("index.deleted_permanently") }}!', data.message, 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __("index.error") }}!', data.message || '{{ __("index.error_occurred") }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __("index.error") }}!', '{{ __("index.error_force_deleting_subject") }}', 'error');
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
            function openSubjectModal() {
                fetch('{{ route("subjects.create") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('subject-modal-label').textContent = data.title;
                        document.querySelector('#subject-modal .modal-body').innerHTML = data.html;
                        
                        const modal = new bootstrap.Modal(document.getElementById('subject-modal'));
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

            function editSubject(id) {
                fetch(`/subjects/${id}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('subject-modal-label').textContent = data.title;
                        document.querySelector('#subject-modal .modal-body').innerHTML = data.html;
                        
                        const modal = new bootstrap.Modal(document.getElementById('subject-modal'));
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
