@extends('layouts.master')
@section('title')
    Screen Sharing Sessions
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    Screen Sharing Sessions
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
                                <h4 class="card-title mb-1">Screen Sharing Sessions</h4>                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('screen-shares.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Create New Session
                                </a>
                                <button class="btn btn-success" onclick="exportExcel()">
                                    <i class="mdi mdi-file-excel"></i> Export Excel
                                </button>
                                <button class="btn btn-info" onclick="printPDF()">
                                    <i class="mdi mdi-file-pdf"></i> Print PDF
                                </button>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by room code, title or status" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">Show:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $screenShares->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $screenShares->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $screenShares->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $screenShares->perPage() == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <table class="table table-striped mb-0" id="screen-shares-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Room Code</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Participants</th>
                                        <th>Started At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="screen-shares-tbody">
                                    @forelse ($screenShares as $screenShare)
                                    <tr>
                                        <th scope="row">{{ ($screenShares->currentPage() - 1) * $screenShares->perPage() + $loop->iteration }}</th>
                                        <td>
                                            <span class="badge rounded-pill bg-info font-size-12 font-weight-bold">{{ $screenShare->room_code }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $screenShare->title ?: 'Untitled Session' }}</strong>
                                        </td>
                                        <td>
                                            @if($screenShare->status == 'active')
                                                <span class="badge rounded-pill bg-success font-size-12">Active</span>
                                            @elseif($screenShare->status == 'ended')
                                                <span class="badge rounded-pill bg-danger font-size-12">Ended</span>
                                            @else
                                                <span class="badge rounded-pill bg-warning font-size-12">Paused</span>
                                            @endif
                                        </td>
                                        <td>{{ $screenShare->participants->count() }} participants</td>
                                        <td>{{ $screenShare->started_at ? $screenShare->started_at->format('M d, Y H:i') : '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('screen-shares.show', $screenShare) }}" class="btn btn-sm btn-soft-primary">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                @if($screenShare->status == 'active')
                                                    <a href="{{ route('screen-shares.show', $screenShare) }}" class="btn btn-sm btn-soft-success">
                                                        <i class="mdi mdi-desktop-mac"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmEnd('{{ $screenShare->id }}', '{{ $screenShare->room_code }}')">
                                                        <i class="mdi mdi-stop"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No screen sharing sessions found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($screenShares->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="pagination-info">
                                    Showing {{ $screenShares->firstItem() }} to {{ $screenShares->lastItem() }} of {{ $screenShares->total() }} data
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination-links">
                                            {{-- Previous Link --}}
                                            @if($screenShares->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $screenShares->currentPage() - 1 }}">Previous</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $screenShares->lastPage(); $i++)
                                                @if($i == $screenShares->currentPage())
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
                                            @if($screenShares->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $screenShares->currentPage() + 1 }}">Next</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="{{ $screenShares->lastPage() }}" tabindex="-1">Next</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        @else
                            @if($screenShares->count() == 0)
                                <div class="text-center py-5">
                                    <i class="mdi mdi-desktop-mac mdi-48px text-muted mb-3"></i>
                                    <h5 class="text-muted">No screen sharing sessions yet</h5>
                                    <p class="text-muted">Create your first screen sharing session to get started.</p>
                                    <a href="{{ route('screen-shares.create') }}" class="btn btn-primary">
                                        <i class="mdi mdi-plus mr-2"></i>Create Session
                                    </a>
                                </div>
                            @endif
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
            let currentPerPage = {{ $screenShares->perPage() }};

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const perPageSelect = document.getElementById('per-page-select');
                const loadingSpinner = document.getElementById('loading-spinner');
                const screenSharesTable = document.getElementById('screen-shares-table');
                const screenSharesTbody = document.getElementById('screen-shares-tbody');
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
                const screenSharesTable = document.getElementById('screen-shares-table');
                const screenSharesTbody = document.getElementById('screen-shares-tbody');
                const paginationLinks = document.getElementById('pagination-links');
                const paginationInfo = document.getElementById('pagination-info');
                
                showLoading();
                
                const params = new URLSearchParams();
                if (currentSearch) params.append('q', currentSearch);
                params.append('page', currentPage);
                params.append('per_page', currentPerPage);

                fetch(`{{ route('screen-shares.index') }}?${params.toString()}`, {
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
                    if (screenSharesTable) screenSharesTable.style.opacity = '0.5';
                }

                function hideLoading() {
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    if (screenSharesTable) screenSharesTable.style.opacity = '1';
                }

                function updateTable(screenSharesData) {
                    if (!screenSharesTbody) return;
                    
                    if (screenSharesData.length === 0) {
                        screenSharesTbody.innerHTML = '<tr><td colspan="7" class="text-center">No screen sharing sessions found</td></tr>';
                        return;
                    }

                    let html = '';
                    screenSharesData.forEach((item, index) => {
                        const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                        const statusBadge = item.status === 'active' 
                            ? '<span class="badge rounded-pill bg-success font-size-12">Active</span>'
                            : item.status === 'ended' 
                            ? '<span class="badge rounded-pill bg-danger font-size-12">Ended</span>'
                            : '<span class="badge rounded-pill bg-warning font-size-12">Paused</span>';
                        
                        const actionButtons = `
                            <div class="d-flex gap-2">
                                <a href="/screen-shares/${item.id}" class="btn btn-sm btn-soft-primary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                ${item.status === 'active' ? `
                                    <a href="/screen-shares/${item.id}" class="btn btn-sm btn-soft-success">
                                        <i class="mdi mdi-desktop-mac"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmEnd('${item.id}', '${item.room_code}')">
                                        <i class="mdi mdi-stop"></i>
                                    </button>
                                ` : ''}
                            </div>
                        `;
                        
                        html += `
                            <tr>
                                <th scope="row">${rowNumber}</th>
                                <td>
                                    <span class="badge rounded-pill bg-info font-size-12 font-weight-bold">${item.room_code}</span>
                                </td>
                                <td>
                                    <strong>${item.title || 'Untitled Session'}</strong>
                                </td>
                                <td>${statusBadge}</td>
                                <td>${item.participants_count} participants</td>
                                <td>${item.started_at || '-'}</td>
                                <td>${actionButtons}</td>
                            </tr>
                        `;
                    });
                    screenSharesTbody.innerHTML = html;
                }

                function updatePagination(pagination) {
                    if (!paginationInfo || !paginationLinks) return;
                    
                    // Update pagination info
                    const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
                    const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                    paginationInfo.textContent = `Showing ${startItem} to ${endItem} of ${pagination.total} data`;

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

            // End session confirmation script with SweetAlert2
            function confirmEnd(id, roomCode) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Session \"" + roomCode + "\" will be ended permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, end it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for ending session
                        fetch(`{{ route('screen-shares.end', ':id') }}`.replace(':id', id), {
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
                                Swal.fire('Ended!', data.message, 'success');
                                // Refresh the table
                                performAjaxSearch();
                            } else {
                                Swal.fire('Error!', data.message || 'Error occurred', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Error ending session', 'error');
                        });
                    }
                });
            }
        </script>
        
        <!-- Hidden end forms for each item -->
        @foreach($screenShares as $screenShare)
            <form id="endForm-{{ $screenShare->id }}" action="{{ route('screen-shares.end', $screenShare) }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endforeach
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
