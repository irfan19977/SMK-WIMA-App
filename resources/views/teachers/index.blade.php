@extends('layouts.master')
@section('title')
    {{ __('index.teacher_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.teacher_list') }}
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
                            <h4 class="card-title mb-1">{{ __('index.teacher_list') }}</h4>                            </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> {{ __('index.add_teacher') }}
                            </a>
                            <button class="btn btn-success" onclick="exportExcel()">
                                <i class="mdi mdi-file-excel"></i> Export Excel
                            </button>
                            <button class="btn btn-info" onclick="printPDF()">
                                <i class="mdi mdi-file-pdf"></i> Cetak PDF
                            </button>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{{ __('index.search_teacher_name_nip_email') }}" id="search-input" value="{{ request('q') }}">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <label class="mb-0">Show:</label>
                                <select class="form-select w-auto" id="per-page-select">
                                    <option value="10" {{ $teachers->perPage() == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $teachers->perPage() == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $teachers->perPage() == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $teachers->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                        <table class="table table-striped mb-0" id="teachers-table">
                            <thead>
                                <tr>
                                    <th>{{ __('index.no') }}</th>
                                    <th>{{ __('index.name') }}</th>
                                    <th>{{ __('index.nip') }}</th>
                                    <th>{{ __('index.email') }}</th>
                                    <th>{{ __('index.phone') }}</th>
                                    <th>{{ __('index.education') }}</th>
                                    <th>{{ __('index.status') }}</th>
                                    <th>{{ __('index.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="teachers-tbody">
                                @forelse ($teachers as $item)
                                <tr>
                                    <th scope="row">{{ ($teachers->currentPage() - 1) * $teachers->perPage() + $loop->iteration }}</th>
                                    <td>
                                        <a href="{{ route('profile.show') }}?user_id={{ $item->user_id }}" class="text-decoration-none fw-bold text-primary">
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->nip }}</span>
                                    </td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->user->phone ?? '-' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $item->education_level }} - {{ $item->education_major }}</small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $item->user->status ? 'bg-success' : 'bg-light' }} font-size-12">
                                            {{ $item->user->status ? __('index.active') : __('index.blocked') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('teachers.edit', $item->id) }}" class="btn btn-sm btn-soft-primary">
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
                                    <td colspan="8" class="text-center">{{ __('index.no_teacher_data') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($teachers->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted" id="pagination-info">
                                {{ __('index.showing') }} {{ $teachers->firstItem() }} {{ __('index.to') }} {{ $teachers->lastItem() }} {{ __('index.of') }} {{ $teachers->total() }} {{ __('index.data') }}
                            </div>
                            <div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" id="pagination-links">
                                        {{-- Previous Link --}}
                                        @if($teachers->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $teachers->currentPage() - 1 }}">Previous</a>
                                            </li>
                                        @endif

                                        {{-- Page Numbers --}}
                                        @for($i = 1; $i <= $teachers->lastPage(); $i++)
                                            @if($i == $teachers->currentPage())
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
                                        @if($teachers->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $teachers->currentPage() + 1 }}">Next</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="{{ $teachers->lastPage() }}" tabindex="-1">Next</a>
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
        let currentPerPage = {{ $teachers->perPage() }};

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const perPageSelect = document.getElementById('per-page-select');
            const loadingSpinner = document.getElementById('loading-spinner');
            const teachersTable = document.getElementById('teachers-table');
            const teachersTbody = document.getElementById('teachers-tbody');
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
            const teachersTable = document.getElementById('teachers-table');
            const teachersTbody = document.getElementById('teachers-tbody');
            const paginationLinks = document.getElementById('pagination-links');
            const paginationInfo = document.getElementById('pagination-info');
            
            showLoading();
            
            const params = new URLSearchParams();
            if (currentSearch) params.append('q', currentSearch);
            params.append('page', currentPage);
            params.append('per_page', currentPerPage);

            fetch(`{{ route('teachers.index') }}?${params.toString()}`, {
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
                if (teachersTable) teachersTable.style.opacity = '0.5';
            }

            function hideLoading() {
                if (loadingSpinner) loadingSpinner.style.display = 'none';
                if (teachersTable) teachersTable.style.opacity = '1';
            }

            function updateTable(teachersData) {
                if (!teachersTbody) return;
                
                if (teachersData.length === 0) {
                    teachersTbody.innerHTML = '<tr><td colspan="8" class="text-center">{{ __('index.no_teacher_data') }}</td></tr>';
                    return;
                }

                let html = '';
                teachersData.forEach((item, index) => {
                    const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                    html += `
                        <tr>
                            <th scope="row">${rowNumber}</th>
                            <td>
                                <strong>${item.name}</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">${item.nip}</span>
                            </td>
                            <td>${item.user.email}</td>
                            <td>${item.user.phone || '-'}</td>
                            <td>
                                <small class="text-muted">${item.education_level} - ${item.education_major}</small>
                            </td>
                            <td>
                                <span class="badge rounded-pill ${item.user.status ? 'bg-success' : 'bg-light'} font-size-12">
                                    ${item.user.status ? 'Aktif' : 'Diblokir'}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/teachers/${item.id}/edit" class="btn btn-sm btn-soft-primary">
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
                teachersTbody.innerHTML = html;
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

        // Delete confirmation script with SweetAlert2
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Guru \"" + name + "\" akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Use AJAX for deletion
                    fetch(`/teachers/${id}`, {
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
                            Swal.fire('Dihapus!', data.message, 'success');
                            // Refresh the table
                            performAjaxSearch();
                        } else {
                            Swal.fire('Error!', data.message || 'Terjadi kesalahan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus guru', 'error');
                    });
                }
            });
        }
    </script>
    
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
