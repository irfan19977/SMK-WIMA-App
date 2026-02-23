@extends('layouts.master')
@section('title')
    {{ __('index.student_list') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.student_list') }}
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
                                <h4 class="card-title mb-1">{{ __('index.student_list') }}</h4>                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('students.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('index.add_student') }}
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
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_student_name_nisn_email') }}" id="search-input" value="{{ request('q') }}">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2 justify-content-end">
                                    <label class="mb-0">{{ __('index.show') }}:</label>
                                    <select class="form-select w-auto" id="per-page-select">
                                        <option value="10" {{ $students->perPage() == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $students->perPage() == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $students->perPage() == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $students->perPage() == 100 ? 'selected' : '' }}>100</option>
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
                                        <th>{{ __('index.nisn') }}</th>
                                        <th>{{ __('index.email') }}</th>
                                        <th>{{ __('index.phone') }}</th>
                                        <th>{{ __('index.card_number') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="students-tbody">
                                    @include('students._table', ['students' => $students])
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($students->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    {{ __('index.showing') }} {{ $students->firstItem() }} {{ __('index.to') }} {{ $students->lastItem() }} {{ __('index.of') }} {{ $students->total() }} {{ __('index.data') }}
                                </div>
                                <div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            {{-- Previous Link --}}
                                            @if($students->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1">{{ __('index.previous') }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $students->previousPageUrl() }}">{{ __('index.previous') }}</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @for($i = 1; $i <= $students->lastPage(); $i++)
                                                @if($i == $students->currentPage())
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">{{ $i }} <span class="sr-only">(current)</span></a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            {{-- Next Link --}}
                                            @if($students->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $students->nextPageUrl() }}">{{ __('index.next') }}</a>
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
        <!-- Sweet Alert-->
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

                // Reload table function (AJAX like pendaftaran)
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
                        const tbody = document.getElementById('students-tbody');
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
            });

            function bindActionButtons() {
                // Delete buttons
                document.querySelectorAll('.btn-delete').forEach(function(btn) {
                    btn.addEventListener('click', async function () {
                        const id = this.getAttribute('data-id');
                        const name = this.getAttribute('data-name') || '';

                        const result = await Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: `Siswa "${name}" akan dihapus permanen!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        });

                        if (result.isConfirmed) {
                            try {
                                const res = await fetch(`/students/${id}`, {
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
                                        title: 'Berhasil!',
                                        text: 'Siswa berhasil dihapus',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    
                                    reloadTable();
                                    
                                } else {
                                    throw new Error(data.message || 'Gagal menghapus siswa');
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
        </script>
    @endsection
