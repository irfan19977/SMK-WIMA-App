@extends('layouts.master')
@section('title')
    {{ __('index.parent_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.parent_list') }}
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
                            <h4 class="card-title mb-1">{{ __('index.parent_list') }}</h4>                            </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('parents.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> {{ __('index.add_parent') }}
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
                                <input type="text" class="form-control" placeholder="{{ __('index.search_parent_name_student_nisn') }}" id="search-input" value="{{ request('q') }}">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <label class="mb-0">Show:</label>
                                <select class="form-select w-auto" id="per-page-select">
                                    <option value="10" {{ $parents->perPage() == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $parents->perPage() == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $parents->perPage() == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $parents->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                        <table class="table table-striped mb-0" id="parents-table">
                            <thead>
                                <tr>
                                    <th>{{ __('index.no') }}</th>
                                    <th>{{ __('index.name') }}</th>
                                    <th>{{ __('index.parent_status') }}</th>
                                    <th>{{ __('index.email') }}</th>
                                    <th>{{ __('index.phone') }}</th>
                                    <th>{{ __('index.child') }}</th>
                                    <th>{{ __('index.account_status') }}</th>
                                    <th>{{ __('index.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="parents-tbody">
                                @forelse ($parents as $item)
                                <tr>
                                    <th scope="row">{{ ($parents->currentPage() - 1) * $parents->perPage() + $loop->iteration }}</th>
                                    <td>
                                        <a href="{{ route('profile.show') }}?user_id={{ $item->user_id }}" class="text-decoration-none fw-bold text-primary">
                                            {{ $item->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                        $statusClass = 'bg-primary';
                                        $statusText = $item->status ?? 'ayah';
                                        
                                        switch($item->status) {
                                            case 'ayah':
                                                $statusClass = 'bg-primary';
                                                $statusText = __('index.father');
                                                break;
                                            case 'ibu':
                                                $statusClass = 'bg-pink';
                                                $statusText = __('index.mother');
                                                break;
                                            case 'wali':
                                                $statusClass = 'bg-info';
                                                $statusText = __('index.guardian');
                                                break;
                                        }
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }} font-size-12">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->user->phone ?? '-' }}</td>
                                    <td>
                                        @if($item->student)
                                            <small class="text-muted">{{ $item->student->name }} ({{ $item->student->nisn ?? 'No NISN' }})</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $item->user->status ? 'bg-success' : 'bg-light' }} font-size-12">
                                            {{ $item->user->status ? __('index.active') : __('index.blocked') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('parents.edit', $item->id) }}" class="btn btn-sm btn-soft-primary">
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
                                    <td colspan="8" class="text-center">{{ __('index.no_parent_data') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($parents->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted" id="pagination-info">
                                {{ __('index.showing') }} {{ $parents->firstItem() }} {{ __('index.to') }} {{ $parents->lastItem() }} {{ __('index.of') }} {{ $parents->total() }} {{ __('index.data') }}
                            </div>
                            <div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" id="pagination-links">
                                        {{-- Previous Link --}}
                                        @if($parents->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $parents->currentPage() - 1 }}">Previous</a>
                                            </li>
                                        @endif

                                        {{-- Page Numbers --}}
                                        @for($i = 1; $i <= $parents->lastPage(); $i++)
                                            @if($i == $parents->currentPage())
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
                                        @if($parents->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $parents->currentPage() + 1 }}">Next</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="{{ $parents->lastPage() }}" tabindex="-1">Next</a>
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
        let currentPerPage = {{ $parents->perPage() }};

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const perPageSelect = document.getElementById('per-page-select');
            const loadingSpinner = document.getElementById('loading-spinner');
            const parentsTable = document.getElementById('parents-table');
            const parentsTbody = document.getElementById('parents-tbody');
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
            const parentsTable = document.getElementById('parents-table');
            const parentsTbody = document.getElementById('parents-tbody');
            const paginationLinks = document.getElementById('pagination-links');
            const paginationInfo = document.getElementById('pagination-info');
            
            showLoading();
            
            const params = new URLSearchParams();
            if (currentSearch) params.append('q', currentSearch);
            params.append('page', currentPage);
            params.append('per_page', currentPerPage);

            fetch(`{{ route('parents.index') }}?${params.toString()}`, {
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
                if (parentsTable) parentsTable.style.opacity = '0.5';
            }

            function hideLoading() {
                if (loadingSpinner) loadingSpinner.style.display = 'none';
                if (parentsTable) parentsTable.style.opacity = '1';
            }

            function updateTable(parentsData) {
                if (!parentsTbody) return;
                
                if (parentsData.length === 0) {
                    parentsTbody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data wali murid</td></tr>';
                    return;
                }

                let html = '';
                parentsData.forEach((item, index) => {
                    const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                    
                    // Determine status badge
                    let statusClass = 'bg-primary';
                    let statusText = item.status || 'ayah';
                    switch(item.status) {
                        case 'ayah':
                            statusClass = 'bg-primary';
                            statusText = 'Ayah';
                            break;
                        case 'ibu':
                            statusClass = 'bg-pink';
                            statusText = 'Ibu';
                            break;
                        case 'wali':
                            statusClass = 'bg-info';
                            statusText = 'Wali';
                            break;
                    }
                    
                    html += `
                        <tr>
                            <th scope="row">${rowNumber}</th>
                            <td>
                                <strong>${item.name}</strong>
                            </td>
                            <td>
                                <span class="badge rounded-pill ${statusClass} font-size-12">
                                    ${statusText}
                                </span>
                            </td>
                            <td>${item.email}</td>
                            <td>${item.phone || '-'}</td>
                            <td>
                                ${item.student ? 
                                    `<small class="text-muted">${item.student.name} (${item.student.nisn || 'No NISN'})</small>` : 
                                    '<small class="text-muted">-</small>'}
                            </td>
                            <td>
                                <span class="badge rounded-pill ${item.user_status ? 'bg-success' : 'bg-light'} font-size-12">
                                    ${item.user_status ? 'Aktif' : 'Diblokir'}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/parents/${item.id}/edit" class="btn btn-sm btn-soft-primary">
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
                parentsTbody.innerHTML = html;
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
                text: "Wali murid \"" + name + "\" akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Use AJAX for deletion
                    fetch(`/parents/${id}`, {
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
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus wali murid', 'error');
                    });
                }
            });
        }
    </script>
    
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
