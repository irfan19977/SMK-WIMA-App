@extends('layouts.master')
@section('title')
    Daftar Pendaftar
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    Daftar Pendaftar
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
                                <h4 class="card-title mb-1">Daftar Pendaftar Baru</h4>
                            </div>
                            <div class="d-flex gap-2">
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
                                    <input type="text" class="form-control" placeholder="Cari Siswa (Nama, NISN)" id="search-input">
                                    <button class="btn btn-primary" type="button" id="search-button">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>NISN</th>
                                        <th>NIK</th>
                                        <th>Jurusan Utama</th>
                                        <th>Jurusan Cadangan</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Nomor HP</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="students-tbody">
                                    @include('pendaftaran._table')
                                </tbody>
                            </table>
                        </div>

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
                            title: 'Error',
                            text: e.message || 'Terjadi kesalahan saat memuat data'
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
                                    title: 'Error',
                                    text: 'ID pendaftar tidak ditemukan'
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
                                    title: 'Error',
                                    text: 'Pendaftar tidak memiliki jurusan pilihan'
                                });
                                return;
                            }

                            const { value: jurusan } = await Swal.fire({
                                title: 'Terima Pendaftar',
                                html: `
                                    <p>Terima pendaftar <strong>${name}</strong> ke jurusan:</p>
                                    <select id="jurusan-select" class="swal2-select">
                                        ${optionsHtml}
                                    </select>
                                `,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, terima!',
                                cancelButtonText: 'Batal',
                                preConfirm: () => {
                                    const select = document.getElementById('jurusan-select');
                                    if (!select) {
                                        Swal.showValidationMessage('Pilih jurusan terlebih dahulu');
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

                                    if (!res.ok) throw new Error('Gagal menerima pendaftar');
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Pendaftar berhasil diterima',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    
                                    reloadTable();
                                    
                                } catch (e) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: e.message || 'Terjadi kesalahan'
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
                                    title: 'Error',
                                    text: 'ID pendaftar tidak ditemukan'
                                });
                                return;
                            }

                            const result = await Swal.fire({
                                title: 'Apakah Anda yakin?',
                                text: `Pendaftar "${name}" akan ditolak dan datanya dihapus permanen!`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, tolak!',
                                cancelButtonText: 'Batal'
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

                                    if (!res.ok) throw new Error('Gagal menolak pendaftar');
                                    
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Pendaftar berhasil ditolak',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    
                                    reloadTable();
                                    
                                } catch (e) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: e.message || 'Terjadi kesalahan'
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