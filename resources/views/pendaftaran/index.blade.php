@extends('layouts.master')
@section('title')
    {{ __('index.pendaftaran_title') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ __('index.pendaftaran_title') }}
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
                                <h4 class="card-title mb-1">{{ __('index.new_registrants') }}</h4>
                            </div>
                            <div class="d-flex gap-2">
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
                                    <input type="text" class="form-control" placeholder="{{ __('index.search_placeholder') }}" id="search-input">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="academic-year-filter">
                                    @php
                                        $currentYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
                                        $academicYears = \App\Helpers\AcademicYearHelper::generateAcademicYears(1, 3);
                                        $selectedYear = request('tahun_akademik', $currentYear);
                                    @endphp
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
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
                                        <th>{{ __('index.no') }}.</th>
                                        <th>{{ __('index.name') }}</th>
                                        <th>{{ __('index.nisn') }}</th>
                                        <th>{{ __('index.nik') }}</th>
                                        <th>{{ __('index.major_primary') }}</th>
                                        <th>{{ __('index.major_secondary') }}</th>
                                        <th>{{ __('index.gender') }}</th>
                                        <th>{{ __('index.birth_place') }}</th>
                                        <th>{{ __('index.birth_date') }}</th>
                                        <th>{{ __('index.phone') }}</th>
                                        <th>{{ __('index.status') }}</th>
                                        <th>{{ __('index.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="students-tbody">
                                    @include('pendaftaran._table')
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(isset($students) && method_exists($students, 'hasPages') && $students->hasPages())
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
                                                        <a class="page-link" href="#">{{ $i }} <span class="sr-only">({{ __('index.current') }})</span></a>
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
        <!-- end row -->
@endsection
@section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchButton = document.getElementById('search-button');
                const academicYearFilter = document.getElementById('academic-year-filter');
                const perPageSelect = document.getElementById('per-page-select');

                // Per page change
                if (perPageSelect) {
                    perPageSelect.addEventListener('change', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    });
                }

                // Academic year filter change
                if (academicYearFilter) {
                    academicYearFilter.addEventListener('change', function() {
                        reloadTable();
                    });
                }

                // Search functionality
                let searchTimeout;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        reloadTable();
                    }, 500);
                });

                searchButton.addEventListener('click', function() {
                    clearTimeout(searchTimeout);
                    reloadTable();
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimeout);
                        reloadTable();
                    }
                });

                // Reload table function
                async function reloadTable() {
                    const url = new URL(window.location.href);
                    const q = (searchInput.value || '').trim();
                    
                    if (q) url.searchParams.set('q', q);
                    else url.searchParams.delete('q');
                    
                    if (academicYearFilter && academicYearFilter.value) {
                        url.searchParams.set('tahun_akademik', academicYearFilter.value);
                    } else {
                        url.searchParams.delete('tahun_akademik');
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
                        
                        bindActionButtons();
                        
                        // Update URL without partial param
                        const cleanUrl = new URL(window.location.href);
                        if (q) cleanUrl.searchParams.set('q', q);
                        else cleanUrl.searchParams.delete('q');
                        if (academicYearFilter && academicYearFilter.value) {
                            cleanUrl.searchParams.set('tahun_akademik', academicYearFilter.value);
                        } else {
                            cleanUrl.searchParams.delete('tahun_akademik');
                        }
                        cleanUrl.searchParams.delete('partial');
                        window.history.pushState({}, '', cleanUrl.toString());
                        
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("index.error") }}',
                            text: e.message || '{{ __("index.error_loading_data") }}'
                        });
                    }
                }

                // Bind action buttons
                function bindActionButtons() {
                    // Accept buttons
                    document.querySelectorAll('.btn-accept').forEach(function(btn) {
                        btn.addEventListener('click', async function () {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name') || '';
                            const jurusanUtama = this.getAttribute('data-jurusan-utama') || '';
                            const jurusanCadangan = this.getAttribute('data-jurusan-cadangan') || '';
                            
                            if (!id) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __("index.error") }}',
                                    text: '{{ __("index.applicant_id_not_found") }}'
                                });
                                return;
                            }

                            let optionsHtml = '';
                            if (jurusanUtama) {
                                optionsHtml += `<option value="${jurusanUtama}">${jurusanUtama}</option>`;
                            }
                            if (jurusanCadangan) {
                                optionsHtml += `<option value="${jurusanCadangan}">${jurusanCadangan}</option>`;
                            }

                            // Jika tidak ada jurusan sama sekali, tampilkan error
                            if (!jurusanUtama && !jurusanCadangan) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __("index.error") }}',
                                    text: '{{ __("index.applicant_no_major") }}'
                                });
                                return;
                            }

                            const { value: jurusan } = await Swal.fire({
                                title: '{{ __("index.accept_applicant") }}',
                                html: `
                                    <p>{{ __("index.accept_applicant_to_major_part1") }} <strong>${name}</strong> {{ __("index.accept_applicant_to_major_part2") }}</p>
                                    <select id="jurusan-select" class="swal2-select">
                                        ${optionsHtml}
                                    </select>
                                `,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '{{ __("index.yes_accept") }}',
                                cancelButtonText: '{{ __("index.cancel") }}',
                                preConfirm: () => {
                                    const select = document.getElementById('jurusan-select');
                                    if (!select) {
                                        Swal.showValidationMessage('{{ __("index.select_major_first") }}');
                                        return false;
                                    }
                                    return select.value;
                                }
                            });

                            if (jurusan) {
                                try {
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                                    if (!csrfToken) {
                                        throw new Error('CSRF token not found');
                                    }
                                    
                                    const res = await fetch(`/pendaftaran-siswa/${id}/accept`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                                        },
                                        body: JSON.stringify({ jurusan: jurusan })
                                    });

                                    if (!res.ok) throw new Error('{{ __("index.failed_accept_applicant") }}');
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: '{{ __("index.success") }}',
                                        text: '{{ __("index.applicant_accepted") }}',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    
                                    reloadTable();
                                    
                                } catch (e) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '{{ __("index.error") }}',
                                        text: e.message || '{{ __("index.error_occurred") }}'
                                    });
                                }
                            }
                        });
                    });

                    // Delete buttons (Reject)
                    document.querySelectorAll('.btn-delete').forEach(function(btn) {
                        btn.addEventListener('click', async function () {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name') || '';
                            
                            if (!id) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __("index.error") }}',
                                    text: '{{ __("index.applicant_id_not_found") }}'
                                });
                                return;
                            }

                            const result = await Swal.fire({
                                title: '{{ __("index.are_you_sure") }}',
                                text: `{{ __("index.applicant_will_be_rejected_part1") }}"${name}"{{ __("index.applicant_will_be_rejected_part2") }}`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '{{ __("index.yes_reject") }}',
                                cancelButtonText: '{{ __("index.cancel") }}'
                            });

                            if (result.isConfirmed) {
                                try {
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                                    if (!csrfToken) {
                                        throw new Error('CSRF token not found');
                                    }
                                    
                                    const res = await fetch(`/pendaftaran-siswa/${id}/reject`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                                        }
                                    });

                                    if (!res.ok) throw new Error('{{ __("index.failed_reject_applicant") }}');
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: '{{ __("index.success") }}',
                                        text: '{{ __("index.applicant_rejected") }}',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    
                                    reloadTable();
                                    
                                } catch (e) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '{{ __("index.error") }}',
                                        text: e.message || '{{ __("index.error_occurred") }}'
                                    });
                                }
                            }
                        });
                    });
                }

                // Export functions
                window.exportExcel = function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('export', 'excel');
                    window.open(url.toString(), '_blank');
                };

                window.printPDF = function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('print', 'pdf');
                    window.open(url.toString(), '_blank');
                };

                // Initialize buttons
                bindActionButtons();
            });
        </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection