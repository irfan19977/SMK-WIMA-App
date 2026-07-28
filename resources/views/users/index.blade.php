@extends('layouts.master')
@section('title')
    {{ __('index.user_management') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.user_management') }}
@endsection
@section('body')
    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-1">{{ __('index.user_management') }}</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> {{ __('index.add_user') }}
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
                                <input type="text" class="form-control" placeholder="{{ __('index.search_user') }}" id="search-input" value="{{ request('q') }}">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <label class="mb-0">Show:</label>
                                <select class="form-select w-auto" id="per-page-select">
                                    <option value="10" {{ $users->perPage() == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $users->perPage() == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $users->perPage() == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $users->perPage() == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('index.no') }}</th>
                                        <th>{{ __('index.name') }}</th>
                                        <th>{{ __('index.email') }}</th>
                                        <th>{{ __('index.phone') }}</th>
                                        <th>{{ __('index.role') }}</th>
                                        <th>{{ __('index.join_date') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="users-tbody">
                                    @include('users._table', ['users' => $users])
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    {{ __('index.showing') }} {{ $users->firstItem() }} {{ __('index.to') }} {{ $users->lastItem() }} {{ __('index.of') }} {{ $users->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            {{-- Previous Link --}}
                                            @if($users->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1">{{ __('index.previous') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $users->previousPageUrl() }}">{{ __('index.previous') }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $users->lastPage(); $i++)
                                                @if($i == $users->currentPage())
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">{{ $i }} <span class="sr-only">(current)</span></a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            {{-- Next Link --}}
                                            @if($users->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $users->nextPageUrl() }}">{{ __('index.next') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1">{{ __('index.next') }}</a>
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
@endsection
@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const perPageSelect = document.getElementById('per-page-select');
            let searchTimeout;
            
            // Auto-focus search input
            searchInput.focus();
            
            // Auto-search after 500ms of inactivity using AJAX
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    reloadTable();
                }, 500);
            });

            // Search button click using AJAX
            searchButton.addEventListener('click', function() {
                clearTimeout(searchTimeout);
                reloadTable();
            });

            // Enter key search using AJAX
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    reloadTable();
                }
            });

            // Per page change
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    reloadTable();
                });
            }

            // Reload table function (AJAX like students)
            async function reloadTable() {
                const url = new URL(window.location.href);
                const q = (searchInput.value || '').trim();
                
                if (q) url.searchParams.set('q', q);
                else url.searchParams.delete('q');
                
                if (perPageSelect && perPageSelect.value) {
                    url.searchParams.set('per_page', perPageSelect.value);
                }
                
                url.searchParams.set('partial', '1');

                try {
                    const res = await fetch(url.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    
                    if (!res.ok) throw new Error('Gagal memuat data');
                    const html = await res.text();
                    const tbody = document.getElementById('users-tbody');
                    if (tbody) {
                        tbody.innerHTML = html;
                    }
                    
                    // Re-bind action buttons after reload
                    bindActionButtons();
                    
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data'
                    });
                }
            }

            // Bind action buttons
            bindActionButtons();

            function bindActionButtons() {
            // Toggle Active buttons
            document.querySelectorAll('.btn-toggle-active').forEach(function(btn) {
                btn.addEventListener('click', async function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';

                    const result = await Swal.fire({
                        title: '{{ __("index.are_you_sure") }}',
                        text: "{{ __("index.want_to_toggle_user_status") }}",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __("index.yes") }}',
                        cancelButtonText: '{{ __("index.cancel") }}'
                    });

                    if (result.isConfirmed) {
                        try {
                            const res = await fetch(`{{ route('users.toggle-active', ':id') }}`.replace(':id', id), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const data = await res.json();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("index.success") }}',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                
                                reloadTable();
                                
                            } else {
                                throw new Error(data.message || 'Gagal mengubah status');
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: e.message
                            });
                        }
                    }
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', async function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';

                    const result = await Swal.fire({
                        title: '{{ __("index.are_you_sure") }}',
                        text: `{{ __("index.user_will_be_deleted") }} "${name}"!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __("index.yes_delete") }}',
                        cancelButtonText: '{{ __("index.cancel") }}'
                    });

                    if (result.isConfirmed) {
                        try {
                            const res = await fetch(`/users/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const data = await res.json();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __("index.deleted") }}',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                
                                reloadTable();
                                
                            } else {
                                throw new Error(data.message || 'Gagal menghapus user');
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: e.message
                            });
                        }
                    }
                });
            });
        }

        });

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
    
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
