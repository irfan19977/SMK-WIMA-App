@extends('layouts.master')
@section('title')
    Daftar Izin Siswa
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    Daftar Izin Siswa
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title">Daftar Izin Siswa</h4>
                            <p class="card-title-desc">Kelola data izin siswa (Sakit, Agenda, Pulang Awal)</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#izin-modal" onclick="openIzinModal()">
                                <i class="mdi mdi-plus me-1"></i> Tambah Izin
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari nama atau NISN siswa" id="search-input" value="{{ request('q') }}">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="type-filter">
                                <option value="">Semua Tipe</option>
                                <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Izin Sakit</option>
                                <option value="agenda" {{ request('type') == 'agenda' ? 'selected' : '' }}>Izin Ada Agenda</option>
                                <option value="pulang_awal" {{ request('type') == 'pulang_awal' ? 'selected' : '' }}>Izin Pulang Awal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="class-filter">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="per-page-select">
                                <option value="10" {{ $permissions->perPage() == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $permissions->perPage() == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $permissions->perPage() == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $permissions->perPage() == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div id="loading-spinner" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <table class="table table-striped mb-0" id="izin-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tipe Izin</th>
                                    <th>Tanggal</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="izin-tbody">
                                @forelse ($permissions as $item)
                                <tr>
                                    <th scope="row">{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}</th>
                                    <td>{{ $item->student->nisn ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $item->student->name ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-primary font-size-12">{{ $item->student->getCurrentClass()?->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $item->type_badge }} font-size-12">
                                            {{ $item->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->end_date)
                                            {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($item->reason, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $item->status_badge }} font-size-12">
                                            {{ $item->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($item->status == 'pending')
                                                <button type="button" class="btn btn-sm btn-soft-success" onclick="approveIzin('{{ $item->id }}')" title="Setujui">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger" onclick="rejectIzin('{{ $item->id }}')" title="Tolak">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-soft-primary" onclick="editIzin('{{ $item->id }}')" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->student->name ?? '' }}')" title="Hapus">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data izin</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($permissions->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted" id="pagination-info">
                                Menampilkan {{ $permissions->firstItem() }} sampai {{ $permissions->lastItem() }} dari {{ $permissions->total() }} data
                            </div>
                            <div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" id="pagination-links">
                                        {{-- Previous Link --}}
                                        @if($permissions->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="#" data-page="{{ $permissions->currentPage() - 1 }}">Previous</a>
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

    <!-- Izin Modal -->
    <div class="modal fade" id="izin-modal" tabindex="-1" aria-labelledby="izin-modal-label" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="izin-modal-label">Tambah Izin Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="izin-modal-body">
                    <!-- Content will be loaded via AJAX -->
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
        let currentPerPage = {{ $permissions->perPage() }};
        let currentType = '{{ request('type', '') }}';
        let currentStatus = '{{ request('status', '') }}';
        let currentClass = '{{ request('class_id', '') }}';

        // Open izin modal
        function openIzinModal() {
            fetch('/izin/create', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('izin-modal-body').innerHTML = data.html;
                    document.getElementById('izin-modal-label').textContent = data.title;
                    
                    const modal = new bootstrap.Modal(document.getElementById('izin-modal'));
                    modal.show();
                    
                    // Initialize form after modal is shown
                    setTimeout(() => {
                        if (typeof initializeIzinForm === 'function') {
                            initializeIzinForm();
                        }
                    }, 300);
                    
                    // Add event listener for modal hidden to clean up backdrop
                    const modalElement = document.getElementById('izin-modal');
                    modalElement.addEventListener('hidden.bs.modal', function () {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Gagal memuat form'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat form: ' + error.message
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const perPageSelect = document.getElementById('per-page-select');
            const typeFilter = document.getElementById('type-filter');
            const statusFilter = document.getElementById('status-filter');
            const classFilter = document.getElementById('class-filter');
            const loadingSpinner = document.getElementById('loading-spinner');
            const izinTable = document.getElementById('izin-table');
            const izinTbody = document.getElementById('izin-tbody');
            const paginationLinks = document.getElementById('pagination-links');
            const paginationInfo = document.getElementById('pagination-info');

            // Set initial values
            if (searchInput && currentSearch) {
                searchInput.value = currentSearch;
            }
            if (typeFilter && currentType) {
                typeFilter.value = currentType;
            }
            if (statusFilter && currentStatus) {
                statusFilter.value = currentStatus;
            }
            if (classFilter && currentClass) {
                classFilter.value = currentClass;
            }

            // Auto-focus search input
            if (searchInput) {
                searchInput.focus();
                if (searchInput.value) {
                    searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                }
            }

            // Per page change
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    currentPerPage = this.value;
                    currentPage = 1;
                    performAjaxSearch();
                });
            }

            // Type filter change
            if (typeFilter) {
                typeFilter.addEventListener('change', function() {
                    currentType = this.value;
                    currentPage = 1;
                    performAjaxSearch();
                });
            }

            // Status filter change
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    currentStatus = this.value;
                    currentPage = 1;
                    performAjaxSearch();
                });
            }

            // Class filter change
            if (classFilter) {
                classFilter.addEventListener('change', function() {
                    currentClass = this.value;
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
            const izinTable = document.getElementById('izin-table');
            const izinTbody = document.getElementById('izin-tbody');
            const paginationLinks = document.getElementById('pagination-links');
            const paginationInfo = document.getElementById('pagination-info');
            
            showLoading();
            
            const params = new URLSearchParams();
            if (currentSearch) params.append('q', currentSearch);
            if (currentType) params.append('type', currentType);
            if (currentStatus) params.append('status', currentStatus);
            if (currentClass) params.append('class_id', currentClass);
            params.append('page', currentPage);
            params.append('per_page', currentPerPage);

            fetch(`/izin?${params.toString()}`, {
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
                if (izinTable) izinTable.style.opacity = '0.5';
            }

            function hideLoading() {
                if (loadingSpinner) loadingSpinner.style.display = 'none';
                if (izinTable) izinTable.style.opacity = '1';
            }

            function updateTable(izinData) {
                if (!izinTbody) return;
                
                if (izinData.length === 0) {
                    izinTbody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data izin</td></tr>';
                    return;
                }

                let html = '';
                izinData.forEach((item, index) => {
                    const rowNumber = (currentPage - 1) * currentPerPage + index + 1;
                    html += `
                        <tr>
                            <th scope="row">${rowNumber}</th>
                            <td>${item.student_nisn || '-'}</td>
                            <td><strong>${item.student_name || '-'}</strong></td>
                            <td><span class="badge rounded-pill bg-primary font-size-12">${item.class_name || '-'}</span></td>
                            <td><span class="badge rounded-pill ${item.type_badge} font-size-12">${item.type_label}</span></td>
                            <td>${item.date}</td>
                            <td><small>${item.reason_truncated || ''}</small></td>
                            <td><span class="badge rounded-pill ${item.status_badge} font-size-12">${item.status_label}</span></td>
                            <td>
                                <div class="d-flex gap-2">
                                    ${item.status === 'pending' ? `
                                        <button type="button" class="btn btn-sm btn-soft-success" onclick="approveIzin('${item.id}')" title="Setujui">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-soft-danger" onclick="rejectIzin('${item.id}')" title="Tolak">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    ` : ''}
                                    <button type="button" class="btn btn-sm btn-soft-primary" onclick="editIzin('${item.id}')" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-soft-danger" onclick="confirmDelete('${item.id}', '${item.student_name.replace(/'/g, "\\'")}')" title="Hapus">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                izinTbody.innerHTML = html;
            }

            function updatePagination(pagination) {
                if (!paginationInfo || !paginationLinks) return;
                
                const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
                const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                paginationInfo.textContent = `Menampilkan ${startItem} sampai ${endItem} dari ${pagination.total} data`;

                let html = '';
                
                if (pagination.current_page === 1) {
                    html += '<li class="page-item disabled"><a class="page-link" href="#" data-page="1" tabindex="-1">Previous</a></li>';
                } else {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a></li>`;
                }

                for (let i = 1; i <= pagination.last_page; i++) {
                    if (i === pagination.current_page) {
                        html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i} <span class="sr-only">(current)</span></a></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                }

                if (pagination.current_page === pagination.last_page) {
                    html += `<li class="page-item disabled"><a class="page-link" href="#" data-page="${pagination.last_page}" tabindex="-1">Next</a></li>`;
                } else {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a></li>`;
                }

                paginationLinks.innerHTML = html;
            }
        }

        // Edit izin
        function editIzin(id) {
            fetch(`/izin/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('izin-modal-body').innerHTML = data.html;
                    document.getElementById('izin-modal-label').textContent = data.title;
                    
                    const modal = new bootstrap.Modal(document.getElementById('izin-modal'));
                    modal.show();
                    
                    setTimeout(() => {
                        if (typeof initializeIzinForm === 'function') {
                            initializeIzinForm();
                        }
                    }, 300);
                    
                    const modalElement = document.getElementById('izin-modal');
                    modalElement.addEventListener('hidden.bs.modal', function () {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Gagal memuat form'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat form.'
                });
            });
        }

        // Approve izin
        function approveIzin(id) {
            Swal.fire({
                title: 'Setujui Izin?',
                text: 'Apakah Anda yakin ingin menyetujui izin ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/izin/${id}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Izin berhasil disetujui',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            performAjaxSearch();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menyetujui izin.'
                        });
                    });
                }
            });
        }

        // Reject izin
        function rejectIzin(id) {
            Swal.fire({
                title: 'Tolak Izin?',
                text: 'Apakah Anda yakin ingin menolak izin ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, tolak!',
                cancelButtonText: 'Batal',
                input: 'text',
                inputLabel: 'Alasan penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Harap masukkan alasan penolakan!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('rejection_reason', result.value);
                    
                    fetch(`/izin/${id}/reject`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Izin berhasil ditolak',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            performAjaxSearch();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menolak izin.'
                        });
                    });
                }
            });
        }

        // Delete confirmation
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Izin \"" + name + "\" akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/izin/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Data berhasil dihapus.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            performAjaxSearch();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    });
                }
            });
        }

        // Handle form submission for izin
        function initializeIzinForm() {
            const form = document.getElementById('izin-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const studentId = document.getElementById('student_id').value;
                    const type = document.getElementById('type').value;
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    const reason = document.getElementById('reason').value;
                    
                    if (!studentId || !type || !startDate || !reason) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Harap lengkapi semua field yang wajib diisi.'
                        });
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menyimpan...';
                    
                    const formData = new FormData(this);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw data;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Izin berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            
                            const modal = bootstrap.Modal.getInstance(document.getElementById('izin-modal'));
                            modal.hide();
                            performAjaxSearch();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Terjadi kesalahan.'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Terjadi kesalahan saat menyimpan data.'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }

            // Load students when class is selected
            const classSelect = document.getElementById('class_id');
            const studentSelect = document.getElementById('student_id');
            
            if (classSelect && studentSelect) {
                classSelect.addEventListener('change', function() {
                    const classId = this.value;
                    console.log('Class selected:', classId);
                    
                    if (classId) {
                        fetch(`/izin/get-students-by-class`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ class_id: classId })
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                let html = '<option value="">Pilih Siswa</option>';
                                if (data.data && data.data.length > 0) {
                                    data.data.forEach(student => {
                                        html += `<option value="${student.id}">${student.name} (${student.nisn})</option>`;
                                    });
                                } else {
                                    html += '<option value="">Tidak ada siswa di kelas ini</option>';
                                }
                                studentSelect.innerHTML = html;
                            } else {
                                console.error('Server returned error:', data);
                                studentSelect.innerHTML = '<option value="">Error loading students</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading students:', error);
                            studentSelect.innerHTML = '<option value="">Error loading students</option>';
                        });
                    } else {
                        studentSelect.innerHTML = '<option value="">Pilih Siswa</option>';
                    }
                });
            }
        }
    </script>
@endsection
