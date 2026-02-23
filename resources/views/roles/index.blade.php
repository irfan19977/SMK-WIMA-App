@extends('layouts.master')
@section('title')
    {{ __('index.roles_management') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.roles_management') }}
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
                                <h4 class="card-title mb-1">{{ __('index.roles_list') }}</h4>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_role') }}
                                </a>
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
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_role_placeholder') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">Show:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $roles->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $roles->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $roles->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $roles->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                            <table class="table table-striped mb-0" id="roles-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('index.no') }}</th>
                                        <th>{{ __('index.role_name') }}</th>
                                        <th>{{ __('index.permissions') }}</th>
                                        <th>{{ __('index.created_by') }}</th>
                                        <th>{{ __('index.created_at') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="roles-tbody">
                                    @forelse($roles as $index => $role)
                                        <tr>
                                            <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $role->name }}</strong>
                                            </td>
                                            <td style="max-width: 300px; white-space: normal;">
                                                @foreach ($role->getPermissionNames() as $permission) 
                                                    <span class="badge bg-primary text-white mb-1 me-1" style="font-size: 0.75rem;" title="{{ $permission }}">{{ $permission }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ __('index.admin') }}</td>
                                            <td>
                                                <small class="text-muted">{{ $role->created_at->format('d M Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ __('index.active') }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-soft-primary" title="{{ __('index.edit') }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-soft-danger" onclick="deleteRole('{{ $role->id }}', '{{ $role->name }}')" title="{{ __('index.delete') }}">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                       <tr>
                                            <td colspan="7" class="text-center">{{ __('index.no_role_data_available') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($roles->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted" id="pagination-info">
                                    Menampilkan {{ $roles->firstItem() }} sampai {{ $roles->lastItem() }} dari {{ $roles->total() }} data
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination-links">
                                            {{-- Previous Link --}}
                                            @if($roles->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $roles->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $roles->lastPage(); $i++)
                                                @if($i == $roles->currentPage())
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">{{ $i }} <span class="sr-only">(current)</span></a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $roles->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            {{-- Next Link --}}
                                            @if($roles->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $roles->nextPageUrl() }}">Next</a>
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

                // Reload table function (AJAX like subjects)
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
                        const tbody = document.getElementById('roles-tbody');
                        if (tbody) {
                            tbody.innerHTML = html;
                            // Fix permission badges styling after AJAX reload
                            tbody.querySelectorAll('.badge').forEach(badge => {
                                if (!badge.classList.contains('bg-success')) {
                                    badge.classList.add('bg-primary', 'text-white');
                                    badge.style.fontSize = '0.75rem';
                                }
                            });
                        }
                        
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data'
                        });
                    }
                }
            });

            // Delete function
            function deleteRole(id, name) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Role "${name}" akan dihapus secara permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/roles/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Role berhasil dihapus',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message || 'Gagal menghapus role'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus role'
                            });
                        });
                    }
                });
            }

            // Export functions
            function exportExcel() {
                // Implement export Excel functionality
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Fitur export Excel akan segera tersedia',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            function printPDF() {
                window.print();
            }
        </script>
@endsection
