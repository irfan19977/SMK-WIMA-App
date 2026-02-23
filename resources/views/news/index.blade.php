@extends('layouts.master')
@section('title')
    {{ __('index.news_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.news_list') }}
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
                                <h4 class="card-title mb-1">{{ __('index.news_list') }}</h4>                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('news.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_news') }}
                                </a>
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
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_news_title_category_author') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">{{ __('index.show') }}:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $news->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $news->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $news->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $news->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                            <table class="table table-striped mb-0" id="news-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('index.title') }}</th>
                                        <th>{{ __('index.category') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.publish_date') }}</th>
                                        <th>{{ __('index.author') }}</th>
                                        <th>{{ __('index.views') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="news-tbody">
                                    @forelse ($news as $item)
                                    <tr>
                                        <th scope="row">{{ ($news->currentPage() - 1) * $news->perPage() + $loop->iteration }}</th>
                                        <td>
                                            <strong>{{ $item->title }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-primary font-size-12">{{ $item->category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ $item->is_published ? 'bg-success' : 'bg-light' }} font-size-12">
                                                {{ $item->is_published ? __('index.published') : __('index.draft') }}
                                            </span>
                                        </td>
                                        <td>{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</td>
                                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                                        <td>{{ $item->view_count ?? 0 }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-soft-primary">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->title }}')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('index.no_news_data') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($news->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="pagination-info">
                                    {{ __('index.showing') }} {{ $news->firstItem() }} {{ __('index.to') }} {{ $news->lastItem() }} {{ __('index.of') }} {{ $news->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination-links">
                                            {{-- Previous Link --}}
                                            @if($news->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="1" tabindex="-1">{{ __("index.previous") }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $news->currentPage() - 1 }}">{{ __("index.previous") }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $news->lastPage(); $i++)
                                                @if($i == $news->currentPage())
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
                                            @if($news->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="#" data-page="{{ $news->currentPage() + 1 }}">Next</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="{{ $news->lastPage() }}" tabindex="-1">Next</a>
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
            let currentPerPage = {{ $news->perPage() }};

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const perPageSelect = document.getElementById('per-page-select');
                const loadingSpinner = document.getElementById('loading-spinner');
                const newsTable = document.getElementById('news-table');
                const newsTbody = document.getElementById('news-tbody');
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
                const newsTable = document.getElementById('news-table');
                const newsTbody = document.getElementById('news-tbody');
                const paginationLinks = document.getElementById('pagination-links');
                const paginationInfo = document.getElementById('pagination-info');
                
                showLoading();
                
                const params = new URLSearchParams();
                if (currentSearch) params.append('q', currentSearch);
                params.append('page', currentPage);
                params.append('per_page', currentPerPage);

                fetch(`{{ route('news.index') }}?${params.toString()}`, {
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
                    if (newsTable) newsTable.style.opacity = '0.5';
                }

                function hideLoading() {
                    if (loadingSpinner) loadingSpinner.style.display = 'none';
                    if (newsTable) newsTable.style.opacity = '1';
                }

                function updateTable(newsData) {
                    if (!newsTbody) return;
                    
                    if (newsData.length === 0) {
                        newsTbody.innerHTML = '<tr><td colspan="8" class="text-center">{{ __('index.no_news_data') }}</td></tr>';
                        return;
                    }

                    let html = '';
                    newsData.forEach((item, index) => {
                        const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                        html += `
                            <tr>
                                <th scope="row">${rowNumber}</th>
                                <td>
                                    <strong>${item.title}</strong>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-primary font-size-12">${item.category}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill ${item.is_published ? 'bg-success' : 'bg-light'} font-size-12">
                                        ${item.is_published ? '{{ __('index.published') }}' : '{{ __('index.draft') }}'}
                                    </span>
                                </td>
                                <td>${item.published_at}</td>
                                <td>${item.user ? item.user.name : 'N/A'}</td>
                                <td>${item.view_count}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="/news/${item.id}/edit" class="btn btn-sm btn-soft-primary">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('${item.id}', '${item.title.replace(/'/g, "\\'")}')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    newsTbody.innerHTML = html;
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

            // Print PDF
            function printPDF() {
                const url = new URL(window.location.href);
                url.searchParams.set('print', 'pdf');
                window.open(url.toString(), '_blank');
            }

            // Delete confirmation script with SweetAlert2
            function confirmDelete(id, title) {
                Swal.fire({
                    title: '{{ __('index.are_you_sure') }}',
                    text: "{{ __('index.news_will_be_deleted_permanently') }} \"" + title + "\"!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('index.yes_delete') }}',
                    cancelButtonText: '{{ __('index.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use AJAX for deletion
                        fetch(`{{ route('news.destroy', ':id') }}`.replace(':id', id), {
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
                            Swal.fire('{{ __('index.error') }}!', '{{ __('index.error_deleting_news') }}', 'error');
                        });
                    }
                });
            }
        </script>
        
        <!-- Hidden delete forms for each item -->
        @foreach($news as $item)
            <form id="deleteForm-{{ $item->id }}" action="{{ route('news.destroy', $item->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
