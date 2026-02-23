@extends('layouts.master')
@section('title')
    {{ __('index.permission_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.permission_list') }}
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
                                <h4 class="card-title mb-1">{{ __('index.permission_list') }}</h4>                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_permission') }}
                                </a>
                                <button class="btn btn-success" onclick="exportExcel()">
                                    <i class="mdi mdi-file-excel"></i> {{ __('index.export_excel') }}
                                </button>
                                <button class="btn btn-warning" onclick="showImportModal()">
                                    <i class="mdi mdi-file-import"></i> {{ __('index.import_excel') }}
                                </button>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_permission_name_guard') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">{{ __('index.show') }}:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $permissions->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $permissions->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $permissions->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $permissions->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                            <table class="table table-striped mb-0" id="permissions-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('index.permission_name') }}</th>
                                        <th>{{ __('index.guard_name') }}</th>
                                        <th>{{ __('index.description') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="permissions-tbody">
                                    @forelse ($permissions as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-info font-size-12">{{ $item->guard_name }}</span>
                                        </td>
                                        <td>{{ $item->description ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('permissions.edit', $item->id) }}" class="btn btn-sm btn-soft-primary">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->name }}')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{ __('index.no_permission_data') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($permissions->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="pagination-info">
                                    {{ __('index.showing') }} {{ $permissions->firstItem() }} {{ __('index.to') }} {{ $permissions->lastItem() }} {{ __('index.of') }} {{ $permissions->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination-links">
                                            {{-- Previous Link --}}
                                            @if($permissions->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="1" tabindex="-1">{{ __("index.previous") }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $permissions->currentPage() - 1 }}">{{ __("index.previous") }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $permissions->lastPage(); $i++)
                                                @if($i == $permissions->currentPage())
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
                                            @if($permissions->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $permissions->currentPage() + 1 }}">Next</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="{{ $permissions->lastPage() }}" tabindex="-1">Next</a>
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
    @endsection
    @section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
            let currentPage = 1;
            let currentSearch = '{{ request('q', '') }}';
            let currentPerPage = {{ $permissions->perPage() }};

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const perPageSelect = document.getElementById('per-page-select');
                const loadingSpinner = document.getElementById('loading-spinner');
                const permissionsTable = document.getElementById('permissions-table');
                const permissionsTbody = document.getElementById('permissions-tbody');
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
            });

            // Global function for AJAX search
            function performAjaxSearch() {
                const loadingSpinner = document.getElementById('loading-spinner');
                const permissionsTable = document.getElementById('permissions-table');
                const permissionsTbody = document.getElementById('permissions-tbody');
                const paginationLinks = document.getElementById('pagination-links');
                const paginationInfo = document.getElementById('pagination-info');
                
                showLoading();
                
                const params = new URLSearchParams();
                if (currentSearch) params.append('q', currentSearch);
                params.append('page', currentPage);
                params.append('per_page', currentPerPage);

                fetch(`{{ route('permissions.index') }}?${params.toString()}`, {
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
                    if (permissionsTable) permissionsTable.style.opacity = '0.5';
                }

                function hideLoading() {
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    if (permissionsTable) permissionsTable.style.opacity = '1';
                }

                function updateTable(permissionsData) {
                    if (!permissionsTbody) return;
                    
                    if (permissionsData.length === 0) {
                        permissionsTbody.innerHTML = '<tr><td colspan="4" class="text-center">{{ __('index.no_permission_data') }}</td></tr>';
                        return;
                    }

                    let html = '';
                    permissionsData.forEach((item) => {
                        html += `
                            <tr>
                                <td>
                                    <strong>${item.name}</strong>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info font-size-12">${item.guard_name}</span>
                                </td>
                                <td>${item.description || '-'}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="/permissions/${item.id}/edit" class="btn btn-sm btn-soft-primary">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('${item.id}', '${item.name.replace(/'/g, "\\'")}')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    permissionsTbody.innerHTML = html;
                }

                function updatePagination(pagination) {
                    if (!paginationInfo || !paginationLinks) return;
                    
                    // Update pagination info
                    const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
                    const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                    paginationInfo.textContent = `{{ __('index.showing') }} ${startItem} {{ __('index.to') }} ${endItem} {{ __('index.of') }} ${pagination.total} {{ __('index.data') }}`;

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
                            html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i} <span class="sr-only">(current)</span></a></li>`;
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

            // Export Excel
            function exportExcel() {
                const url = new URL(window.location.href);
                url.searchParams.set('export', 'excel');
                window.open(url.toString(), '_blank');
            }

            // Show Import Modal
            function showImportModal() {
                Swal.fire({
                    title: '{{ __('index.import_permission') }}',
                    html: `
                        <form id="importForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="importFile" class="form-label">{{ __('index.select_excel_file') }}</label>
                                <input type="file" class="form-control" id="importFile" accept=".xlsx,.xls" required>
                                <div class="form-text">{{ __('index.format_xlsx_or_xls') }}</div>
                            </div>
                        </form>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('index.import') }}',
                    cancelButtonText: '{{ __('index.cancel') }}',
                    preConfirm: () => {
                        const fileInput = document.getElementById('importFile');
                        if (!fileInput.files[0]) {
                            Swal.showValidationMessage('{{ __('index.please_select_file_first') }}!');
                            return false;
                        }
                        return fileInput.files[0];
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('file', result.value);
                        
                        fetch('{{ route('permissions.import') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('{{ __('index.success') }}!', data.message, 'success');
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __('index.error') }}!', data.message || '{{ __('index.error_occurred') }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __('index.error') }}!', '{{ __('index.error_importing_data') }}', 'error');
                        });
                    }
                });
            }

            // Delete confirmation script with SweetAlert2
            function confirmDelete(id, name) {
                Swal.fire({
                    title: '{{ __('index.are_you_sure') }}',
                    text: "{{ __('index.permission_will_be_deleted_permanently') }} \"" + name + "\"!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('index.yes_delete') }}',
                    cancelButtonText: '{{ __('index.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for deletion
                        fetch(`{{ route('permissions.destroy', ':id') }}`.replace(':id', id), {
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
                                Swal.fire('{{ __('index.deleted') }}!', data.message, 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('{{ __('index.error') }}!', data.message || '{{ __('index.error_occurred') }}', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('{{ __('index.error') }}!', '{{ __('index.error_deleting_permission') }}', 'error');
                        });
                    }
                });
            }
        </script>
        
        <!-- Hidden delete forms for each item -->
        @foreach($permissions as $item)
            <form id="deleteForm-{{ $item->id }}" action="{{ route('permissions.destroy', $item->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
